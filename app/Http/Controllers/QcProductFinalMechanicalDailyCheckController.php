<?php

namespace App\Http\Controllers;

use App\Http\Requests\QcProductFinalMechanicalDailyCheckRequest;
use App\Models\Project;
use App\Models\QcProductFinalMechanicalDailyCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QcProductFinalMechanicalDailyCheckController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery(
            $request
        );

        $summaryQuery = clone $query;

        $summary = [
            'total_check' =>
                (clone $summaryQuery)->count(),

            'open' =>
                (clone $summaryQuery)
                    ->open()
                    ->count(),

            'close' =>
                (clone $summaryQuery)
                    ->closed()
                    ->count(),

            'pending' =>
                (clone $summaryQuery)
                    ->pending()
                    ->count(),

            'total_findings' =>
                (int) (
                    (clone $summaryQuery)
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
                ),

            'qty_ok' =>
                (int) (
                    (clone $summaryQuery)
                        ->sum('qty_ok')
                ),

            'qty_nok' =>
                (int) (
                    (clone $summaryQuery)
                        ->sum('qty_nok')
                ),
        ];

        $dailyChecks = $query
            ->with([
                'project',
                'creator',
            ])
            ->orderByDesc('reporting_year')
            ->orderByDesc('reporting_month')
            ->orderByDesc('check_date')
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
            QcProductFinalMechanicalDailyCheck::query()
                ->select('reporting_year')
                ->distinct()
                ->orderByDesc('reporting_year')
                ->pluck('reporting_year');

        $inspectors =
            QcProductFinalMechanicalDailyCheck::query()
                ->whereNotNull('inspector')
                ->where('inspector', '!=', '')
                ->distinct()
                ->orderBy('inspector')
                ->pluck('inspector');

        $inspectionGates =
            QcProductFinalMechanicalDailyCheck::query()
                ->whereNotNull('inspection_gate')
                ->where('inspection_gate', '!=', '')
                ->distinct()
                ->orderBy('inspection_gate')
                ->pluck('inspection_gate');

        $resultOptions =
            QcProductFinalMechanicalDailyCheck
                ::resultOptions();

        $monthOptions =
            QcProductFinalMechanicalDailyCheck
                ::monthOptions();

        return view(
            'monitoring-qc.product-final-mechanical.daily-check.index',
            compact(
                'dailyChecks',
                'summary',
                'projects',
                'years',
                'inspectors',
                'inspectionGates',
                'resultOptions',
                'monthOptions'
            )
        );
    }

    public function create(): View
    {
        return view(
            'monitoring-qc.product-final-mechanical.daily-check.create',
            $this->formOptions()
        );
    }

    public function store(
        QcProductFinalMechanicalDailyCheckRequest $request
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

        $dailyCheck =
            QcProductFinalMechanicalDailyCheck::query()
                ->create($payload);

        return redirect()
            ->route(
                'monitoring-qc.product-final-mechanical.daily-check.show',
                $dailyCheck
            )
            ->with(
                'success',
                'Daily Check QC Product & Final Mekanik berhasil ditambahkan.'
            );
    }

    public function show(
        QcProductFinalMechanicalDailyCheck $dailyCheck
    ): View {
        $dailyCheck->load([
            'project',
            'creator',
            'updater',
            'ncrDetails',
        ]);

        return view(
            'monitoring-qc.product-final-mechanical.daily-check.show',
            compact(
                'dailyCheck'
            )
        );
    }

    public function edit(
        QcProductFinalMechanicalDailyCheck $dailyCheck
    ): View {
        return view(
            'monitoring-qc.product-final-mechanical.daily-check.edit',
            array_merge(
                $this->formOptions(),
                [
                    'dailyCheck' =>
                        $dailyCheck,
                ]
            )
        );
    }

    public function update(
        QcProductFinalMechanicalDailyCheckRequest $request,
        QcProductFinalMechanicalDailyCheck $dailyCheck
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['updated_by'] =
            Auth::id();

        $dailyCheck->update(
            $payload
        );

        return redirect()
            ->route(
                'monitoring-qc.product-final-mechanical.daily-check.show',
                $dailyCheck
            )
            ->with(
                'success',
                'Daily Check QC Product & Final Mekanik berhasil diperbarui.'
            );
    }

    public function destroy(
        QcProductFinalMechanicalDailyCheck $dailyCheck
    ): RedirectResponse {
        $dailyCheck->delete();

        return redirect()
            ->route(
                'monitoring-qc.product-final-mechanical.daily-check.index'
            )
            ->with(
                'success',
                'Daily Check QC Product & Final Mekanik berhasil dihapus.'
            );
    }

    private function filteredQuery(
        Request $request
    ): Builder {
        $query =
            QcProductFinalMechanicalDailyCheck::query();

        if ($request->filled('search')) {
            $search =
                trim(
                    (string) $request->input('search')
                );

            $query->where(
                function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where(
                            'project_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'final_assembly_product_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'sub_part_assembly_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'oil_description',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'batch_reference',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'ncr_number',
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

        if ($request->filled('date_from')) {
            $query->whereDate(
                'check_date',
                '>=',
                $request->input('date_from')
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'check_date',
                '<=',
                $request->input('date_to')
            );
        }

        if ($request->filled('project_id')) {
            $query->where(
                'project_id',
                (int) $request->input('project_id')
            );
        }

        if ($request->filled('inspection_gate')) {
            $query->where(
                'inspection_gate',
                $request->input('inspection_gate')
            );
        }

        if ($request->filled('result')) {
            $query->where(
                'result',
                $request->input('result')
            );
        }

        if ($request->filled('inspector')) {
            $query->where(
                'inspector',
                $request->input('inspector')
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

        foreach (
            $this->quantityFields()
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
                'document_check',
                'inspection_gate',
                'sub_part_assembly_name',
                'oil_description',
                'batch_reference',
                'remarks',
                'ncr_number',
                'inspector',
                'status_is',
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
            empty(
                $validated['closing_oil_date']
            )
        ) {
            $validated['closing_oil_date'] =
                null;
        }

        if (
            empty(
                $validated['cycle_time_minutes']
            )
            && $validated['cycle_time_minutes'] !== 0
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

            'resultOptions' =>
                QcProductFinalMechanicalDailyCheck
                    ::resultOptions(),

            'documentCheckOptions' =>
                QcProductFinalMechanicalDailyCheck
                    ::documentCheckOptions(),

            'inspectionGateOptions' =>
                QcProductFinalMechanicalDailyCheck
                    ::inspectionGateOptions(),

            'statusIsOptions' =>
                QcProductFinalMechanicalDailyCheck
                    ::statusIsOptions(),

            'monthOptions' =>
                QcProductFinalMechanicalDailyCheck
                    ::monthOptions(),

            'findingLabels' =>
                QcProductFinalMechanicalDailyCheck
                    ::findingLabels(),
        ];
    }

    private function quantityFields(): array
    {
        return [
            'vt_qty',
            'dm_qty',
            'wg_qty',
            'pt_qty',
            'cp_qty',
            'ft_qty',
            'qty_ok',
            'qty_nok',
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
