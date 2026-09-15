<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\QcFinalElectricalDailyCheck;
use App\Models\QcFinalElectricalNcr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class QcFinalElectricalDashboardController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Available Years
        |--------------------------------------------------------------------------
        */

        $dailyYears = QcFinalElectricalDailyCheck::query()
            ->selectRaw('YEAR(check_date) as year')
            ->distinct()
            ->pluck('year');

        $ncrYears = QcFinalElectricalNcr::query()
            ->selectRaw('YEAR(issued_date) as year')
            ->distinct()
            ->pluck('year');

        $availableYears = $dailyYears
            ->merge($ncrYears)
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->sortDesc()
            ->values();

        $selectedYear = (int) (
            $request->input('year')
            ?: $availableYears->first()
            ?: now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | Daily Check Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = $this->dashboardQuery(
            $request,
            $selectedYear
        );

        /*
        |--------------------------------------------------------------------------
        | Daily Check Summary
        |--------------------------------------------------------------------------
        */

        $totalCheck = (clone $baseQuery)
            ->count();

        $open = (clone $baseQuery)
            ->where(
                'result',
                QcFinalElectricalDailyCheck::RESULT_NOK
            )
            ->count();

        $close = (clone $baseQuery)
            ->where(
                'result',
                QcFinalElectricalDailyCheck::RESULT_OK
            )
            ->count();

        $pending = (clone $baseQuery)
            ->where(
                'result',
                QcFinalElectricalDailyCheck::RESULT_PENDING
            )
            ->count();

        $determined = $open + $close;

        $closePercentage = $determined > 0
            ? round(
                ($close / $determined) * 100,
                2
            )
            : 0;

        $totalFindings = (int) (
            (clone $baseQuery)
                ->selectRaw(
                    '
                    COALESCE(
                        SUM(
                            visual_qty
                            + completeness_qty
                            + belltest_qty
                            + function_qty
                            + torque_qty
                        ),
                        0
                    ) as total
                    '
                )
                ->value('total')
            ?? 0
        );

        $totalOil = (int) (
            (clone $baseQuery)
                ->sum('oil_count')
        );

        /*
        |--------------------------------------------------------------------------
        | NCR Query
        |--------------------------------------------------------------------------
        |
        | NCR tidak mempunyai inspection_gate dan check_category.
        | Karena itu filter NCR mengikuti:
        |
        | - year
        | - month_from
        | - month_to
        | - project_id
        |
        */

        $ncrQuery = $this->ncrDashboardQuery(
            $request,
            $selectedYear
        );

        $ncrSummary = [
            'total' =>
                (clone $ncrQuery)
                    ->distinct()
                    ->count('ncr_number'),

            'open' =>
                (clone $ncrQuery)
                    ->where(
                        'component_status',
                        QcFinalElectricalNcr::STATUS_NOK
                    )
                    ->distinct()
                    ->count('ncr_number'),

            'closed' =>
                (clone $ncrQuery)
                    ->where(
                        'component_status',
                        QcFinalElectricalNcr::STATUS_OK
                    )
                    ->distinct()
                    ->count('ncr_number'),

            'pending' =>
                (clone $ncrQuery)
                    ->where(
                        'component_status',
                        QcFinalElectricalNcr::STATUS_PENDING
                    )
                    ->distinct()
                    ->count('ncr_number'),
        ];

        $summary = [
            'total_check' =>
                $totalCheck,

            'open' =>
                $open,

            'close' =>
                $close,

            'pending' =>
                $pending,

            'close_percentage' =>
                $closePercentage,

            'total_findings' =>
                $totalFindings,

            'total_oil' =>
                $totalOil,

            /*
             * Sekarang memakai tabel Detail NCR.
             */
            'total_ncr' =>
                $ncrSummary['total'],
        ];

        /*
        |--------------------------------------------------------------------------
        | Status Check per Month
        |--------------------------------------------------------------------------
        */

        $statusRows = (clone $baseQuery)
            ->selectRaw(
                '
                MONTH(check_date) as month,
                COUNT(*) as total_check,
                SUM(
                    CASE
                        WHEN result = ?
                        THEN 1
                        ELSE 0
                    END
                ) as open_count,
                SUM(
                    CASE
                        WHEN result = ?
                        THEN 1
                        ELSE 0
                    END
                ) as close_count,
                SUM(
                    CASE
                        WHEN result = ?
                        THEN 1
                        ELSE 0
                    END
                ) as pending_count
                ',
                [
                    QcFinalElectricalDailyCheck::RESULT_NOK,
                    QcFinalElectricalDailyCheck::RESULT_OK,
                    QcFinalElectricalDailyCheck::RESULT_PENDING,
                ]
            )
            ->groupByRaw(
                'MONTH(check_date)'
            )
            ->orderByRaw(
                'MONTH(check_date)'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Daily Finding per Month
        |--------------------------------------------------------------------------
        */

        $findingRows = (clone $baseQuery)
            ->selectRaw(
                '
                MONTH(check_date) as month,
                COALESCE(SUM(visual_qty), 0) as visual,
                COALESCE(SUM(completeness_qty), 0) as completeness,
                COALESCE(SUM(belltest_qty), 0) as belltest,
                COALESCE(SUM(function_qty), 0) as function_qty,
                COALESCE(SUM(torque_qty), 0) as torque
                '
            )
            ->groupByRaw(
                'MONTH(check_date)'
            )
            ->orderByRaw(
                'MONTH(check_date)'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Location Finding per Month
        |--------------------------------------------------------------------------
        */

        $locationRows = (clone $baseQuery)
            ->selectRaw(
                '
                MONTH(check_date) as month,
                inspection_gate,
                COALESCE(
                    SUM(
                        visual_qty
                        + completeness_qty
                        + belltest_qty
                        + function_qty
                        + torque_qty
                    ),
                    0
                ) as total
                '
            )
            ->groupByRaw(
                '
                MONTH(check_date),
                inspection_gate
                '
            )
            ->orderByRaw(
                'MONTH(check_date)'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Top 3 Findings
        |--------------------------------------------------------------------------
        */

        $topFindings = [
            'visual' =>
                $this->topThreeByMonth(
                    $baseQuery,
                    'visual_qty'
                ),

            'completeness' =>
                $this->topThreeByMonth(
                    $baseQuery,
                    'completeness_qty'
                ),

            'belltest' =>
                $this->topThreeByMonth(
                    $baseQuery,
                    'belltest_qty'
                ),

            'function' =>
                $this->topThreeByMonth(
                    $baseQuery,
                    'function_qty'
                ),

            'torque' =>
                $this->topThreeByMonth(
                    $baseQuery,
                    'torque_qty'
                ),
        ];

        /*
        |--------------------------------------------------------------------------
        | NCR Status per Month
        |--------------------------------------------------------------------------
        |
        | COUNT DISTINCT dipakai agar satu NCR yang mempunyai beberapa
        | detail tidak dihitung berulang.
        |
        */

        $ncrStatusRows = (clone $ncrQuery)
            ->selectRaw(
                '
                MONTH(issued_date) as month,

                COUNT(
                    DISTINCT CASE
                        WHEN component_status = ?
                        THEN ncr_number
                    END
                ) as open_count,

                COUNT(
                    DISTINCT CASE
                        WHEN component_status = ?
                        THEN ncr_number
                    END
                ) as close_count,

                COUNT(
                    DISTINCT CASE
                        WHEN component_status = ?
                        THEN ncr_number
                    END
                ) as pending_count,

                COUNT(
                    DISTINCT ncr_number
                ) as total_count
                ',
                [
                    QcFinalElectricalNcr::STATUS_NOK,
                    QcFinalElectricalNcr::STATUS_OK,
                    QcFinalElectricalNcr::STATUS_PENDING,
                ]
            )
            ->groupByRaw(
                'MONTH(issued_date)'
            )
            ->orderByRaw(
                'MONTH(issued_date)'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NCR Category per Month
        |--------------------------------------------------------------------------
        */

        $ncrFindingRows = (clone $ncrQuery)
            ->selectRaw(
                '
                MONTH(issued_date) as month,

                COALESCE(
                    SUM(visual_qty),
                    0
                ) as visual,

                COALESCE(
                    SUM(dimension_qty),
                    0
                ) as dimension_qty,

                COALESCE(
                    SUM(completeness_qty),
                    0
                ) as completeness,

                COALESCE(
                    SUM(specification_qty),
                    0
                ) as specification,

                COALESCE(
                    SUM(function_qty),
                    0
                ) as function_qty
                '
            )
            ->groupByRaw(
                'MONTH(issued_date)'
            )
            ->orderByRaw(
                'MONTH(issued_date)'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Chart Data
        |--------------------------------------------------------------------------
        */

        $months = collect(
            range(1, 12)
        );

        $monthLabels = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        $labels = $months
            ->map(
                fn (int $month) =>
                    $monthLabels[$month]
            )
            ->values();

        $statusByMonth =
            $statusRows->keyBy('month');

        $findingByMonth =
            $findingRows->keyBy('month');

        $ncrStatusByMonth =
            $ncrStatusRows->keyBy('month');

        $ncrFindingByMonth =
            $ncrFindingRows->keyBy('month');

        /*
         * Canonical PADUKA.
         */
        $locationNames = [
            'Unit Area 1',
            'Unit Area 2',
            'Unit Area 3',
            'WS. INKA',
            'WS. INKA (BWI)',
            'WS. Tiron',
        ];

        $locationDatasets = collect(
            $locationNames
        )
            ->map(
                function (
                    string $location
                ) use (
                    $months,
                    $locationRows
                ) {
                    return [
                        'label' =>
                            $location,

                        'data' =>
                            $months
                                ->map(
                                    function (
                                        int $month
                                    ) use (
                                        $locationRows,
                                        $location
                                    ) {
                                        $row =
                                            $locationRows
                                                ->first(
                                                    fn ($item) =>
                                                        (int) $item->month
                                                            === $month
                                                        && $item->inspection_gate
                                                            === $location
                                                );

                                        return (int) (
                                            $row?->total
                                            ?? 0
                                        );
                                    }
                                )
                                ->values(),
                    ];
                }
            )
            ->values();

        $chart = [
            'labels' =>
                $labels,

            'status' => [
                'open' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $statusByMonth[
                                        $month
                                    ]?->open_count
                                    ?? 0
                                )
                        )
                        ->values(),

                'close' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $statusByMonth[
                                        $month
                                    ]?->close_count
                                    ?? 0
                                )
                        )
                        ->values(),

                'pending' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $statusByMonth[
                                        $month
                                    ]?->pending_count
                                    ?? 0
                                )
                        )
                        ->values(),

                'total' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $statusByMonth[
                                        $month
                                    ]?->total_check
                                    ?? 0
                                )
                        )
                        ->values(),
            ],

            'findings' => [
                'visual' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $findingByMonth[
                                        $month
                                    ]?->visual
                                    ?? 0
                                )
                        )
                        ->values(),

                'completeness' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $findingByMonth[
                                        $month
                                    ]?->completeness
                                    ?? 0
                                )
                        )
                        ->values(),

                'belltest' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $findingByMonth[
                                        $month
                                    ]?->belltest
                                    ?? 0
                                )
                        )
                        ->values(),

                'function' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $findingByMonth[
                                        $month
                                    ]?->function_qty
                                    ?? 0
                                )
                        )
                        ->values(),

                'torque' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $findingByMonth[
                                        $month
                                    ]?->torque
                                    ?? 0
                                )
                        )
                        ->values(),
            ],

            'locations' =>
                $locationDatasets,

            /*
             * Data NCR.
             */
            'ncr_status' => [
                'open' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrStatusByMonth[
                                        $month
                                    ]?->open_count
                                    ?? 0
                                )
                        )
                        ->values(),

                'close' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrStatusByMonth[
                                        $month
                                    ]?->close_count
                                    ?? 0
                                )
                        )
                        ->values(),

                'pending' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrStatusByMonth[
                                        $month
                                    ]?->pending_count
                                    ?? 0
                                )
                        )
                        ->values(),

                'total' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrStatusByMonth[
                                        $month
                                    ]?->total_count
                                    ?? 0
                                )
                        )
                        ->values(),
            ],

            'ncr_findings' => [
                'visual' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrFindingByMonth[
                                        $month
                                    ]?->visual
                                    ?? 0
                                )
                        )
                        ->values(),

                'dimension' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrFindingByMonth[
                                        $month
                                    ]?->dimension_qty
                                    ?? 0
                                )
                        )
                        ->values(),

                'completeness' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrFindingByMonth[
                                        $month
                                    ]?->completeness
                                    ?? 0
                                )
                        )
                        ->values(),

                'specification' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrFindingByMonth[
                                        $month
                                    ]?->specification
                                    ?? 0
                                )
                        )
                        ->values(),

                'function' =>
                    $months
                        ->map(
                            fn (int $month) =>
                                (int) (
                                    $ncrFindingByMonth[
                                        $month
                                    ]?->function_qty
                                    ?? 0
                                )
                        )
                        ->values(),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $projects = Project::query()
            ->orderBy('kode_proyek')
            ->orderBy('nama_proyek')
            ->get([
                'id',
                'kode_proyek',
                'nama_proyek',
            ]);

        $inspectionGates =
            QcFinalElectricalDailyCheck::query()
                ->whereNotNull(
                    'inspection_gate'
                )
                ->where(
                    'inspection_gate',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'inspection_gate'
                )
                ->pluck(
                    'inspection_gate'
                );

        $checkCategories =
            QcFinalElectricalDailyCheck::query()
                ->whereNotNull(
                    'check_category'
                )
                ->where(
                    'check_category',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'check_category'
                )
                ->pluck(
                    'check_category'
                );

        return view(
            'monitoring-qc.final-electrical.dashboard',
            compact(
                'summary',
                'ncrSummary',
                'chart',
                'topFindings',
                'availableYears',
                'selectedYear',
                'projects',
                'inspectionGates',
                'checkCategories'
            )
        );
    }

    private function dashboardQuery(
        Request $request,
        int $year
    ): Builder {
        $query =
            QcFinalElectricalDailyCheck::query()
                ->whereYear(
                    'check_date',
                    $year
                );

        if ($request->filled('month_from')) {
            $query->whereMonth(
                'check_date',
                '>=',
                (int) $request->input(
                    'month_from'
                )
            );
        }

        if ($request->filled('month_to')) {
            $query->whereMonth(
                'check_date',
                '<=',
                (int) $request->input(
                    'month_to'
                )
            );
        }

        if ($request->filled('project_id')) {
            $query->where(
                'project_id',
                (int) $request->input(
                    'project_id'
                )
            );
        }

        if (
            $request->filled(
                'inspection_gate'
            )
        ) {
            $query->where(
                'inspection_gate',
                $request->input(
                    'inspection_gate'
                )
            );
        }

        if (
            $request->filled(
                'check_category'
            )
        ) {
            $query->where(
                'check_category',
                $request->input(
                    'check_category'
                )
            );
        }

        return $query;
    }

    private function ncrDashboardQuery(
        Request $request,
        int $year
    ): Builder {
        $query =
            QcFinalElectricalNcr::query()
                ->whereYear(
                    'issued_date',
                    $year
                );

        if ($request->filled('month_from')) {
            $query->whereMonth(
                'issued_date',
                '>=',
                (int) $request->input(
                    'month_from'
                )
            );
        }

        if ($request->filled('month_to')) {
            $query->whereMonth(
                'issued_date',
                '<=',
                (int) $request->input(
                    'month_to'
                )
            );
        }

        if ($request->filled('project_id')) {
            $query->where(
                'project_id',
                (int) $request->input(
                    'project_id'
                )
            );
        }

        return $query;
    }

    private function topThreeByMonth(
        Builder $baseQuery,
        string $column
    ): Collection {
        $rows = (clone $baseQuery)
            ->selectRaw(
                "
                MONTH(check_date) as month,
                product_name,
                COALESCE(
                    SUM({$column}),
                    0
                ) as total
                "
            )
            ->groupByRaw(
                '
                MONTH(check_date),
                product_name
                '
            )
            ->havingRaw(
                "SUM({$column}) > 0"
            )
            ->orderByRaw(
                'MONTH(check_date)'
            )
            ->orderByDesc(
                'total'
            )
            ->get();

        return collect(
            range(1, 12)
        )->mapWithKeys(
            function (
                int $month
            ) use ($rows) {
                return [
                    $month =>
                        $rows
                            ->where(
                                'month',
                                $month
                            )
                            ->sortByDesc(
                                'total'
                            )
                            ->take(3)
                            ->values(),
                ];
            }
        );
    }
}
