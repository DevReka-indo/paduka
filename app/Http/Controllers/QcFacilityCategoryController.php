<?php

namespace App\Http\Controllers;

use App\Models\QcFacilityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QcFacilityCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori fasilitas QC.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $categories = QcFacilityCategory::query()
            ->withCount('facilities')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere(
                            'description',
                            'like',
                            '%' . $search . '%'
                        );
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('qc-facility-categories.index', compact(
            'categories',
            'search',
            'status'
        ));
    }

    /**
     * Menyimpan kategori fasilitas QC.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qc_facility_categories', 'name'),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'name.unique' => 'Nama kategori tersebut sudah tersedia.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan tidak boleh kurang dari 0.',
            'is_active.boolean' => 'Status kategori tidak valid.',
        ]);

        $name = trim($validated['name']);

        QcFacilityCategory::query()->create([
            'name' => $name,
            'slug' => $this->generateUniqueSlug($name),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('qc-facility-categories.index')
            ->with(
                'success',
                'Kategori fasilitas QC berhasil ditambahkan.'
            );
    }

    /**
     * Memperbarui kategori fasilitas QC.
     */
    public function update(
        Request $request,
        QcFacilityCategory $qcFacilityCategory
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qc_facility_categories', 'name')
                    ->ignore($qcFacilityCategory->id),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'name.unique' => 'Nama kategori tersebut sudah tersedia.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan tidak boleh kurang dari 0.',
            'is_active.boolean' => 'Status kategori tidak valid.',
        ]);

        $name = trim($validated['name']);

        $qcFacilityCategory->update([
            'name' => $name,
            'slug' => $this->generateUniqueSlug(
                $name,
                $qcFacilityCategory->id
            ),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('qc-facility-categories.index')
            ->with(
                'success',
                'Kategori fasilitas QC berhasil diperbarui.'
            );
    }

    /**
     * Menghapus kategori fasilitas QC.
     */
    public function destroy(
        QcFacilityCategory $qcFacilityCategory
    ): RedirectResponse {
        if ($qcFacilityCategory->facilities()->exists()) {
            return redirect()
                ->route('qc-facility-categories.index')
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan oleh fasilitas QC.'
                );
        }

        $qcFacilityCategory->delete();

        return redirect()
            ->route('qc-facility-categories.index')
            ->with(
                'success',
                'Kategori fasilitas QC berhasil dihapus.'
            );
    }

    /**
     * Membuat slug kategori yang unik.
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'kategori';

        $slug = $baseSlug;
        $counter = 1;

        while (
            QcFacilityCategory::query()
                ->where('slug', $slug)
                ->when($ignoreId !== null, function ($query) use ($ignoreId) {
                    $query->whereKeyNot($ignoreId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
