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

        Format:
        [
        {"type":"success","text":"..."},
        {"type":"warning","text":"..."},
        {"type":"danger","text":"..."},
        {"type":"info","text":"..."}
        ]

        Data:
        ' . json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function parseInsight(string $text): array
    {
        $text = trim($text);

        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);

        $decoded = json_decode(trim($text), true);

        if (!is_array($decoded)) {
            Log::warning('Format AI Insight dashboard bukan JSON valid', [
                'response' => $text,
            ]);

            return [
                [
                    'type' => 'warning',
                    'text' => 'AI Insight belum dapat diproses karena format respons tidak valid.',
                ],
            ];
        }

        return collect($decoded)
            ->filter(fn($item) => isset($item['text']))
            ->map(
                fn($item) => [
                    'type' => in_array($item['type'] ?? 'info', ['success', 'warning', 'danger', 'info']) ? $item['type'] : 'info',
                    'text' => $item['text'],
                ],
            )
            ->take(6)
            ->values()
            ->toArray();
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
