<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\QcProductElectricalDailyCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class QcProductElectricalDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $availableYears = QcProductElectricalDailyCheck::query()
            ->selectRaw('YEAR(check_date) AS year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year);

        $selectedYear = $request->filled('year')
            ? (int) $request->input('year')
            : ($availableYears->first() ?? now()->year);

        $baseQuery = $this->dashboardQuery(
            $request,
            $selectedYear
        );

        /*
         |--------------------------------------------------------------------------
         | Summary
         |--------------------------------------------------------------------------
         */

        $summary = [
            'total_check' => (clone $baseQuery)->count(),

            'open' => (clone $baseQuery)
                ->where(
                    'result',
                    QcProductElectricalDailyCheck::RESULT_NOK
                )
                ->count(),

            'close' => (clone $baseQuery)
                ->where(
                    'result',
                    QcProductElectricalDailyCheck::RESULT_OK
                )
                ->count(),

            'pending' => (clone $baseQuery)
                ->where(
                    'result',
                    QcProductElectricalDailyCheck::RESULT_PENDING
                )
                ->count(),

            'total_findings' => $this->totalFindings(
                clone $baseQuery
            ),

            /*
             * Mengikuti dashboard Looker:
             * grafik OK/NOK menggunakan kuantitas kabel.
             */
            'total_ok' => (int) (
                (clone $baseQuery)->sum('cable_ok_qty')
            ),

            'total_nok' => (int) (
                (clone $baseQuery)->sum('cable_nok_qty')
            ),

            'total_ncr' => (clone $baseQuery)
                ->whereNotNull('ncr_number')
                ->where('ncr_number', '!=', '')
                ->count(),
        ];

        $summary['close_percentage'] =
            ($summary['open'] + $summary['close']) > 0
                ? round(
                    (
                        $summary['close']
                        / ($summary['open'] + $summary['close'])
                    ) * 100,
                    2
                )
                : 0;

        /*
         |--------------------------------------------------------------------------
         | Grafik status temuan
         |--------------------------------------------------------------------------
         */

        $statusRows = (clone $baseQuery)
            ->selectRaw('
                MONTH(check_date) AS month_number,
                COUNT(*) AS total_check,
                SUM(
                    CASE
                        WHEN result = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS open_count,
                SUM(
                    CASE
                        WHEN result = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS close_count,
                SUM(
                    CASE
                        WHEN result = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS pending_count
            ', [
                QcProductElectricalDailyCheck::RESULT_NOK,
                QcProductElectricalDailyCheck::RESULT_OK,
                QcProductElectricalDailyCheck::RESULT_PENDING,
            ])
            ->groupByRaw('MONTH(check_date)')
            ->orderByRaw('MONTH(check_date)')
            ->get()
            ->keyBy(fn ($row) => (int) $row->month_number);

        /*
         |--------------------------------------------------------------------------
         | Grafik OK dan NOK
         |--------------------------------------------------------------------------
         */

        $okNokRows = (clone $baseQuery)
            ->selectRaw('
                MONTH(check_date) AS month_number,
                COALESCE(SUM(cable_ok_qty), 0) AS ok_total,
                COALESCE(SUM(cable_nok_qty), 0) AS nok_total
            ')
            ->groupByRaw('MONTH(check_date)')
            ->orderByRaw('MONTH(check_date)')
            ->get()
            ->keyBy(fn ($row) => (int) $row->month_number);

        /*
         |--------------------------------------------------------------------------
         | Grafik lokasi temuan
         |--------------------------------------------------------------------------
         |
         | Menghitung jumlah Daily Check per Inspection Gate.
         |
         */

        $locationRows = (clone $baseQuery)
            ->selectRaw('
                MONTH(check_date) AS month_number,
                inspection_gate,
                COUNT(*) AS total
            ')
            ->whereNotNull('inspection_gate')
            ->groupByRaw(
                'MONTH(check_date), inspection_gate'
            )
            ->orderByRaw('MONTH(check_date)')
            ->get();

        $locationNames = $locationRows
            ->pluck('inspection_gate')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        /*
         |--------------------------------------------------------------------------
         | Grafik kategori temuan
         |--------------------------------------------------------------------------
         */

        $findingRows = (clone $baseQuery)
            ->selectRaw('
                MONTH(check_date) AS month_number,
                COALESCE(SUM(visual_qty), 0) AS visual,
                COALESCE(SUM(skun_qty), 0) AS skun,
                COALESCE(SUM(cramping_qty), 0) AS cramping,
                COALESCE(SUM(marking_qty), 0) AS marking,
                COALESCE(SUM(belltest_qty), 0) AS belltest,
                COALESCE(SUM(function_qty), 0) AS function_qty
            ')
            ->groupByRaw('MONTH(check_date)')
            ->orderByRaw('MONTH(check_date)')
            ->get()
            ->keyBy(fn ($row) => (int) $row->month_number);

        /*
         |--------------------------------------------------------------------------
         | Grafik NCR
         |--------------------------------------------------------------------------
         */

        $ncrRows = (clone $baseQuery)
            ->selectRaw('
                MONTH(check_date) AS month_number,
                ncr_category,
                COUNT(*) AS total
            ')
            ->whereNotNull('ncr_number')
            ->where('ncr_number', '!=', '')
            ->whereNotNull('ncr_category')
            ->groupByRaw(
                'MONTH(check_date), ncr_category'
            )
            ->orderByRaw('MONTH(check_date)')
            ->get();

        /*
         |--------------------------------------------------------------------------
         | Top 3 temuan
         |--------------------------------------------------------------------------
         */

        $topFindings = [
            'visual' => $this->topThreeByMonth(
                clone $baseQuery,
                'visual_qty'
            ),

            'skun' => $this->topThreeByMonth(
                clone $baseQuery,
                'skun_qty'
            ),

            'cramping' => $this->topThreeByMonth(
                clone $baseQuery,
                'cramping_qty'
            ),

            'marking' => $this->topThreeByMonth(
                clone $baseQuery,
                'marking_qty'
            ),

            'belltest' => $this->topThreeByMonth(
                clone $baseQuery,
                'belltest_qty'
            ),

            'function' => $this->topThreeByMonth(
                clone $baseQuery,
                'function_qty'
            ),
        ];

        /*
         |--------------------------------------------------------------------------
         | Susun data chart Januari - Desember
         |--------------------------------------------------------------------------
         */

        $months = $this->monthOptions();

        $monthNumbers = collect(array_keys($months));

        $chart = [
            'labels' => $monthNumbers
                ->map(fn ($month) => $months[$month])
                ->values(),

            'status' => [
                'open' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $statusRows[$month]->open_count
                                ?? 0
                            )
                    )
                    ->values(),

                'close' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $statusRows[$month]->close_count
                                ?? 0
                            )
                    )
                    ->values(),

                'total' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $statusRows[$month]->total_check
                                ?? 0
                            )
                    )
                    ->values(),
            ],

            'ok_nok' => [
                'ok' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $okNokRows[$month]->ok_total
                                ?? 0
                            )
                    )
                    ->values(),

                'nok' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $okNokRows[$month]->nok_total
                                ?? 0
                            )
                    )
                    ->values(),
            ],

            'locations' => $locationNames
                ->mapWithKeys(function ($location) use (
                    $locationRows,
                    $monthNumbers
                ) {
                    return [
                        $location => $monthNumbers
                            ->map(function ($month) use (
                                $locationRows,
                                $location
                            ) {
                                return (int) (
                                    $locationRows
                                        ->first(
                                            fn ($row) =>
                                                (int) $row->month_number ===
                                                    (int) $month
                                                && $row->inspection_gate ===
                                                    $location
                                        )
                                        ?->total
                                    ?? 0
                                );
                            })
                            ->values(),
                    ];
                }),

            'findings' => [
                'visual' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $findingRows[$month]->visual
                                ?? 0
                            )
                    )
                    ->values(),

                'skun' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $findingRows[$month]->skun
                                ?? 0
                            )
                    )
                    ->values(),

                'cramping' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $findingRows[$month]->cramping
                                ?? 0
                            )
                    )
                    ->values(),

                'marking' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $findingRows[$month]->marking
                                ?? 0
                            )
                    )
                    ->values(),

                'belltest' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $findingRows[$month]->belltest
                                ?? 0
                            )
                    )
                    ->values(),

                'function' => $monthNumbers
                    ->map(
                        fn ($month) =>
                            (int) (
                                $findingRows[$month]->function_qty
                                ?? 0
                            )
                    )
                    ->values(),
            ],

            'ncr' => [
                'visual' => $this->ncrSeries(
                    $ncrRows,
                    $monthNumbers,
                    QcProductElectricalDailyCheck::NCR_VISUAL
                ),

                'dimensi' => $this->ncrSeries(
                    $ncrRows,
                    $monthNumbers,
                    QcProductElectricalDailyCheck::NCR_DIMENSI
                ),

                'fungsi' => $this->ncrSeries(
                    $ncrRows,
                    $monthNumbers,
                    QcProductElectricalDailyCheck::NCR_FUNGSI
                ),
            ],
        ];

        /*
         |--------------------------------------------------------------------------
         | Filter options
         |--------------------------------------------------------------------------
         */

        $projects = Project::query()
            ->orderBy('nama_proyek')
            ->get();

        $inspectionGates =
            QcProductElectricalDailyCheck::query()
                ->whereNotNull('inspection_gate')
                ->distinct()
                ->orderBy('inspection_gate')
                ->pluck('inspection_gate');

        $checkCategories =
            QcProductElectricalDailyCheck::query()
                ->whereNotNull('check_category')
                ->distinct()
                ->orderBy('check_category')
                ->pluck('check_category');

        return view(
            'monitoring-qc.product-electrical.dashboard',
            compact(
                'availableYears',
                'selectedYear',
                'summary',
                'chart',
                'topFindings',
                'months',
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
        return QcProductElectricalDailyCheck::query()
            ->whereYear('check_date', $year)

            ->when(
                $request->filled('month_from'),
                fn (Builder $query) =>
                    $query->whereMonth(
                        'check_date',
                        '>=',
                        (int) $request->input('month_from')
                    )
            )

            ->when(
                $request->filled('month_to'),
                fn (Builder $query) =>
                    $query->whereMonth(
                        'check_date',
                        '<=',
                        (int) $request->input('month_to')
                    )
            )

            ->when(
                $request->filled('project_id'),
                fn (Builder $query) =>
                    $query->where(
                        'project_id',
                        $request->integer('project_id')
                    )
            )

            ->when(
                $request->filled('inspection_gate'),
                fn (Builder $query) =>
                    $query->where(
                        'inspection_gate',
                        $request->input('inspection_gate')
                    )
            )

            ->when(
                $request->filled('check_category'),
                fn (Builder $query) =>
                    $query->where(
                        'check_category',
                        $request->input('check_category')
                    )
            );
    }

    private function totalFindings(Builder $query): int
    {
        return (int) (
            $query
                ->selectRaw('
                    COALESCE(
                        SUM(
                            visual_qty
                            + skun_qty
                            + cramping_qty
                            + marking_qty
                            + belltest_qty
                            + function_qty
                        ),
                        0
                    ) AS total
                ')
                ->value('total')
            ?? 0
        );
    }

    private function topThreeByMonth(
        Builder $query,
        string $column
    ): Collection {
        $allowedColumns = [
            'visual_qty',
            'skun_qty',
            'cramping_qty',
            'marking_qty',
            'belltest_qty',
            'function_qty',
        ];

        abort_unless(
            in_array($column, $allowedColumns, true),
            500,
            'Kategori temuan tidak valid.'
        );

        return $query
            ->selectRaw("
                MONTH(check_date) AS month_number,
                product_name,
                SUM({$column}) AS total
            ")
            ->where($column, '>', 0)
            ->groupByRaw(
                'MONTH(check_date), product_name'
            )
            ->orderByRaw('MONTH(check_date)')
            ->orderByDesc('total')
            ->get()
            ->groupBy(
                fn ($row) => (int) $row->month_number
            )
            ->map(
                fn (Collection $rows) =>
                    $rows
                        ->sortByDesc('total')
                        ->take(3)
                        ->values()
            );
    }

    private function ncrSeries(
        Collection $rows,
        Collection $monthNumbers,
        string $category
    ): Collection {
        return $monthNumbers
            ->map(function ($month) use (
                $rows,
                $category
            ) {
                return (int) (
                    $rows
                        ->first(
                            fn ($row) =>
                                (int) $row->month_number ===
                                    (int) $month
                                && $row->ncr_category ===
                                    $category
                        )
                        ?->total
                    ?? 0
                );
            })
            ->values();
    }

    private function monthOptions(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }
}
