<?php

namespace App\Http\Controllers;

use App\Models\WorkIndicator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WorkIndicatorController extends Controller
{
    public function index(Request $request): View
    {
        $selectedType = $request->input(
            'type',
            WorkIndicator::TYPE_PROGRAM_KERJA
        );

        if (!array_key_exists(
            $selectedType,
            WorkIndicator::typeOptions()
        )) {
            $selectedType = WorkIndicator::TYPE_PROGRAM_KERJA;
        }

        $indicators = WorkIndicator::query()
            ->where('type', $selectedType)
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view(
            'work-program-kpi.indicators.index',
            compact(
                'indicators',
                'selectedType'
            )
        );
    }

    public function create(Request $request): View
    {
        $selectedType = $request->input(
            'type',
            WorkIndicator::TYPE_PROGRAM_KERJA
        );

        if (!array_key_exists(
            $selectedType,
            WorkIndicator::typeOptions()
        )) {
            $selectedType = WorkIndicator::TYPE_PROGRAM_KERJA;
        }

        return view(
            'work-program-kpi.indicators.create',
            compact(
                'selectedType'
            )
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(array_keys(
                    WorkIndicator::typeOptions()
                )),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Program Kerja dan KPI sama-sama
         * menggunakan periode bulanan.
         */
        $validated['input_period'] =
            WorkIndicator::PERIOD_MONTHLY;

        $validated['is_active'] =
            $request->boolean('is_active');

        WorkIndicator::query()->create(
            $validated
        );

        return redirect()
            ->route(
                'work-program-kpi.indicators.index',
                [
                    'type' => $validated['type'],
                ]
            )
            ->with(
                'success',
                'Indikator berhasil ditambahkan.'
            );
    }

    public function edit(
        WorkIndicator $workIndicator
    ): View {
        return view(
            'work-program-kpi.indicators.edit',
            compact(
                'workIndicator'
            )
        );
    }

    public function update(
        Request $request,
        WorkIndicator $workIndicator
    ): RedirectResponse {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(array_keys(
                    WorkIndicator::typeOptions()
                )),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Program Kerja dan KPI sama-sama
         * menggunakan periode bulanan.
         */
        $validated['input_period'] =
            WorkIndicator::PERIOD_MONTHLY;

        $validated['is_active'] =
            $request->boolean('is_active');

        $workIndicator->update(
            $validated
        );

        return redirect()
            ->route(
                'work-program-kpi.indicators.index',
                [
                    'type' => $validated['type'],
                ]
            )
            ->with(
                'success',
                'Indikator berhasil diperbarui.'
            );
    }
}
