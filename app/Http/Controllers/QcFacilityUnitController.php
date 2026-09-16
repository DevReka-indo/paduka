<?php

namespace App\Http\Controllers;

use App\Models\QcFacility;
use App\Models\QcFacilityUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class QcFacilityUnitController extends Controller
{
    public function create( QcFacility $qcFacility ): View
    {
        return view(
            'qc-facilities.units.create',
            compact('qcFacility')
        );
    }

    public function store( Request $request, QcFacility $qcFacility ): RedirectResponse
    {
        $validated = $request->validate([
            'inventory_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:qc_facility_units,inventory_number',
            ],

            'serial_number' => [
                'required',
                'string',
                'max:255',
                'unique:qc_facility_units,serial_number',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'condition' => [
                'required',
                Rule::in([
                    'baik',
                    'perlu_perbaikan',
                    'dalam_perbaikan',
                    'tidak_layak',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $unit = $qcFacility->units()->create([
            'inventory_number' =>
                $validated['inventory_number'] ?? null,

            'serial_number' =>
                $validated['serial_number'],

            'location' =>
                $validated['location'] ?? null,

            'condition' =>
                $validated['condition'],

            'notes' =>
                $validated['notes'] ?? null,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'qc-facilities.units.show',
                [
                    'qcFacility' => $qcFacility,
                    'qcFacilityUnit' => $unit,
                ]
            )
            ->with(
                'success',
                'Unit fasilitas berhasil ditambahkan.'
            );
    }

    public function show( QcFacility $qcFacility, QcFacilityUnit $qcFacilityUnit ): View
    {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        $qcFacilityUnit->load([
            'facility.category',
            'latestCalibration',
            'calibrations' => function ($query) {
                $query
                    ->orderByDesc('calibration_date')
                    ->orderByDesc('id');
            },
            'creator',
            'updater',
        ]);

        $qcFacility->load('category');

        return view(
            'qc-facilities.units.show',
            compact(
                'qcFacility',
                'qcFacilityUnit'
            )
        );
    }

    public function edit( QcFacility $qcFacility, QcFacilityUnit $qcFacilityUnit ): View
    {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        return view(
            'qc-facilities.units.edit',
            compact(
                'qcFacility',
                'qcFacilityUnit'
            )
        );
    }

    public function update( Request $request, QcFacility $qcFacility, QcFacilityUnit $qcFacilityUnit ): RedirectResponse
    {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        $validated = $request->validate([
            'inventory_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(
                    'qc_facility_units',
                    'inventory_number'
                )->ignore($qcFacilityUnit->id),
            ],

            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'qc_facility_units',
                    'serial_number'
                )->ignore($qcFacilityUnit->id),
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'condition' => [
                'required',
                Rule::in([
                    'baik',
                    'perlu_perbaikan',
                    'dalam_perbaikan',
                    'tidak_layak',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $qcFacilityUnit->update([
            'inventory_number' =>
                $validated['inventory_number'] ?? null,

            'serial_number' =>
                $validated['serial_number'],

            'location' =>
                $validated['location'] ?? null,

            'condition' =>
                $validated['condition'],

            'notes' =>
                $validated['notes'] ?? null,

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'qc-facilities.units.show',
                [
                    'qcFacility' => $qcFacility,
                    'qcFacilityUnit' => $qcFacilityUnit,
                ]
            )
            ->with(
                'success',
                'Unit fasilitas berhasil diperbarui.'
            );
    }

    public function destroy( QcFacility $qcFacility, QcFacilityUnit $qcFacilityUnit ): RedirectResponse
    {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        $qcFacilityUnit->load('calibrations');

        foreach (
            $qcFacilityUnit->calibrations
            as $calibration
        ) {
            if (
                $calibration->certificate_path &&
                Storage::disk('local')->exists(
                    $calibration->certificate_path
                )
            ) {
                Storage::disk('local')->delete(
                    $calibration->certificate_path
                );
            }
        }

        $qcFacilityUnit->delete();

        return redirect()
            ->route(
                'qc-facilities.show',
                $qcFacility
            )
            ->with(
                'success',
                'Unit fasilitas berhasil dihapus.'
            );
    }

    private function ensureUnitBelongsToFacility( QcFacility $qcFacility, QcFacilityUnit $qcFacilityUnit ): void
    {
        abort_unless(
            (int) $qcFacilityUnit->qc_facility_id
                === (int) $qcFacility->id,
            404
        );
    }
}
