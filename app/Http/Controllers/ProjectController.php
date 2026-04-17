<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Daftar semua project.
     */
    public function index(Request $request): View
    {
        $query = Project::withCount('ncrs');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_proyek', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_proyek', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_proyek', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    /**
     * Form tambah project.
     */
    public function create(): View
    {
        $nomorPreview = Project::generateNomorProyek();

        return view('projects.create', compact('nomorPreview'));
    }

    /**
     * Simpan project baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kode_proyek'  => ['required', 'string', 'max:50', 'unique:projects,kode_proyek'],
            'nama_proyek'  => ['required', 'string', 'max:175'],
        ], [
            'kode_proyek.unique'  => 'Kode proyek sudah digunakan.',
        ]);

        Project::create($request->only(['kode_proyek', 'nama_proyek']));

        return redirect()->route('projects.index')->with('pesan', 'Project berhasil ditambahkan.');
    }

    /**
     * Detail project.
     */
    public function show(Project $project): View
    {
        $project->loadCount('ncrs');
        $project->load('ncrs.penanggungJawab');

        return view('projects.show', compact('project'));
    }

    /**
     * Form edit project.
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update project.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'kode_proyek'  => ['required', 'string', 'max:50', "unique:projects,kode_proyek,{$project->id}"],
            'nama_proyek'  => ['required', 'string', 'max:175'],
        ], [
            'kode_proyek.unique'  => 'Kode proyek sudah digunakan.',
        ]);

        $project->update($request->only(['kode_proyek', 'nama_proyek']));

        return redirect()->route('projects.index')->with('pesan', 'Data project berhasil diperbarui.');
    }

    /**
     * Hapus project.
     */
    public function destroy(Project $project): RedirectResponse
    {
        if ($project->ncrs()->exists()) {
            return redirect()->route('projects.index')
                ->with('error', "Project \"{$project->nama_proyek}\" tidak dapat dihapus karena masih memiliki data NCR terkait.");
        }

        $project->delete();

        return redirect()->route('projects.index')->with('pesan', 'Project berhasil dihapus.');
    }
}
