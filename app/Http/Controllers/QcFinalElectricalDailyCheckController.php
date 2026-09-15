<?php

namespace App\Http\Controllers;

use App\Http\Requests\QcFinalElectricalDailyCheckRequest;
use App\Models\Project;
use App\Models\QcFinalElectricalDailyCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QcFinalElectricalDailyCheckController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery(
            $request
        );

        $summaryQuery = clone $query;

        $summary = [
            'total' =>
                (clone $summaryQuery)->count(),

            'open' =>
                (clone $summaryQuery)
                    ->where(
                        'result',
                        QcFinalElectricalDailyCheck::RESULT_NOK
                    )
                    ->count(),

            'closed' =>
                (clone $summaryQuery)
                    ->where(
                        'result',
                        QcFinalElectricalDailyCheck::RESULT_OK
                    )
                    ->count(),

            'pending' =>
                (clone $summaryQuery)
                    ->where(
                        'result',
                        QcFinalElectricalDailyCheck::RESULT_PENDING
                    )
                    ->count(),

            'total_findings' =>
                (int) (
                    (clone $summaryQuery)
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
                ),

            'total_ncr' =>
                (clone $summaryQuery)
                    ->whereNotNull('ncr_number')
                    ->where(
                        'ncr_number',
                        '!=',
                        ''
                    )
                    ->count(),
        ];

        $dailyChecks = $query
            ->with('project')
            ->latest('check_date')
            ->latest('id')
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
            QcFinalElectricalDailyCheck::query()
                ->selectRaw(
                    'YEAR(check_date) as year'
                )
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year');

        $gates =
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

        $categories =
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

        $inspectors =
            QcFinalElectricalDailyCheck::query()
                ->whereNotNull('inspector')
                ->where(
                    'inspector',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'inspector'
                )
                ->pluck(
                    'inspector'
                );

        return view(
            'monitoring-qc.final-electrical.daily-checks.index',
            compact(
                'dailyChecks',
                'summary',
                'projects',
                'years',
                'gates',
                'categories',
                'inspectors'
            )
        );
    }

    public function create(): View
    {
        return view(
            'monitoring-qc.final-electrical.daily-checks.create',
            $this->formOptions()
        );
    }

    public function store(
        QcFinalElectricalDailyCheckRequest $request
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['source'] = 'paduka';
        $payload['created_by'] = Auth::id();
        $payload['updated_by'] = Auth::id();

        $dailyCheck =
            QcFinalElectricalDailyCheck::query()
                ->create($payload);

        return redirect()
            ->route(
                'monitoring-qc.final-electrical.daily-check.show',
                $dailyCheck
            )
            ->with(
                'success',
                'Data QC Final Elektrik berhasil ditambahkan.'
            );
    }

    public function show(
        QcFinalElectricalDailyCheck $dailyCheck
    ): View {
        $dailyCheck->load([
            'project',
            'creator',
            'updater',
        ]);

        return view(
            'monitoring-qc.final-electrical.daily-checks.show',
            compact(
                'dailyCheck'
            )
        );
    }

    public function edit(
        QcFinalElectricalDailyCheck $dailyCheck
    ): View {
        return view(
            'monitoring-qc.final-electrical.daily-checks.edit',
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
        QcFinalElectricalDailyCheckRequest $request,
        QcFinalElectricalDailyCheck $dailyCheck
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['updated_by'] = Auth::id();

        $dailyCheck->update(
            $payload
        );

        return redirect()
            ->route(
                'monitoring-qc.final-electrical.daily-check.show',
                $dailyCheck
            )
            ->with(
                'success',
                'Data QC Final Elektrik berhasil diperbarui.'
            );
    }

    public function destroy(
        QcFinalElectricalDailyCheck $dailyCheck
    ): RedirectResponse {
        $dailyCheck->delete();

        return redirect()
            ->route(
                'monitoring-qc.final-electrical.daily-check.index'
            )
            ->with(
                'success',
                'Data QC Final Elektrik berhasil dihapus.'
            );
    }

    private function filteredQuery(
        Request $request
    ): Builder {
        $query =
            QcFinalElectricalDailyCheck::query();

        /*
         |--------------------------------------------------------------------------
         | Search
         |--------------------------------------------------------------------------
         */

        if ($request->filled('search')) {
            $search = trim(
                (string) $request->input(
                    'search'
                )
            );

            $query->where(
                function (Builder $query) use (
                    $search
                ) {
                    $query
                        ->where(
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
                            'serial_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'car_reference',
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

        /*
         |--------------------------------------------------------------------------
         | Date
         |--------------------------------------------------------------------------
         */

        if ($request->filled('date_from')) {
            $query->whereDate(
                'check_date',
                '>=',
                $request->input(
                    'date_from'
                )
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'check_date',
                '<=',
                $request->input(
                    'date_to'
                )
            );
        }

        if ($request->filled('year')) {
            $query->whereYear(
                'check_date',
                (int) $request->input(
                    'year'
                )
            );
        }

        if ($request->filled('month')) {
            $query->whereMonth(
                'check_date',
                (int) $request->input(
                    'month'
                )
            );
        }

        /*
         |--------------------------------------------------------------------------
         | Master Filters
         |--------------------------------------------------------------------------
         */

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

        if ($request->filled('result')) {
            $query->where(
                'result',
                $request->input(
                    'result'
                )
            );
        }

        if (
            $request->filled(
                'inspector'
            )
        ) {
            $query->where(
                'inspector',
                $request->input(
                    'inspector'
                )
            );
        }

        return $query;
    }

    private function preparePayload(
        array $validated
    ): array {
        /*
         * Snapshot project.
         */
        if (
            ! empty(
                $validated['project_id']
            )
        ) {
            $project = Project::query()
                ->find(
                    $validated['project_id']
                );

            if ($project) {
                $validated['project_name'] =
                    $project->nama_proyek;
            }
        }

        $validated['project_name'] =
            trim(
                (string) (
                    $validated['project_name']
                    ?? '-'
                )
            );

        /*
         * Integer quantities.
         */
        foreach (
            $this->quantityFields()
            as $field
        ) {
            $validated[$field] =
                max(
                    0,
                    (int) (
                        $validated[$field]
                        ?? 0
                    )
                );
        }

        /*
         * Trim basic string fields.
         */
        foreach (
            [
                'document_check',
                'inspection_gate',
                'product_name',
                'check_category',
                'serial_number',
                'car_reference',
                'batch_reference',
                'ncr_number',
                'inspector',
                'status_is',
                'oil_link',
            ] as $field
        ) {
            if (
                array_key_exists(
                    $field,
                    $validated
                )
            ) {
                $validated[$field] =
                    $this->nullableString(
                        $validated[$field]
                    );
            }
        }

        return $validated;
    }

    private function formOptions(): array
    {
        return [
            'projects' =>
                Project::query()
                    ->orderBy(
                        'kode_proyek'
                    )
                    ->orderBy(
                        'nama_proyek'
                    )
                    ->get([
                        'id',
                        'kode_proyek',
                        'nama_proyek',
                    ]),

            'documentCheckOptions' =>
                QcFinalElectricalDailyCheck
                    ::documentCheckOptions(),

            'inspectionGateOptions' =>
                QcFinalElectricalDailyCheck
                    ::inspectionGateOptions(),

            'checkCategoryOptions' =>
                QcFinalElectricalDailyCheck
                    ::checkCategoryOptions(),

            'resultOptions' =>
                QcFinalElectricalDailyCheck
                    ::resultOptions(),

            'statusIsOptions' =>
                QcFinalElectricalDailyCheck
                    ::statusIsOptions(),
        ];
    }

    private function quantityFields(): array
    {
        return [
            'visual_qty',
            'completeness_qty',
            'belltest_qty',
            'function_qty',
            'torque_qty',
            'oil_count',
        ];
    }

    private function nullableString(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim(
            (string) $value
        );

        return $value === ''
            ? null
            : $value;
    }
}
