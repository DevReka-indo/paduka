<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemuanController extends Controller
{
    /**
     * Daftar semua lokasi temuan.
     */
    public function index(Request $request): View
    {
        $query = Temuan::withCount('ncrs');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_temuan', 'like', '%' . $request->search . '%')
                    ->orWhere('status_temuan', 'like', '%' . $request->search . '%')
                    ->orWhere('detail_temuan', 'like', '%' . $request->search . '%');
            });
        }

        $temuans = $query->latest()->paginate(10)->withQueryString();

        return view('temuan.index', compact('temuans'));
    }

    /**
     * Form tambah temuan.
     */
    public function create(): View
    {
        $nomorPreview = Temuan::generateNomorTemuan();

        return view('temuan.create', compact('nomorPreview'));
    }

    /**
     * Simpan temuan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'status_temuan' => ['required', 'string', 'max:25'],
                'detail_temuan' => ['nullable', 'string', 'max:175'],
            ],
            [
                'status_temuan.required' => 'Status temuan wajib diisi.',
            ],
        );

        Temuan::create($validated);

        return redirect()->route('temuan.index')->with('pesan', 'Lokasi temuan berhasil ditambahkan.');
    }

    /**
     * Detail temuan.
     */
    public function show(Temuan $lokasi): View
    {
        $lokasi->loadCount('ncrs');
        $lokasi->load('ncrs');

        return view('temuan.show', compact('lokasi'));
    }

    /**
     * Form edit temuan.
     */
    public function edit(Temuan $lokasi): View
    {
        return view('temuan.edit', compact('lokasi'));
    }

    /**
     * Update temuan.
     */
    public function update(Request $request, Temuan $lokasi): RedirectResponse
    {
        $request->validate([
            'status_temuan' => ['required', 'string', 'max:25'],
            'detail_temuan' => ['nullable', 'string', 'max:175'],
        ], [
            'status_temuan.required' => 'Lokasi temuan wajib diisi.',
        ]);

        $lokasi->update($request->only(['status_temuan', 'detail_temuan']));

        return redirect()->route('temuan.index')->with('pesan', 'Lokasi temuan berhasil diperbarui.');
    }

    /**
     * Hapus temuan.
     */
    public function destroy(Temuan $lokasi): RedirectResponse
    {
        if ($lokasi->ncrs()->exists()) {
            return redirect()
                ->route('temuan.index')
                ->with('error', "Temuan \"{$lokasi->nomor_temuan}\" tidak dapat dihapus karena masih terkait dengan data NCR.");
        }

        $lokasi->delete();

        return redirect()->route('temuan.index')->with('pesan', 'Lokasi temuan berhasil dihapus.');
    }
}
