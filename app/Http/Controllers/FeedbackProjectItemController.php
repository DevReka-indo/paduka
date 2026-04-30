<?php

namespace App\Http\Controllers;

use App\Models\FeedbackProject;
use App\Models\FeedbackProjectItem;
use Illuminate\Http\Request;

class FeedbackProjectItemController extends Controller
{
    public function create()
    {
        $projects = FeedbackProject::where('is_active', true)
            ->orderBy('nama_project')
            ->get();

        return view('feedback.project_item.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'feedback_project_id' => 'required|exists:feedback_projects,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        FeedbackProjectItem::create($validated);

        return redirect()->route('feedback.index')
            ->with('success', 'Barang project feedback berhasil ditambahkan.');
    }

    public function edit(FeedbackProjectItem $feedbackProjectItem)
    {
        $projects = FeedbackProject::where('is_active', true)
            ->orderBy('nama_project')
            ->get();

        return view('feedback.project_item.edit', compact('feedbackProjectItem', 'projects'));
    }

    public function update(Request $request, FeedbackProjectItem $feedbackProjectItem)
    {
        $validated = $request->validate([
            'feedback_project_id' => 'required|exists:feedback_projects,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $feedbackProjectItem->update($validated);

        return redirect()->route('feedback.index')
            ->with('success', 'Barang project feedback berhasil diperbarui.');
    }

    public function destroy(FeedbackProjectItem $feedbackProjectItem)
    {
        $feedbackProjectItem->delete();

        return redirect()->route('feedback.index')
            ->with('success', 'Barang project feedback berhasil dihapus.');
    }
}
