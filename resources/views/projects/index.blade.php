@extends('layouts.app')

@section('header')
    Daftar Project
@endsection

@section('content')
<div class="py-6 max-w-7xl mx-auto space-y-4">

    {{-- Header Bar --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-400">Kelola seluruh data project</p>
        <a href="{{ route('projects.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Project
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('pesan'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center justify-between gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('pesan') }}
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center justify-between gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                </svg>
                {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Search --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('projects.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="text-xs font-medium text-gray-500 mb-1 block">Cari</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nomor, kode, atau nama proyek..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400" />
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('projects.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nomor Proyek</th>
                        <th class="px-4 py-3 text-left font-semibold">Kode Proyek</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Proyek</th>
                        <th class="px-4 py-3 text-left font-semibold">NCR Terkait</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 transition-colors">

                        <td class="px-4 py-3 text-gray-400">{{ $projects->firstItem() + $loop->index }}</td>

                        <td class="px-4 py-3">
                            <a href="{{ route('projects.show', $project) }}"
                                class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                {{ $project->nomor_proyek }}
                            </a>
                        </td>

                        <td class="px-4 py-3">
                            <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg">
                                {{ $project->kode_proyek }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-gray-700 max-w-xs">
                            <p class="truncate">{{ $project->nama_proyek }}</p>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold
                                {{ $project->ncrs_count > 0 ? 'bg-orange-50 text-orange-600' : 'bg-gray-100 text-gray-400' }}
                                px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $project->ncrs_count }} NCR
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="inline-flex items-center gap-1 text-xs font-medium bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>

                                <a href="{{ route('projects.edit', $project) }}"
                                    class="inline-flex items-center gap-1 text-xs font-medium bg-amber-50 text-amber-600 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline"
                                    x-data=""
                                    x-on:submit.prevent="if(confirm('Hapus project {{ $project->nama_proyek }}?\nPastikan tidak ada NCR yang terkait.')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition-colors
                                        {{ $project->ncrs_count > 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                        @if($project->ncrs_count > 0) disabled title="Tidak dapat dihapus, masih ada NCR terkait" @endif>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-400">Belum ada data project</p>
                                <a href="{{ route('projects.create') }}" class="text-xs text-indigo-500 hover:underline">
                                    Tambah project pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($projects->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
