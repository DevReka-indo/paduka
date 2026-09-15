<?php

namespace App\Http\Controllers;

use App\Http\Requests\QcProductElectricalDailyCheckRequest;
use App\Models\Project;
use App\Models\QcProductElectricalDailyCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QcProductElectricalDailyCheckController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request);

        $summaryQuery = clone $query;

        $summary = [
            'total' => (clone $summaryQuery)->count(),

            'open' => (clone $summaryQuery)
                ->open()
                ->count(),

            'closed' => (clone $summaryQuery)
                ->closed()
                ->count(),

            'pending' => (clone $summaryQuery)
                ->where(
                    'result',
                    QcProductElectricalDailyCheck::RESULT_PENDING
                )
                ->count(),

            'total_findings' => (int) (
                (clone $summaryQuery)
                    ->selectRaw(
                        'COALESCE(SUM(
                            visual_qty +
                            skun_qty +
                            cramping_qty +
                            marking_qty +
                            belltest_qty +
                            function_qty
                        ), 0) AS total_findings'
                    )
                    ->value('total_findings') ?? 0
            ),
        ];

        $dailyChecks = $query
            ->latest('check_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $projects = Project::query()
            ->orderBy('nama_proyek')
            ->get();

        $years = QcProductElectricalDailyCheck::query()
            ->selectRaw('YEAR(check_date) AS year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $inspectionGates = QcProductElectricalDailyCheck::query()
            ->whereNotNull('inspection_gate')
            ->distinct()
            ->orderBy('inspection_gate')
            ->pluck('inspection_gate');

        $checkCategories = QcProductElectricalDailyCheck::query()
            ->whereNotNull('check_category')
            ->distinct()
            ->orderBy('check_category')
            ->pluck('check_category');

        $inspectors = QcProductElectricalDailyCheck::query()
            ->whereNotNull('inspector')
            ->distinct()
            ->orderBy('inspector')
            ->pluck('inspector');

        return view(
            'monitoring-qc.product-electrical.daily-checks.index',
            compact(
                'dailyChecks',
                'summary',
                'projects',
                'years',
                'inspectionGates',
                'checkCategories',
                'inspectors'
            )
        );
    }

    public function create(): View
    {
        return view(
            'monitoring-qc.product-electrical.daily-checks.create',
            $this->formOptions()
        );
    }

    public function store(
        QcProductElectricalDailyCheckRequest $request
    ): RedirectResponse {
        $payload = $this->preparePayload($request);

        $payload['source'] = 'paduka';
        $payload['created_by'] = Auth::id();
        $payload['updated_by'] = Auth::id();

        $dailyCheck = QcProductElectricalDailyCheck::query()
            ->create($payload);

        return redirect()
            ->route(
                'monitoring-qc.product-electrical.daily-check.show',
                $dailyCheck
            )
            ->with(
                'success',
                'Data Daily Check QC Product Elektrik berhasil ditambahkan.'
            );
    }

    public function show(
        QcProductElectricalDailyCheck $dailyCheck
    ): View {
        $dailyCheck->load([
            'project',
            'creator',
            'updater',
        ]);

        return view(
            'monitoring-qc.product-electrical.daily-checks.show',
            compact('dailyCheck')
        );
    }

    public function edit(
        QcProductElectricalDailyCheck $dailyCheck
    ): View {
        return view(
            'monitoring-qc.product-electrical.daily-checks.edit',
            array_merge(
                $this->formOptions(),
                compact('dailyCheck')
            )
        );
    }

    public function update(
        QcProductElectricalDailyCheckRequest $request,
        QcProductElectricalDailyCheck $dailyCheck
    ): RedirectResponse {
        $payload = $this->preparePayload($request);
        $payload['updated_by'] = Auth::id();

        $dailyCheck->update($payload);

        return redirect()
            ->route(
                'monitoring-qc.product-electrical.daily-check.show',
                $dailyCheck
            )
            ->with(
                'success',
                'Data Daily Check QC Product Elektrik berhasil diperbarui.'
            );
    }

    public function destroy(
        QcProductElectricalDailyCheck $dailyCheck
    ): RedirectResponse {
        $dailyCheck->update([
            'updated_by' => Auth::id(),
        ]);

        $dailyCheck->delete();

        return redirect()
            ->route(
                'monitoring-qc.product-electrical.daily-check.index'
            )
            ->with(
                'success',
                'Data Daily Check QC Product Elektrik berhasil dihapus.'
            );
    }

    private function filteredQuery(Request $request): Builder
    {
        $search = trim((string) $request->input('search'));

        return QcProductElectricalDailyCheck::query()
            ->with('project')

            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where('project_name', 'like', "%{$search}%")
                        ->orWhere('document_check', 'like', "%{$search}%")
                        ->orWhere('inspection_gate', 'like', "%{$search}%")
                        ->orWhere('product_name', 'like', "%{$search}%")
                        ->orWhere('check_category', 'like', "%{$search}%")
                        ->orWhere('batch_reference', 'like', "%{$search}%")
                        ->orWhere('oil_description', 'like', "%{$search}%")
                        ->orWhere('inspector', 'like', "%{$search}%")
                        ->orWhere('ncr_number', 'like', "%{$search}%");
                });
            })

            ->when(
                $request->filled('date_from'),
                fn (Builder $query) => $query->whereDate(
                    'check_date',
                    '>=',
                    $request->input('date_from')
                )
            )

            ->when(
                $request->filled('date_to'),
                fn (Builder $query) => $query->whereDate(
                    'check_date',
                    '<=',
                    $request->input('date_to')
                )
            )

            ->when(
                $request->filled('year'),
                fn (Builder $query) => $query->whereYear(
                    'check_date',
                    (int) $request->input('year')
                )
            )

            ->when(
                $request->filled('month'),
                fn (Builder $query) => $query->whereMonth(
                    'check_date',
                    (int) $request->input('month')
                )
            )

            ->when(
                $request->filled('project_id'),
                fn (Builder $query) => $query->where(
                    'project_id',
                    $request->integer('project_id')
                )
            )

            ->when(
                $request->filled('inspection_gate'),
                fn (Builder $query) => $query->where(
                    'inspection_gate',
                    $request->input('inspection_gate')
                )
            )

            ->when(
                $request->filled('check_category'),
                fn (Builder $query) => $query->where(
                    'check_category',
                    $request->input('check_category')
                )
            )

            ->when(
                $request->filled('result'),
                fn (Builder $query) => $query->where(
                    'result',
                    $request->input('result')
                )
            )

            ->when(
                $request->filled('inspector'),
                fn (Builder $query) => $query->where(
                    'inspector',
                    $request->input('inspector')
                )
            );
    }

    private function preparePayload(
        QcProductElectricalDailyCheckRequest $request
    ): array {
        $payload = $request->validated();

        if (!empty($payload['project_id'])) {
            $project = Project::query()
                ->findOrFail($payload['project_id']);

            /*
             * Snapshot nama proyek disimpan agar histori tidak berubah
             * ketika master proyek diperbarui.
             */
            $payload['project_name'] = $project->nama_proyek;
        }

        foreach ($this->quantityFields() as $field) {
            $payload[$field] = (int) ($payload[$field] ?? 0);
        }

        if (empty($payload['ncr_number'])) {
            $payload['ncr_number'] = null;
            $payload['ncr_category'] = null;
        }

        return $payload;
    }

    private function formOptions(): array
    {
        return [
            'projects' => Project::query()
                ->orderBy('nama_proyek')
                ->get(),

            'documentCheckOptions' =>
                QcProductElectricalDailyCheck::documentCheckOptions(),

            'inspectionGateOptions' =>
                QcProductElectricalDailyCheck::inspectionGateOptions(),

            'checkCategoryOptions' =>
                QcProductElectricalDailyCheck::checkCategoryOptions(),

            'statusIsOptions' =>
                QcProductElectricalDailyCheck::statusIsOptions(),

            'resultOptions' =>
                QcProductElectricalDailyCheck::resultOptions(),

            'ncrCategoryOptions' =>
                QcProductElectricalDailyCheck::ncrCategoryOptions(),
        ];
    }

    private function quantityFields(): array
    {
        return [
            'visual_qty',
            'skun_qty',
            'cramping_qty',
            'marking_qty',
            'belltest_qty',
            'function_qty',

            'product_ok_qty',
            'product_nok_qty',
            'cable_ok_qty',
            'cable_nok_qty',
        ];
    }
}
