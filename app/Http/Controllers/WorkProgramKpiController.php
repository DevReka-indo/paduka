<?php

namespace App\Http\Controllers;

use App\Models\WorkAchievement;
use App\Models\WorkIndicator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkProgramKpiController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'tab' => [
                'nullable',
                'in:program_kerja,kpi',
            ],
            'year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
            ],
            'month' => [
                'nullable',
                'integer',
                'min:1',
                'max:12',
            ],
        ]);

        $selectedTab = $validated['tab']
            ?? WorkIndicator::TYPE_PROGRAM_KERJA;

        $selectedYear = (int) (
            $validated['year']
            ?? now()->year
        );

        $selectedMonth = (int) (
            $validated['month']
            ?? now()->month
        );

        /*
         * Program Kerja dan KPI sama-sama
         * menggunakan periode bulanan.
         */
        $inputPeriod = WorkIndicator::PERIOD_MONTHLY;

        /*
         * period_number langsung mengikuti nomor bulan.
         *
         * Januari   = 1
         * Februari  = 2
         * ...
         * Desember  = 12
         */
        $periodLimit = $selectedMonth;

        $indicators = WorkIndicator::query()
            ->where('type', $selectedTab)
            ->where('input_period', $inputPeriod)
            ->active()
            ->ordered()
            ->with([
                'achievements' => function ($query) use (
                    $selectedYear,
                    $periodLimit
                ) {
                    $query
                        ->where('year', $selectedYear)
                        ->where(
                            'period_number',
                            '<=',
                            $periodLimit
                        )
                        ->orderBy('period_number');
                },
            ])
            ->get();

        /*
         * Ambil capaian terakhir yang tersedia
         * sampai bulan yang dipilih.
         */
        $indicators->each(function (WorkIndicator $indicator) {
            $latestAchievement = $indicator
                ->achievements
                ->sortByDesc('period_number')
                ->first();

            $indicator->setAttribute(
                'latest_achievement',
                $latestAchievement
            );

            $indicator->setAttribute(
                'latest_percentage',
                $latestAchievement
                    ? (float) $latestAchievement->percentage
                    : null
            );
        });

        /*
         * Indikator yang belum mempunyai data
         * tidak dihitung sebagai nol.
         */
        $averageAchievement = $indicators
            ->pluck('latest_percentage')
            ->filter(fn ($value) => $value !== null)
            ->avg();

        $averageAchievement = $averageAchievement !== null
            ? round((float) $averageAchievement, 2)
            : null;

        /*
         * Daftar tahun yang tersedia.
         */
        $availableYears = WorkAchievement::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->prepend(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        /*
         * Pilihan bulan Januari sampai Desember.
         */
        $monthOptions = collect(range(1, 12))
            ->mapWithKeys(fn (int $month) => [
                $month => Carbon::create()
                    ->month($month)
                    ->translatedFormat('F'),
            ]);

        /*
         * Kolom detail ditampilkan sampai
         * bulan yang dipilih.
         */
        $periodOptions = $this->periodOptions(
            $periodLimit
        );

        return view('work-program-kpi.index', compact(
            'selectedTab',
            'selectedYear',
            'selectedMonth',
            'inputPeriod',
            'periodLimit',
            'indicators',
            'averageAchievement',
            'availableYears',
            'monthOptions',
            'periodOptions'
        ));
    }

    private function periodOptions(
        int $periodLimit
    ): array {
        return collect(range(1, $periodLimit))
            ->mapWithKeys(fn (int $month) => [
                $month => Carbon::create()
                    ->month($month)
                    ->translatedFormat('F'),
            ])
            ->all();
    }
}
