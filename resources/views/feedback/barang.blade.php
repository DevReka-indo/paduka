@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1400px] space-y-6">

        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Master Data
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Master Barang / Komponen Project
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Daftar barang atau komponen yang terkait dengan project feedback.
                    </p>
                </div>

                <a href="{{ route('feedback-project-items.create') }}"
                   class="inline-flex w-fit items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Tambah Barang
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-100 bg-white p-2 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('feedback.index') }}"
                   class="rounded-2xl px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                    Data Feedback
                </a>

                <a href="{{ route('feedback.project') }}"
                   class="rounded-2xl px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                    Master Project
                </a>

                <a href="{{ route('feedback.barang') }}"
                   class="rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                    Master Barang
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-5 shadow-sm dark:border-indigo-900/30 dark:bg-indigo-900/20">
                <p class="text-xs font-bold uppercase tracking-wide text-indigo-700 dark:text-indigo-300">
                    Total Barang
                </p>
                <p class="mt-3 text-4xl font-bold text-indigo-700 dark:text-indigo-300">
                    {{ number_format($feedbackProjectItems->total()) }}
                </p>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 md:col-span-2">
                <form method="GET" action="{{ route('feedback.barang') }}" class="flex flex-col gap-3 md:flex-row">
                    <input
                        type="text"
                        name="item_search"
                        value="{{ $itemSearch }}"
                        placeholder="Cari project atau nama barang..."
                        class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    >

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Cari
                    </button>

                    @if($itemSearch)
                        <a href="{{ route('feedback.barang') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-4 text-left font-bold">#</th>
                            <th class="px-5 py-4 text-left font-bold">Project</th>
                            <th class="px-5 py-4 text-left font-bold">Nama Barang</th>
                            <th class="px-5 py-4 text-left font-bold">Deskripsi</th>
                            <th class="px-5 py-4 text-left font-bold">Status</th>
                            <th class="px-5 py-4 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($feedbackProjectItems as $index => $item)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-4 text-gray-400">
                                    {{ $feedbackProjectItems->firstItem() + $index }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item->project->nama_project ?? '—' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-gray-700 dark:text-gray-200">
                                    {{ $item->nama_barang }}
                                </td>

                                <td class="max-w-[360px] truncate px-5 py-4 text-gray-600 dark:text-gray-300" title="{{ $item->deskripsi }}">
                                    {{ $item->deskripsi ?: '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($item->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700 dark:bg-red-900/20 dark:text-red-300">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('feedback-project-items.edit', $item->id) }}"
                                           class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-300">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('feedback-project-items.destroy', $item->id) }}"
                                              onsubmit="return confirm('Hapus barang {{ addslashes($item->nama_barang) }}?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100 dark:bg-red-900/20 dark:text-red-300">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <img src="{{ asset('img/data-not-found.png') }}" alt="Data tidak ditemukan" class="h-28 w-auto opacity-90">
                                        <p class="font-semibold text-gray-500 dark:text-gray-400">
                                            Belum ada master barang project
                                        </p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">
                                            Klik Tambah Barang untuk membuat daftar barang/komponen per project.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($feedbackProjectItems->hasPages())
                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                    {{ $feedbackProjectItems->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
