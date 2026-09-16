<?php

namespace App\Http\Controllers;

use App\Models\QcFacility;
use App\Models\QcFacilityCategory;
use App\Models\QcFacilityUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class QcFacilityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(
            (string) $request->input('search')
        );

        $categoryId =
            $request->input('category_id');

        $condition =
            $request->input('condition');

        $calibrationStatus =
            $request->input('calibration_status');

        $today = now()
            ->startOfDay()
            ->toDateString();

        $expiringUntil = now()
            ->startOfDay()
            ->addDays(30)
            ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Query Master Fasilitas
        |--------------------------------------------------------------------------
        |
        | Satu record qc_facilities = satu master fasilitas.
        | SN, inventaris, lokasi, kondisi dan kalibrasi
        | dicari melalui unit fisiknya.
        |
        */

        $facilitiesQuery = QcFacility::query()
            ->with('category')

            /*
            * Statistik unit untuk setiap card.
            */
            ->withCount([
                'units',

                'units as good_units_count' =>
                    function ($query) {
                        $query->where(
                            'condition',
                            'baik'
                        );
                    },

                'units as issue_units_count' =>
                    function ($query) {
                        $query->where(
                            'condition',
                            '!=',
                            'baik'
                        );
                    },

                'units as valid_units_count' =>
                    function ($query) use (
                        $expiringUntil
                    ) {
                        $query->whereHas(
                            'latestCalibration',
                            function ($calibrationQuery) use (
                                $expiringUntil
                            ) {
                                $calibrationQuery
                                    ->whereNotNull(
                                        'calibration_valid_until'
                                    )
                                    ->whereDate(
                                        'calibration_valid_until',
                                        '>',
                                        $expiringUntil
                                    );
                            }
                        );
                    },

                'units as expiring_units_count' =>
                    function ($query) use (
                        $today,
                        $expiringUntil
                    ) {
                        $query->whereHas(
                            'latestCalibration',
                            function ($calibrationQuery) use (
                                $today,
                                $expiringUntil
                            ) {
                                $calibrationQuery
                                    ->whereNotNull(
                                        'calibration_valid_until'
                                    )
                                    ->whereBetween(
                                        'calibration_valid_until',
                                        [
                                            $today,
                                            $expiringUntil,
                                        ]
                                    );
                            }
                        );
                    },

                'units as expired_units_count' =>
                    function ($query) use (
                        $today
                    ) {
                        $query->whereHas(
                            'latestCalibration',
                            function ($calibrationQuery) use (
                                $today
                            ) {
                                $calibrationQuery
                                    ->whereNotNull(
                                        'calibration_valid_until'
                                    )
                                    ->whereDate(
                                        'calibration_valid_until',
                                        '<',
                                        $today
                                    );
                            }
                        );
                    },

                'units as no_calibration_units_count' =>
                    function ($query) {
                        $query->whereDoesntHave(
                            'latestCalibration'
                        );
                    },
            ])

            /*
            * Search:
            *
            * Master:
            * - nama
            * - merk
            * - model
            *
            * Unit:
            * - nomor inventaris
            * - serial number
            * - lokasi
            */
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        function ($subQuery) use ($search) {
                            $subQuery
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'brand',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'model',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhereHas(
                                    'units',
                                    function ($unitQuery) use (
                                        $search
                                    ) {
                                        $unitQuery->where(
                                            function (
                                                $unitSubQuery
                                            ) use ($search) {
                                                $unitSubQuery
                                                    ->where(
                                                        'inventory_number',
                                                        'like',
                                                        '%' . $search . '%'
                                                    )
                                                    ->orWhere(
                                                        'serial_number',
                                                        'like',
                                                        '%' . $search . '%'
                                                    )
                                                    ->orWhere(
                                                        'location',
                                                        'like',
                                                        '%' . $search . '%'
                                                    );
                                            }
                                        );
                                    }
                                );
                        }
                    );
                }
            )

            /*
            * Filter kategori masih milik master.
            */
            ->when(
                $categoryId,
                function ($query) use ($categoryId) {
                    $query->where(
                        'category_id',
                        $categoryId
                    );
                }
            )

            /*
            * Kondisi sekarang milik unit.
            *
            * Master akan ditampilkan jika minimal
            * satu unit memenuhi kondisi terpilih.
            */
            ->when(
                $condition,
                function ($query) use ($condition) {
                    $query->whereHas(
                        'units',
                        function ($unitQuery) use (
                            $condition
                        ) {
                            $unitQuery->where(
                                'condition',
                                $condition
                            );
                        }
                    );
                }
            )

            /*
            * Kalibrasi Berlaku.
            */
            ->when(
                $calibrationStatus === 'valid',
                function ($query) use (
                    $expiringUntil
                ) {
                    $query->whereHas(
                        'units.latestCalibration',
                        function ($calibrationQuery) use (
                            $expiringUntil
                        ) {
                            $calibrationQuery
                                ->whereNotNull(
                                    'calibration_valid_until'
                                )
                                ->whereDate(
                                    'calibration_valid_until',
                                    '>',
                                    $expiringUntil
                                );
                        }
                    );
                }
            )

            /*
            * Akan kedaluwarsa maksimal 30 hari.
            */
            ->when(
                $calibrationStatus === 'expiring',
                function ($query) use (
                    $today,
                    $expiringUntil
                ) {
                    $query->whereHas(
                        'units.latestCalibration',
                        function ($calibrationQuery) use (
                            $today,
                            $expiringUntil
                        ) {
                            $calibrationQuery
                                ->whereNotNull(
                                    'calibration_valid_until'
                                )
                                ->whereBetween(
                                    'calibration_valid_until',
                                    [
                                        $today,
                                        $expiringUntil,
                                    ]
                                );
                        }
                    );
                }
            )

            /*
            * Kalibrasi kedaluwarsa.
            */
            ->when(
                $calibrationStatus === 'expired',
                function ($query) use ($today) {
                    $query->whereHas(
                        'units.latestCalibration',
                        function ($calibrationQuery) use (
                            $today
                        ) {
                            $calibrationQuery
                                ->whereNotNull(
                                    'calibration_valid_until'
                                )
                                ->whereDate(
                                    'calibration_valid_until',
                                    '<',
                                    $today
                                );
                        }
                    );
                }
            )

            /*
            * Belum ada kalibrasi:
            *
            * - master belum punya unit
            * ATAU
            * - ada unit yang belum punya kalibrasi.
            */
            ->when(
                $calibrationStatus === 'not_available',
                function ($query) {
                    $query->where(
                        function ($subQuery) {
                            $subQuery
                                ->whereDoesntHave('units')
                                ->orWhereHas(
                                    'units',
                                    function ($unitQuery) {
                                        $unitQuery
                                            ->whereDoesntHave(
                                                'latestCalibration'
                                            );
                                    }
                                );
                        }
                    );
                }
            )

            ->latest();

        $facilities = $facilitiesQuery
            ->paginate(12)
            ->withQueryString();

        $categories = QcFacilityCategory::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        |
        | Total = jumlah master fasilitas.
        |
        | Status kalibrasi = jumlah UNIT FISIK,
        | bukan jumlah master.
        |
        */

        $summary = [
            'total' =>
                QcFacility::query()->count(),

            'unit_total' =>
                QcFacilityUnit::query()->count(),

            'valid' =>
                QcFacilityUnit::query()
                    ->whereHas(
                        'latestCalibration',
                        function ($query) use (
                            $expiringUntil
                        ) {
                            $query
                                ->whereNotNull(
                                    'calibration_valid_until'
                                )
                                ->whereDate(
                                    'calibration_valid_until',
                                    '>',
                                    $expiringUntil
                                );
                        }
                    )
                    ->count(),

            'expiring' =>
                QcFacilityUnit::query()
                    ->whereHas(
                        'latestCalibration',
                        function ($query) use (
                            $today,
                            $expiringUntil
                        ) {
                            $query
                                ->whereNotNull(
                                    'calibration_valid_until'
                                )
                                ->whereBetween(
                                    'calibration_valid_until',
                                    [
                                        $today,
                                        $expiringUntil,
                                    ]
                                );
                        }
                    )
                    ->count(),

            'expired' =>
                QcFacilityUnit::query()
                    ->whereHas(
                        'latestCalibration',
                        function ($query) use (
                            $today
                        ) {
                            $query
                                ->whereNotNull(
                                    'calibration_valid_until'
                                )
                                ->whereDate(
                                    'calibration_valid_until',
                                    '<',
                                    $today
                                );
                        }
                    )
                    ->count(),

            'not_available' =>
                QcFacilityUnit::query()
                    ->whereDoesntHave(
                        'latestCalibration'
                    )
                    ->count(),
        ];

        return view(
            'qc-facilities.index',
            compact(
                'facilities',
                'categories',
                'summary',
                'search',
                'categoryId',
                'condition',
                'calibrationStatus'
            )
        );
    }

    public function create (): View
    {
        $categories = QcFacilityCategory::query()
            ->orderBy('name')
            ->get();

        return view('qc-facilities.create', compact('categories'));
    }

    public function store ( Request $request ): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:qc_facility_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'technical_specifications' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $photoPath = $request
            ->file('photo')
            ->store(
                'qc-facilities',
                'public'
            );

        $facility = QcFacility::query()->create([
            'category_id' =>
                $validated['category_id'],

            'name' =>
                $validated['name'],

            'brand' =>
                $validated['brand'] ?? null,

            'model' =>
                $validated['model'] ?? null,

            'technical_specifications' =>
                $validated[
                    'technical_specifications'
                ] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'photo_path' =>
                $photoPath,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'qc-facilities.show',
                $facility
            )
            ->with(
                'success',
                'Master fasilitas QC berhasil ditambahkan. '
                . 'Silakan tambahkan unit/perangkat fisiknya.'
            );
    }

    public function show ( QcFacility $qcFacility ): View
    {
        $qcFacility->load([
            'category',
            'creator',
            'updater',

            'units' => function ($query) {
                $query
                    ->with('latestCalibration')
                    ->orderBy('inventory_number')
                    ->orderBy('serial_number');
            },
        ]);

        return view(
            'qc-facilities.show',
            compact('qcFacility')
        );
    }

    public function edit ( QcFacility $qcFacility ): View
    {
        $categories = QcFacilityCategory::query()
            ->orderBy('name')
            ->get();

        return view('qc-facilities.edit', compact(
            'qcFacility',
            'categories'
        ));
    }

    public function update ( Request $request, QcFacility $qcFacility ): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:qc_facility_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'technical_specifications' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $photoPath =
            $qcFacility->photo_path;

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request
                ->file('photo')
                ->store(
                    'qc-facilities',
                    'public'
                );

            if (
                $qcFacility->photo_path &&
                Storage::disk('public')->exists(
                    $qcFacility->photo_path
                )
            ) {
                Storage::disk('public')->delete(
                    $qcFacility->photo_path
                );
            }

            $photoPath = $newPhotoPath;
        }

        $qcFacility->update([
            'category_id' =>
                $validated['category_id'],

            'name' =>
                $validated['name'],

            'brand' =>
                $validated['brand'] ?? null,

            'model' =>
                $validated['model'] ?? null,

            'technical_specifications' =>
                $validated[
                    'technical_specifications'
                ] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'photo_path' =>
                $photoPath,

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'qc-facilities.show',
                $qcFacility
            )
            ->with(
                'success',
                'Data master fasilitas QC berhasil diperbarui.'
            );
    }

    public function destroy ( QcFacility $qcFacility ): RedirectResponse
    {
        /*
        * Load seluruh unit dan sertifikat kalibrasi
        * sebelum database cascade menghapus record.
        */
        $qcFacility->load(
            'units.calibrations'
        );

        foreach ($qcFacility->units as $unit) {
            foreach (
                $unit->calibrations
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
        }

        /*
        * Hapus foto master.
        */
        if (
            $qcFacility->photo_path &&
            Storage::disk('public')->exists(
                $qcFacility->photo_path
            )
        ) {
            Storage::disk('public')->delete(
                $qcFacility->photo_path
            );
        }

        /*
        * FK cascade:
        * qc_facilities
        * → qc_facility_units
        * → qc_facility_calibrations
        */
        $qcFacility->delete();

        return redirect()
            ->route('qc-facilities.index')
            ->with(
                'success',
                'Data fasilitas QC berhasil dihapus.'
            );
    }
}
