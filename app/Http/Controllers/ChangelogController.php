<?php

namespace App\Http\Controllers;

use App\Models\Changelog;
use App\Models\ChangelogItem;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function index()
    {
        $changelogs = Changelog::with('items')
            ->orderByDesc('tanggal_rilis')
            ->paginate(10);

        return view('changelog.index', compact('changelogs'));
    }

    public function create()
    {
        return view('changelog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'versi'         => 'required|string|max:20|unique:changelogs,versi',
            'tipe'          => 'required|in:release,feature,improvement,fix',
            'tanggal_rilis' => 'required|date',
            'deskripsi'     => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*'       => 'required|string|max:255',
        ]);

        $changelog = Changelog::create([
            'versi'         => $request->versi,
            'tipe'          => $request->tipe,
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi'     => $request->deskripsi,
            'is_published'  => $request->boolean('is_published', true),
        ]);

        foreach ($request->items as $i => $isi) {
            if (trim($isi)) {
                ChangelogItem::create([
                    'changelog_id' => $changelog->id,
                    'isi'          => $isi,
                    'urutan'       => $i,
                ]);
            }
        }

        return redirect()->route('changelog.index')
            ->with('success', "Changelog {$changelog->versi} berhasil ditambahkan.");
    }

    public function edit(Changelog $changelog)
    {
        $changelog->load('items');
        return view('changelog.edit', compact('changelog'));
    }

    public function update(Request $request, Changelog $changelog)
    {
        $request->validate([
            'versi'         => 'required|string|max:20|unique:changelogs,versi,' . $changelog->id,
            'tipe'          => 'required|in:release,feature,improvement,fix',
            'tanggal_rilis' => 'required|date',
            'deskripsi'     => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*'       => 'required|string|max:255',
        ]);

        $changelog->update([
            'versi'         => $request->versi,
            'tipe'          => $request->tipe,
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi'     => $request->deskripsi,
            'is_published'  => $request->boolean('is_published', true),
        ]);

        $changelog->items()->delete();
        foreach ($request->items as $i => $isi) {
            if (trim($isi)) {
                ChangelogItem::create([
                    'changelog_id' => $changelog->id,
                    'isi'          => $isi,
                    'urutan'       => $i,
                ]);
            }
        }

        return redirect()->route('changelog.index')
            ->with('success', "Changelog {$changelog->versi} berhasil diperbarui.");
    }

    public function destroy(Changelog $changelog)
    {
        $changelog->delete();

        return redirect()->route('changelog.index')
            ->with('success', 'Changelog berhasil dihapus.');
    }
}
