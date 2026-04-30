@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Feedback Project</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('feedback-projects.create') }}" class="btn btn-primary mb-3">
        Tambah Project
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Project</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
                <tr>
                    <td>{{ $projects->firstItem() + $loop->index }}</td>
                    <td>{{ $project->nama_project }}</td>
                    <td>{{ $project->deskripsi ?? '-' }}</td>
                    <td>
                        @if ($project->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('feedback-projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('feedback-projects.destroy', $project->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus project ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data project.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $projects->links() }}
</div>
@endsection
