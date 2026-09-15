<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\QcProductFinalMechanicalDailyCheck;
use App\Models\QcProductFinalMechanicalNcr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class QcProductFinalMechanicalDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $availableYears = $this->availableYears();

        $selectedYear = (int) (
            $request->input('year')
            ?: $availableYears->first()
            ?: now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | Daily Check
        |--------------------------------------------------------------------------
        */

        $dailyQuery =
            QcProductFinalMechanicalDailyCheck::query()
                ->where(
                    'reporting_year',
                    $selectedYear
                );

        $this->applyMonthFilter(
            $dailyQuery,
            $request
        );

        if ($request->filled('project_id')) {
            $dailyQuery->where(
                'project_id',
                (int) $request->input('project_id')
            );
        }

        if ($request->filled('inspection_gate')) {
            $dailyQuery->where(
                'inspection_gate',
                $request->input('inspection_gate')
            );
        }

        $dailySummaryQuery =
            clone $dailyQuery;

        $totalCheck =
            (clone $dailySummaryQuery)
                ->count();

        $open =
            (clone $dailySummaryQuery)
                ->open()
                ->count();

        $close =
            (clone $dailySummaryQuery)
                ->closed()
                ->count();

        $pending =
            (clone $dailySummaryQuery)
                ->pending()
                ->count();

        $determined =
            $open + $close;

        $closePercentage =
            $determined > 0
                ? round(
                    ($close / $determined) * 100,
                    1
                )
                : 0;

        $totalFindings =
            (int) (
                (clone $dailySummaryQuery)
                    ->selectRaw(
                        '
                        COALESCE(
                            SUM(
                                vt_qty
                                + dm_qty
                                + wg_qty
                                + pt_qty
                                + cp_qty
                                + ft_qty
                            ),
                            0
                        ) as total
                        '
                    )
                    ->value('total')
                ?? 0
            );

        $qtyOk =
            (int) (
                (clone $dailySummaryQuery)
                    ->sum('qty_ok')
            );

        $qtyNok =
            (int) (
                (clone $dailySummaryQuery)
                    ->sum('qty_nok')
            );

        /*
        |--------------------------------------------------------------------------
        | Detail NCR
        |--------------------------------------------------------------------------
        |
        | Inspection Gate tidak diterapkan ke NCR karena tabel NCR
        | tidak memiliki field inspection_gate dan historical NCR
        | tidak selalu terhubung ke Daily Check.
        |
        */

        $ncrQuery =
            QcProductFinalMechanicalNcr::query()
                ->where(
                    'reporting_year',
                    $selectedYear
                );

        $this->applyMonthFilter(
            $ncrQuery,
            $request
        );

        if ($request->filled('project_id')) {
            $ncrQuery->where(
                'project_id',
                (int) $request->input('project_id')
            );
        }

        $ncrSummaryQuery =
            clone $ncrQuery;

        $ncrSummary = [
            'total' =>
                (clone $ncrSummaryQuery)
                    ->distinct()
                    ->count('ncr_number'),

            'open' =>
                (clone $ncrSummaryQuery)
                    ->open()
                    ->distinct()
                    ->count('ncr_number'),

            'close' =>
                (clone $ncrSummaryQuery)
                    ->closed()
                    ->distinct()
                    ->count('ncr_number'),

            'pending' =>
                (clone $ncrSummaryQuery)
                    ->pending()
                    ->distinct()
                    ->count('ncr_number'),

            'total_findings' =>
                (int) (
                    (clone $ncrSummaryQuery)
                        ->selectRaw(
                            '
                            COALESCE(
                                SUM(
                                    visual_qty
                                    + dimension_qty
                                    + function_qty
                                ),
                                0
                            ) as total
                            '
                        )
                        ->value('total')
                    ?? 0
                ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Main Summary
        |--------------------------------------------------------------------------
        */

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

            'qty_ok' =>
                $qtyOk,

            'qty_nok' =>
                $qtyNok,

            'total_ncr' =>
                $ncrSummary['total'],
        ];

        /*
        |--------------------------------------------------------------------------
        | Monthly Daily Check Status
        |--------------------------------------------------------------------------
        */

        $statusRows =
            (clone $dailyQuery)
                ->selectRaw(
                    '
                    reporting_month,
                    result,
                    COUNT(*) as total
                    '
                )
                ->groupBy(
                    'reporting_month',
                    'result'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Monthly Daily Findings
        |--------------------------------------------------------------------------
        */

        $findingRows =
            (clone $dailyQuery)
                ->selectRaw(
                    '
                    reporting_month,
                    SUM(vt_qty) as vt,
                    SUM(dm_qty) as dm,
                    SUM(wg_qty) as wg,
                    SUM(pt_qty) as pt,
                    SUM(cp_qty) as cp,
                    SUM(ft_qty) as ft
                    '
                )
                ->groupBy(
                    'reporting_month'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Inspection Gate Findings
        |--------------------------------------------------------------------------
        */

        $gateRows =
            (clone $dailyQuery)
                ->whereNotNull(
                    'inspection_gate'
                )
                ->where(
                    'inspection_gate',
                    '!=',
                    ''
                )
                ->selectRaw(
                    '
                    reporting_month,
                    inspection_gate,
                    SUM(
                        vt_qty
                        + dm_qty
                        + wg_qty
                        + pt_qty
                        + cp_qty
                        + ft_qty
                    ) as total_findings
                    '
                )
                ->groupBy(
                    'reporting_month',
                    'inspection_gate'
                )
                ->orderBy(
                    'inspection_gate'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Top Product Findings
        |--------------------------------------------------------------------------
        */

        $topFindings =
            (clone $dailyQuery)
                ->selectRaw(
                    '
                    final_assembly_product_name,
                    SUM(
                        vt_qty
                        + dm_qty
                        + wg_qty
                        + pt_qty
                        + cp_qty
                        + ft_qty
                    ) as total_findings
                    '
                )
                ->groupBy(
                    'final_assembly_product_name'
                )
                ->havingRaw(
                    '
                    SUM(
                        vt_qty
                        + dm_qty
                        + wg_qty
                        + pt_qty
                        + cp_qty
                        + ft_qty
                    ) > 0
                    '
                )
                ->orderByDesc(
                    'total_findings'
                )
                ->limit(5)
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Monthly NCR Status
        |--------------------------------------------------------------------------
        */

        $ncrStatusRows =
            (clone $ncrQuery)
                ->selectRaw(
                    '
                    reporting_month,
                    component_status,
                    COUNT(DISTINCT ncr_number) as total
                    '
                )
                ->groupBy(
                    'reporting_month',
                    'component_status'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Monthly NCR Findings
        |--------------------------------------------------------------------------
        */

        $ncrFindingRows =
            (clone $ncrQuery)
                ->selectRaw(
                    '
                    reporting_month,
                    SUM(visual_qty) as visual,
                    SUM(dimension_qty) as dimension,
                    SUM(function_qty) as function_qty
                    '
                )
                ->groupBy(
                    'reporting_month'
                )
                ->get();

        $chart = $this->buildChartData(
            $statusRows,
            $findingRows,
            $gateRows,
            $ncrStatusRows,
            $ncrFindingRows
        );

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $projects =
            Project::query()
                ->orderBy('kode_proyek')
                ->orderBy('nama_proyek')
                ->get([
                    'id',
                    'kode_proyek',
                    'nama_proyek',
                ]);

        $inspectionGates =
            QcProductFinalMechanicalDailyCheck::query()
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

        $monthOptions =
            QcProductFinalMechanicalDailyCheck
                ::monthOptions();

        return view(
            'monitoring-qc.product-final-mechanical.dashboard',
            compact(
                'summary',
                'ncrSummary',
                'chart',
                'topFindings',
                'availableYears',
                'selectedYear',
                'projects',
                'inspectionGates',
                'monthOptions'
            )
        );
    }

    private function applyMonthFilter(
        Builder $query,
        Request $request
    ): void {
        if ($request->filled('month_from')) {
            $query->where(
                'reporting_month',
                '>=',
                (int) $request->input(
                    'month_from'
                )
            );
        }

        if ($request->filled('month_to')) {
            $query->where(
                'reporting_month',
                '<=',
                (int) $request->input(
                    'month_to'
                )
            );
        }
    }

    private function availableYears(): Collection
    {
        $dailyYears =
            QcProductFinalMechanicalDailyCheck::query()
                ->select('reporting_year')
                ->distinct()
                ->pluck('reporting_year');

        $ncrYears =
            QcProductFinalMechanicalNcr::query()
                ->select('reporting_year')
                ->distinct()
                ->pluck('reporting_year');

        return $dailyYears
            ->merge($ncrYears)
            ->filter()
            ->map(
                fn ($year) =>
                    (int) $year
            )
            ->unique()
            ->sortDesc()
            ->values();
    }

    private function buildChartData(
        Collection $statusRows,
        Collection $findingRows,
        Collection $gateRows,
        Collection $ncrStatusRows,
        Collection $ncrFindingRows
    ): array {
        $labels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ];

        $status = [
            'open' =>
                array_fill(0, 12, 0),

            'close' =>
                array_fill(0, 12, 0),

            'pending' =>
                array_fill(0, 12, 0),

            'total' =>
                array_fill(0, 12, 0),
        ];

        foreach ($statusRows as $row) {
            $index =
                (int) $row->reporting_month - 1;

            if (
                $index < 0
                || $index > 11
            ) {
                continue;
            }

            $value =
                (int) $row->total;

            match ($row->result) {
                QcProductFinalMechanicalDailyCheck::RESULT_OK =>
                    $status['close'][$index] += $value,

                QcProductFinalMechanicalDailyCheck::RESULT_NOK =>
                    $status['open'][$index] += $value,

                default =>
                    $status['pending'][$index] += $value,
            };

            $status['total'][$index] +=
                $value;
        }

        $findings = [
            'vt' =>
                array_fill(0, 12, 0),

            'dm' =>
                array_fill(0, 12, 0),

            'wg' =>
                array_fill(0, 12, 0),

            'pt' =>
                array_fill(0, 12, 0),

            'cp' =>
                array_fill(0, 12, 0),

            'ft' =>
                array_fill(0, 12, 0),
        ];

        foreach ($findingRows as $row) {
            $index =
                (int) $row->reporting_month - 1;

            if (
                $index < 0
                || $index > 11
            ) {
                continue;
            }

            $findings['vt'][$index] =
                (int) $row->vt;

            $findings['dm'][$index] =
                (int) $row->dm;

            $findings['wg'][$index] =
                (int) $row->wg;

            $findings['pt'][$index] =
                (int) $row->pt;

            $findings['cp'][$index] =
                (int) $row->cp;

            $findings['ft'][$index] =
                (int) $row->ft;
        }

        $gateSeries = [];

        foreach ($gateRows as $row) {
            $gate =
                trim(
                    (string) $row->inspection_gate
                );

            if ($gate === '') {
                continue;
            }

            if (! isset($gateSeries[$gate])) {
                $gateSeries[$gate] = [
                    'label' =>
                        $gate,

                    'data' =>
                        array_fill(
                            0,
                            12,
                            0
                        ),
                ];
            }

            $index =
                (int) $row->reporting_month - 1;

            if (
                $index < 0
                || $index > 11
            ) {
                continue;
            }

            $gateSeries[$gate]['data'][$index] =
                (int) $row->total_findings;
        }

        $ncrStatus = [
            'open' =>
                array_fill(0, 12, 0),

            'close' =>
                array_fill(0, 12, 0),

            'pending' =>
                array_fill(0, 12, 0),

            'total' =>
                array_fill(0, 12, 0),
        ];

        foreach ($ncrStatusRows as $row) {
            $index =
                (int) $row->reporting_month - 1;

            if (
                $index < 0
                || $index > 11
            ) {
                continue;
            }

            $value =
                (int) $row->total;

            match ($row->component_status) {
                QcProductFinalMechanicalNcr::STATUS_OK =>
                    $ncrStatus['close'][$index] += $value,

                QcProductFinalMechanicalNcr::STATUS_NOK =>
                    $ncrStatus['open'][$index] += $value,

                default =>
                    $ncrStatus['pending'][$index] += $value,
            };

            $ncrStatus['total'][$index] +=
                $value;
        }

        $ncrFindings = [
            'visual' =>
                array_fill(0, 12, 0),

            'dimension' =>
                array_fill(0, 12, 0),

            'function' =>
                array_fill(0, 12, 0),
        ];

        foreach ($ncrFindingRows as $row) {
            $index =
                (int) $row->reporting_month - 1;

            if (
                $index < 0
                || $index > 11
            ) {
                continue;
            }

            $ncrFindings['visual'][$index] =
                (int) $row->visual;

            $ncrFindings['dimension'][$index] =
                (int) $row->dimension;

            $ncrFindings['function'][$index] =
                (int) $row->function_qty;
        }

        return [
            'labels' =>
                $labels,

            'status' =>
                $status,

            'findings' =>
                $findings,

            'inspection_gates' =>
                array_values(
                    $gateSeries
                ),

            'ncr_status' =>
                $ncrStatus,

            'ncr_findings' =>
                $ncrFindings,
        ];
    }
}
