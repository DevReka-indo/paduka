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
            'tab' => ['nullable', 'in:program_kerja,kpi'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
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

        $inputPeriod = $selectedTab === WorkIndicator::TYPE_KPI
            ? WorkIndicator::PERIOD_QUARTERLY
            : WorkIndicator::PERIOD_MONTHLY;

        /*
         * Program Kerja menggunakan periode 1–12.
         * KPI menggunakan periode 1–4.
         */
        $periodLimit = $inputPeriod === WorkIndicator::PERIOD_QUARTERLY
            ? (int) ceil($selectedMonth / 3)
            : $selectedMonth;

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
         * Ambil capaian terakhir yang tersedia sampai bulan dipilih.
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
         * Indikator yang belum mempunyai data tidak dihitung
         * sebagai nol.
         */
        $averageAchievement = $indicators
            ->pluck('latest_percentage')
            ->filter(fn ($value) => $value !== null)
            ->avg();

        $averageAchievement = $averageAchievement !== null
            ? round((float) $averageAchievement, 2)
            : null;

        $availableYears = WorkAchievement::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->prepend(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        $monthOptions = collect(range(1, 12))
            ->mapWithKeys(fn (int $month) => [
                $month => Carbon::create()
                    ->month($month)
                    ->translatedFormat('F'),
            ]);

        $periodOptions = $this->periodOptions(
            $inputPeriod,
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
        string $inputPeriod,
        int $periodLimit
    ): array {
        if ($inputPeriod === WorkIndicator::PERIOD_QUARTERLY) {
            return collect([
                1 => 'Januari–Maret',
                2 => 'April–Juni',
                3 => 'Juli–September',
                4 => 'Oktober–Desember',
            ])
                ->take($periodLimit)
                ->all();
        }

        return collect(range(1, $periodLimit))
            ->mapWithKeys(fn (int $month) => [
                $month => Carbon::create()
                    ->month($month)
                    ->translatedFormat('F'),
            ])
            ->all();
    }
}
