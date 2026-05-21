<?php

namespace App\Http\Controllers;

use App\Models\Ncr;
use App\Models\User;
use App\Models\Project;
use App\Models\FeedbackPelanggan;
use App\Services\DashboardAiInsightService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardAiInsightService $aiInsightService): View
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $level = strtolower($authUser->level ?? '');
        $isAdmin = in_array($level, ['admin', 'superadmin']);

        $baseQuery = $this->baseNcrQuery($authUser, $isAdmin);

        /*
        |--------------------------------------------------------------------------
        | Statistik utama total keseluruhan NCR
        |--------------------------------------------------------------------------
        */
        $totalNcr = (clone $baseQuery)->count();
        $totalOpen = (clone $baseQuery)->where('keterangan', 'open')->count();
        $totalProses = (clone $baseQuery)->where('keterangan', 'process')->count();
        $totalClose = (clone $baseQuery)->whereIn('keterangan', ['close', 'closed'])->count();

        /*
        |--------------------------------------------------------------------------
        | Filter bulan dashboard
        |--------------------------------------------------------------------------
        */
        $defaultFrom = now()->subMonths(11)->format('Y-m');
        $defaultTo = now()->format('Y-m');

        $from = request('from', $defaultFrom);
        $to = request('to', $defaultTo);

        $startDate = Carbon::createFromFormat('Y-m', $from)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $to)->endOfMonth();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
            [$from, $to] = [$to, $from];
        }

        /*
        |--------------------------------------------------------------------------
        | Dropdown bulan-tahun
        |--------------------------------------------------------------------------
        */
        $availableMonths = collect();
        $monthCursor = now()->subMonths(23)->startOfMonth();

        while ($monthCursor->lte(now()->startOfMonth())) {
            $availableMonths->push([
                'value' => $monthCursor->format('Y-m'),
                'label' => $monthCursor->translatedFormat('F Y'),
            ]);

            $monthCursor->addMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | Query NCR berdasarkan range filter
        |--------------------------------------------------------------------------
        */
        $filteredQuery = (clone $baseQuery)->whereBetween('tgl_masuk', [$startDate, $endDate]);

        $filteredTotalNcr = (clone $filteredQuery)->count();
        $filteredTotalOpen = (clone $filteredQuery)->where('keterangan', 'open')->count();
        $filteredTotalProses = (clone $filteredQuery)->where('keterangan', 'process')->count();
        $filteredTotalClose = (clone $filteredQuery)->whereIn('keterangan', ['close', 'closed'])->count();

        /*
        |--------------------------------------------------------------------------
        | Bar chart NCR sesuai range filter
        |--------------------------------------------------------------------------
        */
        $bulanLabel = [];
        $dataOpen = [];
        $dataProses = [];
        $dataClose = [];

        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $bulanLabel[] = $current->translatedFormat('M Y');

            $dataOpen[] = (clone $baseQuery)->where('keterangan', 'open')->whereYear('tgl_masuk', $current->year)->whereMonth('tgl_masuk', $current->month)->count();

            $dataProses[] = (clone $baseQuery)->where('keterangan', 'process')->whereYear('tgl_masuk', $current->year)->whereMonth('tgl_masuk', $current->month)->count();

            $dataClose[] = (clone $baseQuery)
                ->whereIn('keterangan', ['close', 'closed'])
                ->whereYear('tgl_masuk', $current->year)
                ->whereMonth('tgl_masuk', $current->month)
                ->count();

            $current->addMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | NCR mendekati target
        |--------------------------------------------------------------------------
        */
        // $ncrMendekatiTarget = (clone $baseQuery)
        //     ->with(['project', 'penanggungJawab'])
        //     ->whereNotIn('keterangan', ['close', 'closed'])
        //     ->whereNotNull('tgl_target')
        //     ->where('tgl_target', '<=', Carbon::now()->addDays(7))
        //     ->orderBy('tgl_target', 'asc')
        //     ->limit(10)
        //     ->get()
        //     ->map(function ($ncr) {
        //         $ncr->sisa_hari = Carbon::now()
        //             ->startOfDay()
        //             ->diffInDays(Carbon::parse($ncr->tgl_target)->startOfDay(), false);

        //         return $ncr;
        //     });

        /*
        |--------------------------------------------------------------------------
        | Data admin
        |--------------------------------------------------------------------------
        */
        $totalProject = $isAdmin ? Project::count() : null;
        $totalUser = $isAdmin ? User::where('keterangan', true)->count() : null;

        /*
        |--------------------------------------------------------------------------
        | Tambahan Summary NCR untuk AI Insight
        |--------------------------------------------------------------------------
        */
        $ncrTerlambat = (clone $filteredQuery)->where('keterangan', 'open')->whereNotNull('tgl_target')->whereDate('tgl_target', '<', Carbon::today())->count();

        $kategoriMasalahDominan = (clone $filteredQuery)->select('kategori_masalah', DB::raw('COUNT(*) as total'))->whereNotNull('kategori_masalah')->where('kategori_masalah', '!=', '')->groupBy('kategori_masalah')->orderByDesc('total')->first();

        $unitNcrDominan = (clone $filteredQuery)->select('unit_tujuan', DB::raw('COUNT(*) as total'))->whereNotNull('unit_tujuan')->where('unit_tujuan', '!=', '')->groupBy('unit_tujuan')->orderByDesc('total')->first();

        /*
        |--------------------------------------------------------------------------
        | Summary Feedback Pelanggan untuk AI Insight
        |--------------------------------------------------------------------------
        | Feedback memakai range yang sama dengan dashboard utama.
        | NCR memakai tgl_masuk, feedback memakai created_at.
        */
        $feedbackQuery = FeedbackPelanggan::query()->whereBetween('created_at', [$startDate, $endDate]);

        $feedbackCollection = $feedbackQuery->get();

        $totalFeedback = $feedbackCollection->count();
        $avgFeedbackScore = $feedbackCollection->avg('rata_rata');
        $maxFeedbackScore = $feedbackCollection->max('rata_rata');
        $minFeedbackScore = $feedbackCollection->min('rata_rata');

        $feedbackPercentage = $avgFeedbackScore ? ($avgFeedbackScore / 3) * 100 : null;

        /*
        |--------------------------------------------------------------------------
        | Periode label
        |--------------------------------------------------------------------------
        */
        $periodeLabel = $startDate->translatedFormat('F Y') . ' - ' . $endDate->translatedFormat('F Y');

        /*
        |--------------------------------------------------------------------------
        | Generate AI Insight NCR + Feedback
        |--------------------------------------------------------------------------
        */
        $dashboardSummary = [
            'periode' => $periodeLabel,

            'ncr' => [
                'total_ncr' => $filteredTotalNcr,
                'ncr_open' => $filteredTotalOpen,
                'ncr_process' => $filteredTotalProses,
                'ncr_close' => $filteredTotalClose,
                'ncr_terlambat' => $ncrTerlambat,

                'kategori_masalah_dominan' => $kategoriMasalahDominan?->kategori_masalah,
                'total_kategori_masalah_dominan' => $kategoriMasalahDominan?->total,

                'unit_ncr_dominan' => $unitNcrDominan?->unit_tujuan,
                'total_unit_ncr_dominan' => $unitNcrDominan?->total,
            ],

            'feedback' => [
                'total_feedback' => $totalFeedback,
                'nilai_rata_rata' => $avgFeedbackScore ? round($avgFeedbackScore, 2) : null,
                'nilai_tertinggi' => $maxFeedbackScore ? round($maxFeedbackScore, 2) : null,
                'nilai_terendah' => $minFeedbackScore ? round($minFeedbackScore, 2) : null,
                'presentase' => $feedbackPercentage ? round($feedbackPercentage, 2) : null,
                'target_kpi' => 3,
            ],
        ];

        $aiInsightCacheKey = 'dashboard_ai_insight_' . md5(json_encode($dashboardSummary));

        $aiInsights = $aiInsightService->generate($dashboardSummary, $aiInsightCacheKey);

        return view(
            'dashboard',
            compact(
                'totalNcr',
                'totalOpen',
                'totalProses',
                'totalClose',

                'filteredTotalNcr',
                'filteredTotalOpen',
                'filteredTotalProses',
                'filteredTotalClose',

                'bulanLabel',
                'dataOpen',
                'dataProses',
                'dataClose',

                'availableMonths',
                'defaultFrom',
                'defaultTo',
                'from',
                'to',

                'totalProject',
                'totalUser',
                'isAdmin',
                'level',

                'ncrTerlambat',
                'totalFeedback',
                'avgFeedbackScore',
                'maxFeedbackScore',
                'minFeedbackScore',
                'feedbackPercentage',
                'periodeLabel',
                'aiInsights',
            ),
        );
    }

    private function baseNcrQuery(User $authUser, bool $isAdmin)
    {
        $query = Ncr::query();

        if (!$isAdmin && in_array($authUser->level, ['user', 'manager'])) {
            $unitKerjaIds = $authUser->unitKerja()->pluck('unit_kerja.id')->toArray();
            $unitKerjaNames = $authUser->unitKerja()->pluck('nama_unit')->toArray();

            $query->where(function ($q) use ($authUser, $unitKerjaIds, $unitKerjaNames) {
                $q->where('user_id', $authUser->id);

                $q->orWhere('penanggung_jawab', $authUser->id);

                if (!empty($unitKerjaIds)) {
                    $q->orWhereHas('user.unitKerja', function ($uq) use ($unitKerjaIds) {
                        $uq->whereIn('unit_kerja.id', $unitKerjaIds);
                    });

                    $q->orWhereIn('unit_kerja_id', $unitKerjaIds);
                }

                if (!empty($unitKerjaNames)) {
                    $q->orWhereIn('unit_tujuan', $unitKerjaNames);
                }
            });
        }

        return $query;
    }
}
