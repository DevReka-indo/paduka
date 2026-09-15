<?php

namespace App\Http\Controllers;

use App\Http\Requests\QcProductFinalMechanicalNcrRequest;
use App\Models\Project;
use App\Models\QcProductFinalMechanicalDailyCheck;
use App\Models\QcProductFinalMechanicalNcr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QcProductFinalMechanicalNcrController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery(
            $request
        );

        $summaryQuery = clone $query;

        $summary = [
            'total' =>
                (clone $summaryQuery)
                    ->distinct('ncr_number')
                    ->count('ncr_number'),

            'open' =>
                (clone $summaryQuery)
                    ->open()
                    ->distinct('ncr_number')
                    ->count('ncr_number'),

            'close' =>
                (clone $summaryQuery)
                    ->closed()
                    ->distinct('ncr_number')
                    ->count('ncr_number'),

            'pending' =>
                (clone $summaryQuery)
                    ->pending()
                    ->distinct('ncr_number')
                    ->count('ncr_number'),

            'total_findings' =>
                (int) (
                    (clone $summaryQuery)
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

        $ncrs = $query
            ->with([
                'project',
                'dailyCheck',
            ])
            ->orderByDesc('reporting_year')
            ->orderByDesc('reporting_month')
            ->orderByDesc('issued_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $projects = Project::query()
            ->orderBy('kode_proyek')
            ->orderBy('nama_proyek')
            ->get([
                'id',
                'kode_proyek',
                'nama_proyek',
            ]);

        $years =
            QcProductFinalMechanicalNcr::query()
                ->select('reporting_year')
                ->distinct()
                ->orderByDesc('reporting_year')
                ->pluck('reporting_year');

        $inspectors =
            QcProductFinalMechanicalNcr::query()
                ->whereNotNull('inspector')
                ->where('inspector', '!=', '')
                ->distinct()
                ->orderBy('inspector')
                ->pluck('inspector');

        $componentStatusOptions =
            QcProductFinalMechanicalNcr
                ::componentStatusOptions();

        $monthOptions =
            QcProductFinalMechanicalNcr
                ::monthOptions();

        return view(
            'monitoring-qc.product-final-mechanical.ncr.index',
            compact(
                'ncrs',
                'summary',
                'projects',
                'years',
                'inspectors',
                'componentStatusOptions',
                'monthOptions'
            )
        );
    }

    public function create(
        Request $request
    ): View {
        $dailyCheck = null;

        if ($request->filled('daily_check_id')) {
            $dailyCheck =
                QcProductFinalMechanicalDailyCheck::query()
                    ->find(
                        $request->integer(
                            'daily_check_id'
                        )
                    );
        }

        return view(
            'monitoring-qc.product-final-mechanical.ncr.create',
            array_merge(
                $this->formOptions(),
                [
                    'selectedDailyCheck' =>
                        $dailyCheck,
                ]
            )
        );
    }

    public function store(
        QcProductFinalMechanicalNcrRequest $request
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['source'] =
            'paduka';

        $payload['created_by'] =
            Auth::id();

        $payload['updated_by'] =
            Auth::id();

        $ncr =
            QcProductFinalMechanicalNcr::query()
                ->create($payload);

        return redirect()
            ->route(
                'monitoring-qc.product-final-mechanical.ncr.show',
                $ncr
            )
            ->with(
                'success',
                'Detail NCR Mekanik berhasil ditambahkan.'
            );
    }

    public function show(
        QcProductFinalMechanicalNcr $ncr
    ): View {
        $ncr->load([
            'project',
            'dailyCheck',
            'creator',
            'updater',
        ]);

        return view(
            'monitoring-qc.product-final-mechanical.ncr.show',
            compact(
                'ncr'
            )
        );
    }

    public function edit(
        QcProductFinalMechanicalNcr $ncr
    ): View {
        return view(
            'monitoring-qc.product-final-mechanical.ncr.edit',
            array_merge(
                $this->formOptions(),
                [
                    'ncr' =>
                        $ncr,

                    'selectedDailyCheck' =>
                        $ncr->dailyCheck,
                ]
            )
        );
    }

    public function update(
        QcProductFinalMechanicalNcrRequest $request,
        QcProductFinalMechanicalNcr $ncr
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['updated_by'] =
            Auth::id();

        $ncr->update(
            $payload
        );

        return redirect()
            ->route(
                'monitoring-qc.product-final-mechanical.ncr.show',
                $ncr
            )
            ->with(
                'success',
                'Detail NCR Mekanik berhasil diperbarui.'
            );
    }

    public function destroy(
        QcProductFinalMechanicalNcr $ncr
    ): RedirectResponse {
        $ncr->delete();

        return redirect()
            ->route(
                'monitoring-qc.product-final-mechanical.ncr.index'
            )
            ->with(
                'success',
                'Detail NCR Mekanik berhasil dihapus.'
            );
    }

    private function filteredQuery(
        Request $request
    ): Builder {
        $query =
            QcProductFinalMechanicalNcr::query();

        if ($request->filled('search')) {
            $search =
                trim(
                    (string) $request->input('search')
                );

            $query->where(
                function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where(
                            'ncr_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'project_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'product_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'nonconformity_location',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'target_unit',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'nonconformity_description',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'inspector',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }

        if ($request->filled('year')) {
            $query->where(
                'reporting_year',
                (int) $request->input('year')
            );
        }

        if ($request->filled('month')) {
            $query->where(
                'reporting_month',
                (int) $request->input('month')
            );
        }

        if ($request->filled('project_id')) {
            $query->where(
                'project_id',
                (int) $request->input('project_id')
            );
        }

        if ($request->filled('component_status')) {
            $query->where(
                'component_status',
                $request->input(
                    'component_status'
                )
            );
        }

        if ($request->filled('inspector')) {
            $query->where(
                'inspector',
                $request->input('inspector')
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'issued_date',
                '>=',
                $request->input('date_from')
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'issued_date',
                '<=',
                $request->input('date_to')
            );
        }

        return $query;
    }

    private function preparePayload(
        array $validated
    ): array {
        if (
            ! empty(
                $validated['project_id']
            )
        ) {
            $project = Project::query()
                ->findOrFail(
                    $validated['project_id']
                );

            $validated['project_name'] =
                $project->nama_proyek;
        } else {
            $validated['project_id'] =
                null;

            $validated['project_name'] =
                $this->nullableString(
                    $validated['project_name']
                    ?? null
                );
        }

        if (
            ! empty(
                $validated['daily_check_id']
            )
        ) {
            QcProductFinalMechanicalDailyCheck::query()
                ->findOrFail(
                    $validated['daily_check_id']
                );
        } else {
            $validated['daily_check_id'] =
                null;
        }

        foreach (
            [
                'visual_qty',
                'dimension_qty',
                'function_qty',
            ]
            as $field
        ) {
            $validated[$field] =
                (int) (
                    $validated[$field]
                    ?? 0
                );
        }

        foreach (
            [
                'project_name',
                'nonconformity_location',
                'target_unit',
                'inspector',
            ]
            as $field
        ) {
            $validated[$field] =
                $this->nullableString(
                    $validated[$field]
                    ?? null
                );
        }

        if (
            ! isset(
                $validated['cycle_time_minutes']
            )
            || $validated['cycle_time_minutes'] === ''
        ) {
            $validated['cycle_time_minutes'] =
                null;
        }

        return $validated;
    }

    private function formOptions(): array
    {
        return [
            'projects' =>
                Project::query()
                    ->orderBy('kode_proyek')
                    ->orderBy('nama_proyek')
                    ->get([
                        'id',
                        'kode_proyek',
                        'nama_proyek',
                    ]),

            'dailyChecks' =>
                QcProductFinalMechanicalDailyCheck::query()
                    ->orderByDesc('check_date')
                    ->orderByDesc('id')
                    ->limit(200)
                    ->get([
                        'id',
                        'check_date',
                        'project_name',
                        'final_assembly_product_name',
                        'ncr_number',
                    ]),

            'componentStatusOptions' =>
                QcProductFinalMechanicalNcr
                    ::componentStatusOptions(),

            'monthOptions' =>
                QcProductFinalMechanicalNcr
                    ::monthOptions(),

            'findingLabels' =>
                QcProductFinalMechanicalNcr
                    ::findingLabels(),
        ];
    }

    private function nullableString(
        mixed $value
    ): ?string {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $value =
            trim(
                (string) $value
            );

        return $value === ''
            || $value === '-'
                ? null
                : $value;
    }
}
