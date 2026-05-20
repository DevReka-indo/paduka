<?php

namespace App\Http\Controllers;

use App\Models\FeedbackProject;
use Illuminate\Http\Request;

class FeedbackProjectController extends Controller
{



    public function index(Request $request)
    {
        $projectSearch = trim($request->get('project_search', ''));

        $feedbackProjects = FeedbackProject::query()
            ->when($projectSearch !== '', function ($query) use ($projectSearch) {
                $query->where(function ($q) use ($projectSearch) {
                    $q->where('nama_project', 'like', "%{$projectSearch}%")
                    ->orWhere('deskripsi', 'like', "%{$projectSearch}%");
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'project_page')
            ->withQueryString();

        return view('feedback.index', compact(
            'feedbackProjects',
            'projectSearch'
        ));
    }

    public function create()
    {
        return view('feedback.project.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        FeedbackProject::create($validated);

        return redirect()->route('feedback.index')
            ->with('success', 'Project feedback berhasil ditambahkan.');
    }

    public function edit(FeedbackProject $feedbackProject)
    {
        return view('feedback.project.edit', compact('feedbackProject'));
    }

    public function update(Request $request, FeedbackProject $feedbackProject)
    {
        $validated = $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $feedbackProject->update($validated);

        return redirect()->route('feedback.index')
            ->with('success', 'Project feedback berhasil diperbarui.');
    }

    public function destroy(FeedbackProject $feedbackProject)
    {
        $feedbackProject->delete();

        return redirect()->route('feedback.index')
            ->with('success', 'Project feedback berhasil dihapus.');
    }
}
