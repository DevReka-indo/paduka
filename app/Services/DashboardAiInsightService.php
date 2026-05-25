<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardAiInsightService
{
    public function generate(array $summary, string $cacheKey): array
    {
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($summary) {
            try {
                $response = Http::withToken(config('services.groq.key'))
                    ->timeout(30)
                    ->post(rtrim(config('services.groq.url'), '/') . '/chat/completions', [
                        'model' => config('services.groq.model'),
                        'temperature' => 0.3,
                        'max_tokens' => 700,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'Kamu adalah analis dashboard KPI internal PADUKA. Jawab hanya dalam JSON valid tanpa markdown.',
                            ],
                            [
                                'role' => 'user',
                                'content' => $this->buildPrompt($summary),
                            ],
                        ],
                    ]);

                if ($response->failed()) {
                    Log::error('Groq AI Insight dashboard gagal', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return $this->fallbackInsight($summary);
                }

                $text = data_get($response->json(), 'choices.0.message.content');

                if (!$text) {
                    return $this->fallbackInsight($summary);
                }

                return $this->parseInsight($text);
            } catch (\Throwable $e) {
                Log::error('Gagal generate AI Insight dashboard utama', [
                    'message' => $e->getMessage(),
                    'summary' => $summary,
                ]);

                return $this->fallbackInsight($summary);
            }
        });
    }

    private function buildPrompt(array $summary): string
    {
        return '
        Buat 6 insight singkat dalam Bahasa Indonesia berdasarkan data dashboard PADUKA berikut.

        Konteks:
        - NCR adalah data Non Conformance Report.
        - Feedback pelanggan adalah data survei kepuasan pelanggan.
        - Presentase feedback dihitung dari rata-rata skor dibagi target KPI 3 lalu dikali 100%.
        - Jika presentase feedback di atas 100%, artinya KPI melampaui target.
        - NCR open adalah data yang masih perlu ditindaklanjuti.
        - NCR terlambat adalah NCR open yang sudah melewati tanggal target.

        Aturan:
        - Jangan mengarang angka di luar data JSON.
        - Jangan menyebut data yang tidak tersedia.
        - Setiap insight maksimal 1 kalimat.
        - Gunakan gaya profesional, ringkas, dan mudah dipahami.
        - Berikan kombinasi insight NCR dan feedback pelanggan.
        - Jawaban wajib JSON valid tanpa markdown.

        Format wajib:
        {
          "insights": [
            {"type":"success","text":"..."},
            {"type":"warning","text":"..."},
            {"type":"danger","text":"..."},
            {"type":"info","text":"..."}
          ]
        }

        Larangan:
        - Jangan bungkus dengan key response.
        - Jangan bungkus dengan key insights.
        - Jangan gunakan markdown.
        - Jangan tambahkan teks sebelum atau sesudah JSON.
        - Output harus langsung berupa array JSON valid.

        Kamu adalah analis dashboard KPI internal PADUKA. Jawab hanya dalam JSON valid tanpa markdown.

        Data:
        ' . json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function parseInsight(string $text): array
    {
        $originalText = trim($text);

        $text = trim($originalText);
        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);

        $decoded = $this->decodeAiJson($text);

        if (!is_array($decoded)) {
            Log::warning('Format AI Insight dashboard bukan JSON valid setelah normalisasi', [
                'response' => $originalText,
            ]);

            return $this->invalidInsightResponse();
        }

        if (isset($decoded['response'])) {
            $responseValue = $decoded['response'];

            if (is_string($responseValue)) {
                $innerDecoded = $this->decodeAiJson($responseValue);

                if (is_array($innerDecoded)) {
                    $decoded = $innerDecoded;
                }
            } elseif (is_array($responseValue)) {
                $decoded = $responseValue;
            }
        }

        if (isset($decoded['insights']) && is_array($decoded['insights'])) {
            $decoded = $decoded['insights'];
        }

        if (!is_array($decoded) || !array_is_list($decoded)) {
            Log::warning('Format AI Insight dashboard bukan array insight valid setelah normalisasi', [
                'response' => $originalText,
                'decoded' => $decoded,
            ]);

            return $this->invalidInsightResponse();
        }

        $insights = collect($decoded)
            ->filter(fn ($item) => is_array($item) && isset($item['text']))
            ->map(fn ($item) => [
                'type' => in_array($item['type'] ?? 'info', ['success', 'warning', 'danger', 'info'])
                    ? $item['type']
                    : 'info',
                'text' => trim((string) $item['text']),
            ])
            ->filter(fn ($item) => $item['text'] !== '')
            ->take(6)
            ->values()
            ->toArray();

        if (empty($insights)) {
            Log::warning('AI Insight dashboard kosong setelah parsing', [
                'response' => $originalText,
                'decoded' => $decoded,
            ]);

            return $this->invalidInsightResponse();
        }

        return $insights;
    }

    private function decodeAiJson(string $text): mixed
    {
        $text = trim($text);

        $decoded = json_decode($text, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        /*
        * Fallback khusus:
        * Kadang model/API mengembalikan wrapper seperti:
        * {"response":"[{\"type\":\"success\",\"text\":\"...\"}]"}
        * tetapi wrapper luarnya bisa gagal didecode.
        *
        * Maka kita ambil isi response secara manual.
        */
        $manualResponse = $this->extractEscapedResponseValue($text);

        if ($manualResponse) {
            $unescapedResponse = stripcslashes($manualResponse);
            $decoded = json_decode($unescapedResponse, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }

            $arrayText = $this->extractJsonArray($unescapedResponse);

            if ($arrayText) {
                $decoded = json_decode($arrayText, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
        }

        $objectText = $this->extractJsonObject($text);

        if ($objectText) {
            $decoded = json_decode($objectText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }

            $manualResponse = $this->extractEscapedResponseValue($objectText);

            if ($manualResponse) {
                $unescapedResponse = stripcslashes($manualResponse);
                $decoded = json_decode($unescapedResponse, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
        }

        $arrayText = $this->extractJsonArray($text);

        if ($arrayText) {
            $decoded = json_decode($arrayText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        $unescaped = stripcslashes($text);

        $decoded = json_decode($unescaped, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        $arrayText = $this->extractJsonArray($unescaped);

        if ($arrayText) {
            $decoded = json_decode($arrayText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return null;
    }

    private function extractEscapedResponseValue(string $text): ?string
    {
        /*
        * Ambil isi dari:
        * "response":"..."
        *
        * Regex ini mendukung escaped quote seperti \" di dalam string.
        */
        if (preg_match('/"response"\s*:\s*"((?:\\\\.|[^"\\\\])*)"/s', $text, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function extractJsonObject(string $text): ?string
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');

        if ($start === false || $end === false || $end <= $start) {
            return null;
        }

        return substr($text, $start, $end - $start + 1);
    }

    private function extractJsonArray(string $text): ?string
    {
        $start = strpos($text, '[');
        $end = strrpos($text, ']');

        if ($start === false || $end === false || $end <= $start) {
            return null;
        }

        return substr($text, $start, $end - $start + 1);
    }

    private function invalidInsightResponse(): array
    {
        return [
            [
                'type' => 'warning',
                'text' => 'AI Insight belum dapat diproses karena format respons tidak valid.',
            ],
        ];
    }

    private function fallbackInsight(array $summary): array
    {
        $ncr = $summary['ncr'] ?? [];
        $feedback = $summary['feedback'] ?? [];

        $insights = [];

        if (isset($ncr['total_ncr'])) {
            $insights[] = [
                'type' => 'info',
                'text' => 'Terdapat ' . $ncr['total_ncr'] . ' data NCR pada periode ini.',
            ];
        }

        if (!empty($ncr['ncr_open'])) {
            $insights[] = [
                'type' => 'warning',
                'text' => 'Masih terdapat ' . $ncr['ncr_open'] . ' NCR berstatus open yang perlu ditindaklanjuti.',
            ];
        }

        if (!empty($ncr['ncr_terlambat'])) {
            $insights[] = [
                'type' => 'danger',
                'text' => 'Terdapat ' . $ncr['ncr_terlambat'] . ' NCR open yang sudah melewati tanggal target.',
            ];
        }

        if (!empty($feedback['total_feedback'])) {
            $insights[] = [
                'type' => 'success',
                'text' => 'Terdapat ' . $feedback['total_feedback'] . ' feedback pelanggan dengan rata-rata skor ' . ($feedback['nilai_rata_rata'] ?? '-') . '.',
            ];
        }

        if (!empty($feedback['presentase'])) {
            $type = $feedback['presentase'] >= 100 ? 'success' : 'warning';

            $insights[] = [
                'type' => $type,
                'text' => 'Presentase KPI feedback pelanggan mencapai ' . $feedback['presentase'] . '%.',
            ];
        }

        return $insights ?: [
                [
                    'type' => 'info',
                    'text' => 'Belum ada data yang cukup untuk membuat insight dashboard.',
                ],
            ];
    }
}
