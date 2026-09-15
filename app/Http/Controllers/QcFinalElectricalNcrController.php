<?php

namespace App\Http\Controllers;

use App\Http\Requests\QcFinalElectricalNcrRequest;
use App\Models\Project;
use App\Models\QcFinalElectricalDailyCheck;
use App\Models\QcFinalElectricalNcr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QcFinalElectricalNcrController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery(
            $request
        );

        $summaryQuery = clone $query;

        $summary = [
            'total_details' =>
                (clone $summaryQuery)->count(),

            'unique_ncr' =>
                (clone $summaryQuery)
                    ->distinct()
                    ->count('ncr_number'),

            'open' =>
                (clone $summaryQuery)
                    ->where(
                        'component_status',
                        QcFinalElectricalNcr::STATUS_NOK
                    )
                    ->count(),

            'closed' =>
                (clone $summaryQuery)
                    ->where(
                        'component_status',
                        QcFinalElectricalNcr::STATUS_OK
                    )
                    ->count(),

            'pending' =>
                (clone $summaryQuery)
                    ->where(
                        'component_status',
                        QcFinalElectricalNcr::STATUS_PENDING
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
                                    + specification_qty
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

        $ncrDetails = $query
            ->with([
                'project',
                'dailyCheck',
            ])
            ->latest('issued_date')
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

        $years = QcFinalElectricalNcr::query()
            ->selectRaw(
                'YEAR(issued_date) as year'
            )
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $inspectors =
            QcFinalElectricalNcr::query()
                ->whereNotNull('inspector')
                ->where('inspector', '!=', '')
                ->distinct()
                ->orderBy('inspector')
                ->pluck('inspector');

        return view(
            'monitoring-qc.final-electrical.ncr.index',
            compact(
                'ncrDetails',
                'summary',
                'projects',
                'years',
                'inspectors'
            )
        );
    }

    public function create(
        Request $request
    ): View {
        $dailyCheck = null;

        if ($request->filled('daily_check_id')) {
            $dailyCheck =
                QcFinalElectricalDailyCheck::query()
                    ->find(
                        $request->integer(
                            'daily_check_id'
                        )
                    );
        }

        return view(
            'monitoring-qc.final-electrical.ncr.create',
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
        QcFinalElectricalNcrRequest $request
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['source'] = 'paduka';
        $payload['created_by'] = Auth::id();
        $payload['updated_by'] = Auth::id();

        $ncrDetail =
            QcFinalElectricalNcr::query()
                ->create($payload);

        return redirect()
            ->route(
                'monitoring-qc.final-electrical.ncr.show',
                $ncrDetail
            )
            ->with(
                'success',
                'Detail NCR QC Final Elektrik berhasil ditambahkan.'
            );
    }

    public function show(
        QcFinalElectricalNcr $ncrDetail
    ): View {
        $ncrDetail->load([
            'project',
            'dailyCheck',
            'creator',
            'updater',
        ]);

        return view(
            'monitoring-qc.final-electrical.ncr.show',
            compact('ncrDetail')
        );
    }

    public function edit(
        QcFinalElectricalNcr $ncrDetail
    ): View {
        return view(
            'monitoring-qc.final-electrical.ncr.edit',
            array_merge(
                $this->formOptions(),
                [
                    'ncrDetail' =>
                        $ncrDetail,

                    'selectedDailyCheck' =>
                        $ncrDetail->dailyCheck,
                ]
            )
        );
    }

    public function update(
        QcFinalElectricalNcrRequest $request,
        QcFinalElectricalNcr $ncrDetail
    ): RedirectResponse {
        $payload = $this->preparePayload(
            $request->validated()
        );

        $payload['updated_by'] = Auth::id();

        $ncrDetail->update(
            $payload
        );

        return redirect()
            ->route(
                'monitoring-qc.final-electrical.ncr.show',
                $ncrDetail
            )
            ->with(
                'success',
                'Detail NCR QC Final Elektrik berhasil diperbarui.'
            );
    }

    public function destroy(
        QcFinalElectricalNcr $ncrDetail
    ): RedirectResponse {
        $ncrDetail->delete();

        return redirect()
            ->route(
                'monitoring-qc.final-electrical.ncr.index'
            )
            ->with(
                'success',
                'Detail NCR QC Final Elektrik berhasil dihapus.'
            );
    }

    private function filteredQuery(
        Request $request
    ): Builder {
        $query =
            QcFinalElectricalNcr::query();

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

        /*
        |--------------------------------------------------------------------------
        | Period
        |--------------------------------------------------------------------------
        */

        if ($request->filled('year')) {
            $query->whereYear(
                'issued_date',
                (int) $request->input('year')
            );
        }

        if ($request->filled('month')) {
            $query->whereMonth(
                'issued_date',
                (int) $request->input('month')
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

        /*
        |--------------------------------------------------------------------------
        | Other Filters
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
                'component_status'
            )
        ) {
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

        return $query;
    }

    private function preparePayload(
        array $validated
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Daily Check Reference
        |--------------------------------------------------------------------------
        */

        if (
            ! empty(
                $validated['daily_check_id']
            )
        ) {
            $dailyCheck =
                QcFinalElectricalDailyCheck::query()
                    ->find(
                        $validated[
                            'daily_check_id'
                        ]
                    );

            if ($dailyCheck) {
                /*
                 * Daily Check menjadi referensi utama
                 * apabila project belum dipilih manual.
                 */
                if (
                    empty(
                        $validated['project_id']
                    )
                ) {
                    $validated['project_id'] =
                        $dailyCheck->project_id;

                    $validated['project_name'] =
                        $dailyCheck->project_name;
                }

                /*
                 * Product hanya diisi dari Daily Check
                 * jika form tidak mengirim nilai.
                 */
                if (
                    empty(
                        $validated['product_name']
                    )
                ) {
                    $validated['product_name'] =
                        $dailyCheck->product_name;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Project Snapshot
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Finding Quantity
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Nullable Strings
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'ncr_number',
                'product_name',
                'nonconformity_location',
                'target_unit',
                'nonconformity_description',
                'inspector',
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
                    ->orderBy('kode_proyek')
                    ->orderBy('nama_proyek')
                    ->get([
                        'id',
                        'kode_proyek',
                        'nama_proyek',
                    ]),

            'dailyChecks' =>
                QcFinalElectricalDailyCheck::query()
                    ->orderByDesc('check_date')
                    ->orderByDesc('id')
                    ->get([
                        'id',
                        'check_date',
                        'project_name',
                        'product_name',
                        'ncr_number',
                    ]),

            'componentStatusOptions' =>
                QcFinalElectricalNcr
                    ::componentStatusOptions(),
        ];
    }

    private function quantityFields(): array
    {
        return [
            'visual_qty',
            'completeness_qty',
            'specification_qty',
            'dimension_qty',
            'function_qty',
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
