<?php

namespace App\Http\Controllers;

use App\Models\QcFacility;
use App\Models\QcFacilityCalibration;
use App\Models\QcFacilityUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QcFacilityCalibrationController extends Controller
{
    public function create(
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit
    ): View {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        return view(
            'qc-facilities.units.calibrations.create',
            compact(
                'qcFacility',
                'qcFacilityUnit'
            )
        );
    }

    public function store(
        Request $request,
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit
    ): RedirectResponse {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        $validated = $request->validate([
            'calibration_date' => [
                'required',
                'date',
            ],

            'calibration_valid_until' => [
                'required',
                'date',
                'after_or_equal:calibration_date',
            ],

            'certificate_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'calibration_laboratory' => [
                'nullable',
                'string',
                'max:255',
            ],

            'certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $certificatePath = null;

        if ($request->hasFile('certificate')) {
            $certificatePath = $request
                ->file('certificate')
                ->store(
                    'qc-facility-calibrations',
                    'public'
                );
        }

        $qcFacilityUnit
            ->calibrations()
            ->create([
                'calibration_date' =>
                    $validated['calibration_date'],

                'calibration_valid_until' =>
                    $validated[
                        'calibration_valid_until'
                    ],

                'certificate_number' =>
                    $validated[
                        'certificate_number'
                    ] ?? null,

                'calibration_laboratory' =>
                    $validated[
                        'calibration_laboratory'
                    ] ?? null,

                'certificate_path' =>
                    $certificatePath,

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
                    'qcFacilityUnit' =>
                        $qcFacilityUnit,
                ]
            )
            ->with(
                'success',
                'Data kalibrasi berhasil ditambahkan.'
            );
    }

    public function edit(
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit,
        QcFacilityCalibration $qcFacilityCalibration
    ): View {
        $this->ensureHierarchy(
            $qcFacility,
            $qcFacilityUnit,
            $qcFacilityCalibration
        );

        return view(
            'qc-facilities.units.calibrations.edit',
            compact(
                'qcFacility',
                'qcFacilityUnit',
                'qcFacilityCalibration'
            )
        );
    }

    public function update(
        Request $request,
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit,
        QcFacilityCalibration $qcFacilityCalibration
    ): RedirectResponse {
        $this->ensureHierarchy(
            $qcFacility,
            $qcFacilityUnit,
            $qcFacilityCalibration
        );

        $validated = $request->validate([
            'calibration_date' => [
                'required',
                'date',
            ],

            'calibration_valid_until' => [
                'required',
                'date',
                'after_or_equal:calibration_date',
            ],

            'certificate_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'calibration_laboratory' => [
                'nullable',
                'string',
                'max:255',
            ],

            'certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'remove_certificate' => [
                'nullable',
                'boolean',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $certificatePath =
            $qcFacilityCalibration->certificate_path;

        /*
         * Jika upload sertifikat baru:
         * simpan file baru lalu hapus file lama.
         */
        if ($request->hasFile('certificate')) {
            $newCertificatePath = $request
                ->file('certificate')
                ->store(
                    'qc-facility-calibrations',
                    'public'
                );

            if (
                $certificatePath
                && Storage::disk('public')
                    ->exists($certificatePath)
            ) {
                Storage::disk('public')
                    ->delete($certificatePath);
            }

            $certificatePath =
                $newCertificatePath;
        } elseif (
            $request->boolean('remove_certificate')
        ) {
            if (
                $certificatePath
                && Storage::disk('public')
                    ->exists($certificatePath)
            ) {
                Storage::disk('public')
                    ->delete($certificatePath);
            }

            $certificatePath = null;
        }

        $qcFacilityCalibration->update([
            'calibration_date' =>
                $validated['calibration_date'],

            'calibration_valid_until' =>
                $validated[
                    'calibration_valid_until'
                ],

            'certificate_number' =>
                $validated[
                    'certificate_number'
                ] ?? null,

            'calibration_laboratory' =>
                $validated[
                    'calibration_laboratory'
                ] ?? null,

            'certificate_path' =>
                $certificatePath,

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
                    'qcFacilityUnit' =>
                        $qcFacilityUnit,
                ]
            )
            ->with(
                'success',
                'Data kalibrasi berhasil diperbarui.'
            );
    }

    public function destroy(
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit,
        QcFacilityCalibration $qcFacilityCalibration
    ): RedirectResponse {
        $this->ensureHierarchy(
            $qcFacility,
            $qcFacilityUnit,
            $qcFacilityCalibration
        );

        if (
            $qcFacilityCalibration->certificate_path
            && Storage::disk('public')->exists(
                $qcFacilityCalibration
                    ->certificate_path
            )
        ) {
            Storage::disk('public')->delete(
                $qcFacilityCalibration
                    ->certificate_path
            );
        }

        $qcFacilityCalibration->delete();

        return redirect()
            ->route(
                'qc-facilities.units.show',
                [
                    'qcFacility' => $qcFacility,
                    'qcFacilityUnit' =>
                        $qcFacilityUnit,
                ]
            )
            ->with(
                'success',
                'Riwayat kalibrasi berhasil dihapus.'
            );
    }

    public function certificate(
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit,
        QcFacilityCalibration $qcFacilityCalibration
    ): StreamedResponse {
        $this->ensureHierarchy(
            $qcFacility,
            $qcFacilityUnit,
            $qcFacilityCalibration
        );

        $path =
            $qcFacilityCalibration->certificate_path;

        abort_if(
            !$path
            || !Storage::disk('public')->exists($path),
            404,
            'File sertifikat tidak ditemukan.'
        );

        $extension = pathinfo(
            $path,
            PATHINFO_EXTENSION
        );

        $baseName =
            $qcFacilityCalibration->certificate_number
            ?: 'sertifikat-kalibrasi';

        $baseName = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $baseName
        );

        $fileName = $baseName
            . ($extension ? '.' . $extension : '');

        return Storage::disk('public')->response(
            $path,
            $fileName
        );
    }

    private function ensureUnitBelongsToFacility(
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit
    ): void {
        abort_unless(
            (int) $qcFacilityUnit->qc_facility_id
                === (int) $qcFacility->id,
            404
        );
    }

    private function ensureHierarchy(
        QcFacility $qcFacility,
        QcFacilityUnit $qcFacilityUnit,
        QcFacilityCalibration $qcFacilityCalibration
    ): void {
        $this->ensureUnitBelongsToFacility(
            $qcFacility,
            $qcFacilityUnit
        );

        abort_unless(
            (int) $qcFacilityCalibration
                ->qc_facility_unit_id
                === (int) $qcFacilityUnit->id,
            404
        );
    }
}
