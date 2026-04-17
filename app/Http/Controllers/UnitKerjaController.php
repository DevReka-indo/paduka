<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitKerjaController extends Controller
{
    /**
     * Daftar semua unit kerja.
     */
    public function index(Request $request): View
    {
        $query = UnitKerja::withCount('users');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_unit', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_unit', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('keterangan', $request->status === 'aktif');
        }

        $unitKerja = $query->orderBy('nama_unit', 'asc')->paginate(20)->withQueryString();

        return view('users.unit_kerja.index', compact('unitKerja'));
    }

    /**
     * Detail unit kerja.
     */
    public function show(UnitKerja $unitKerja)
    {
        $unitKerja->loadCount('users')->load([
            'users' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        return view('users.unit_kerja.show', compact('unitKerja'));
    }

    /**
     * Form tambah unit kerja.
     */
    public function create(): View
    {
        return view('users.unit_kerja.create');
    }

    /**
     * Simpan unit kerja baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'kode_unit' => ['nullable', 'string', 'max:20', 'unique:unit_kerja,kode_unit'],
                'nama_unit' => ['required', 'string', 'max:100'],
                'deskripsi' => ['nullable', 'string'],
                'keterangan' => ['boolean'],
            ],
            [
                'kode_unit.unique' => 'Kode unit sudah digunakan.',
                'nama_unit.required' => 'Nama unit wajib diisi.',
            ],
        );

        UnitKerja::create([
            'kode_unit' => $request->kode_unit ?: null,
            'nama_unit' => $request->nama_unit,
            'deskripsi' => $request->deskripsi,
            'keterangan' => $request->boolean('keterangan', true),
        ]);

        return redirect()->route('unit-kerja.index')->with('pesan', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Form edit unit kerja.
     */
    public function edit(UnitKerja $unitKerja): View
    {
        return view('users.unit_kerja.edit', compact('unitKerja'));
    }

    /**
     * Update unit kerja.
     */
    public function update(Request $request, UnitKerja $unitKerja): RedirectResponse
    {
        $request->validate(
            [
                'kode_unit' => ['nullable', 'string', 'max:20', Rule::unique('unit_kerja', 'kode_unit')->ignore($unitKerja->id)],
                'nama_unit' => ['required', 'string', 'max:100'],
                'deskripsi' => ['nullable', 'string'],
                'keterangan' => ['boolean'],
            ],
            [
                'kode_unit.unique' => 'Kode unit sudah digunakan.',
            ],
        );

        $unitKerja->update([
            'kode_unit' => $request->kode_unit ?: null,
            'nama_unit' => $request->nama_unit,
            'deskripsi' => $request->deskripsi,
            'keterangan' => $request->boolean('keterangan', true),
        ]);

        return redirect()->route('unit-kerja.index')->with('pesan', 'Unit kerja berhasil diperbarui.');
    }

    /**
     * Hapus unit kerja.
     */
    public function destroy(UnitKerja $unitKerja): RedirectResponse
    {
        if ($unitKerja->users()->exists()) {
            return redirect()
                ->route('unit-kerja.index')
                ->with('error', "Unit kerja \"{$unitKerja->nama_unit}\" tidak dapat dihapus karena masih digunakan oleh " . $unitKerja->users()->count() . ' pengguna.');
        }

        $unitKerja->delete();

        return redirect()->route('unit-kerja.index')->with('pesan', 'Unit kerja berhasil dihapus.');
    }
}
