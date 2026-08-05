<?php

namespace App\Http\Controllers;

use App\Models\QcFacility;
use App\Models\QcFacilityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QcFacilityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $categoryId = $request->input('category_id');
        $condition = $request->input('condition');
        $calibrationStatus = $request->input('calibration_status');

        $facilitiesQuery = QcFacility::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%')
                        ->orWhere('model', 'like', '%' . $search . '%')
                        ->orWhere('inventory_number', 'like', '%' . $search . '%')
                        ->orWhere('serial_number', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($condition, function ($query) use ($condition) {
                $query->where('condition', $condition);
            })
            ->when($calibrationStatus === 'valid', function ($query) {
                $query
                    ->whereNotNull('calibration_valid_until')
                    ->whereDate(
                        'calibration_valid_until',
                        '>',
                        now()->addDays(30)->toDateString()
                    );
            })
            ->when($calibrationStatus === 'expiring', function ($query) {
                $query
                    ->whereNotNull('calibration_valid_until')
                    ->whereBetween('calibration_valid_until', [
                        now()->toDateString(),
                        now()->addDays(30)->toDateString(),
                    ]);
            })
            ->when($calibrationStatus === 'expired', function ($query) {
                $query
                    ->whereNotNull('calibration_valid_until')
                    ->whereDate(
                        'calibration_valid_until',
                        '<',
                        now()->toDateString()
                    );
            })
            ->when($calibrationStatus === 'not_available', function ($query) {
                $query->whereNull('calibration_valid_until');
            })
            ->latest();

        $facilities = $facilitiesQuery
            ->paginate(12)
            ->withQueryString();

        $categories = QcFacilityCategory::query()
            ->orderBy('name')
            ->get();

        $summary = [
            'total' => QcFacility::query()->count(),

            'valid' => QcFacility::query()
                ->whereNotNull('calibration_valid_until')
                ->whereDate(
                    'calibration_valid_until',
                    '>',
                    now()->addDays(30)->toDateString()
                )
                ->count(),

            'expiring' => QcFacility::query()
                ->whereNotNull('calibration_valid_until')
                ->whereBetween('calibration_valid_until', [
                    now()->toDateString(),
                    now()->addDays(30)->toDateString(),
                ])
                ->count(),

            'expired' => QcFacility::query()
                ->whereNotNull('calibration_valid_until')
                ->whereDate(
                    'calibration_valid_until',
                    '<',
                    now()->toDateString()
                )
                ->count(),
        ];

        return view('qc-facilities.index', compact(
            'facilities',
            'categories',
            'summary',
            'search',
            'categoryId',
            'condition',
            'calibrationStatus'
        ));
    }

    public function create(): View
    {
        $categories = QcFacilityCategory::query()
            ->orderBy('name')
            ->get();

        return view('qc-facilities.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
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
            'inventory_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:qc_facilities,inventory_number',
            ],
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:qc_facilities,serial_number',
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
            'calibration_date' => [
                'nullable',
                'date',
            ],
            'calibration_valid_until' => [
                'nullable',
                'date',
                'after_or_equal:calibration_date',
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
            ->store('qc-facilities', 'public');

        $facility = QcFacility::query()->create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['model'] ?? null,
            'technical_specifications' => $validated['technical_specifications'] ?? null,
            'inventory_number' => $validated['inventory_number'] ?? null,
            'serial_number' => $validated['serial_number'] ?? null,
            'location' => $validated['location'] ?? null,
            'condition' => $validated['condition'],
            'calibration_date' => $validated['calibration_date'] ?? null,
            'calibration_valid_until' => $validated['calibration_valid_until'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo_path' => $photoPath,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('qc-facilities.show', $facility)
            ->with('success', 'Data fasilitas QC berhasil ditambahkan.');
    }

    public function show(QcFacility $qcFacility): View
    {
        $qcFacility->load([
            'category',
            'creator',
            'updater',
        ]);

        return view('qc-facilities.show', compact('qcFacility'));
    }

    public function edit(QcFacility $qcFacility): View
    {
        $categories = QcFacilityCategory::query()
            ->orderBy('name')
            ->get();

        return view('qc-facilities.edit', compact(
            'qcFacility',
            'categories'
        ));
    }

    public function update(
        Request $request,
        QcFacility $qcFacility
    ): RedirectResponse {
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
            'inventory_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('qc_facilities', 'inventory_number')
                    ->ignore($qcFacility->id),
            ],
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('qc_facilities', 'serial_number')
                    ->ignore($qcFacility->id),
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
            'calibration_date' => [
                'nullable',
                'date',
            ],
            'calibration_valid_until' => [
                'nullable',
                'date',
                'after_or_equal:calibration_date',
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

        $photoPath = $qcFacility->photo_path;

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request
                ->file('photo')
                ->store('qc-facilities', 'public');

            if (
                $qcFacility->photo_path &&
                Storage::disk('public')->exists($qcFacility->photo_path)
            ) {
                Storage::disk('public')->delete($qcFacility->photo_path);
            }

            $photoPath = $newPhotoPath;
        }

        $qcFacility->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['model'] ?? null,
            'technical_specifications' => $validated['technical_specifications'] ?? null,
            'inventory_number' => $validated['inventory_number'] ?? null,
            'serial_number' => $validated['serial_number'] ?? null,
            'location' => $validated['location'] ?? null,
            'condition' => $validated['condition'],
            'calibration_date' => $validated['calibration_date'] ?? null,
            'calibration_valid_until' => $validated['calibration_valid_until'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo_path' => $photoPath,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('qc-facilities.show', $qcFacility)
            ->with('success', 'Data fasilitas QC berhasil diperbarui.');
    }

    public function destroy(QcFacility $qcFacility): RedirectResponse
    {
        if (
            $qcFacility->photo_path &&
            Storage::disk('public')->exists($qcFacility->photo_path)
        ) {
            Storage::disk('public')->delete($qcFacility->photo_path);
        }

        $qcFacility->delete();

        return redirect()
            ->route('qc-facilities.index')
            ->with('success', 'Data fasilitas QC berhasil dihapus.');
    }
}
