@extends('layouts.app')

@section('header')
    Kategori Fasilitas Quality Control
@endsection

@section('content_width', 'w-full')

@section('content')
    <div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1600px] space-y-6">

            {{-- Header --}}
            <div
                class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>
                <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <p
                            class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                            Quality Control
                        </p>

                        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            Kategori Fasilitas Quality Control
                        </h1>

                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500 dark:text-gray-400">
                            Kelola pengelompokan fasilitas dan peralatan Quality Control.
                        </p>
                    </div>

                    <a href="{{ route('qc-facilities.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">

                        <i class="fa-solid fa-arrow-left mr-2"></i>

                        Kembali ke Fasilitas
                    </a>
                </div>
            </div>

            {{-- Success Alert --}}
            @if (session('success'))
                <div
                    class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">

                    <i class="fa-solid fa-circle-check mt-0.5"></i>

                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Error Alert --}}
            @if (session('error'))
                <div
                    class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">

                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div
                    class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                        <div>
                            <p class="font-semibold">
                                Terdapat data yang belum sesuai:
                            </p>

                            <ul class="mt-2 list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 xl:grid-cols-[380px_minmax(0,1fr)]">

                {{-- Add Category Form --}}
                <div>
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 xl:sticky xl:top-6">

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Tambah Kategori
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Tambahkan kategori fasilitas QC baru.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-300">

                                <i class="fa-solid fa-folder-plus"></i>
                            </span>
                        </div>

                        <form method="POST"
                            action="{{ route('qc-facility-categories.store') }}"
                            class="mt-6 space-y-5">

                            @csrf

                            {{-- Name --}}
                            <div>
                                <label for="name"
                                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                    Nama Kategori
                                    <span class="text-red-500">*</span>
                                </label>

                                <input id="name" type="text" name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Contoh: Alat Ukur Dimensi"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description"
                                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                    Deskripsi
                                </label>

                                <textarea id="description" name="description" rows="4"
                                    placeholder="Deskripsi singkat kategori..."
                                    class="w-full resize-none rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">{{ old('description') }}</textarea>
                            </div>

                            {{-- Sort Order --}}
                            <div>
                                <label for="sort_order"
                                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                    Urutan
                                </label>

                                <input id="sort_order" type="number" name="sort_order"
                                    value="{{ old('sort_order', 0) }}"
                                    min="0"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                    Angka terkecil ditampilkan lebih dahulu.
                                </p>
                            </div>

                            {{-- Active Status --}}
                            <div
                                class="rounded-2xl border border-gray-200 bg-slate-50 p-4 dark:border-gray-700 dark:bg-gray-800/60">

                                <input type="hidden" name="is_active" value="0">

                                <label class="flex cursor-pointer items-start gap-3">
                                    <input type="checkbox" name="is_active" value="1"
                                        @checked(old('is_active', true))
                                        class="mt-0.5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">

                                    <span>
                                        <span class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                                            Kategori Aktif
                                        </span>

                                        <span class="mt-1 block text-xs leading-5 text-gray-500 dark:text-gray-400">
                                            Kategori aktif dapat digunakan pada data fasilitas.
                                        </span>
                                    </span>
                                </label>
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                                <i class="fa-solid fa-floppy-disk mr-2"></i>

                                Simpan Kategori
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Category List --}}
                <div class="space-y-6">

                    {{-- Filter --}}
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                        <form method="GET"
                            action="{{ route('qc-facility-categories.index') }}"
                            class="grid gap-4 md:grid-cols-[minmax(0,1fr)_220px_auto]">

                            {{-- Search --}}
                            <div>
                                <label for="search"
                                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                    Pencarian
                                </label>

                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>

                                    <input id="search" type="text" name="search"
                                        value="{{ $search }}"
                                        placeholder="Nama atau deskripsi kategori..."
                                        class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status"
                                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                    Status
                                </label>

                                <select id="status" name="status"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

                                    <option value="">Semua Status</option>
                                    <option value="active" @selected($status === 'active')>
                                        Aktif
                                    </option>
                                    <option value="inactive" @selected($status === 'inactive')>
                                        Tidak Aktif
                                    </option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                                    <i class="fa-solid fa-filter mr-2"></i>

                                    Filter
                                </button>

                                <a href="{{ route('qc-facility-categories.index') }}"
                                    title="Reset filter"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- List Header --}}
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                Daftar Kategori
                            </h2>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Menampilkan {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }}
                                dari {{ number_format($categories->total()) }} kategori.
                            </p>
                        </div>
                    </div>

                    @if ($categories->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($categories as $category)
                                <details
                                    class="group overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm transition open:shadow-md dark:border-gray-800 dark:bg-gray-900">

                                    {{-- Category Information --}}
                                    <summary
                                        class="flex cursor-pointer list-none flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">

                                        <div class="flex min-w-0 items-start gap-4">
                                            <span
                                                class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-300">

                                                <i class="fa-solid fa-folder"></i>
                                            </span>

                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h3 class="font-bold text-gray-900 dark:text-white">
                                                        {{ $category->name }}
                                                    </h3>

                                                    @if ($category->is_active)
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300">

                                                            <i class="fa-solid fa-circle mr-1.5 text-[6px]"></i>

                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-500/20 dark:bg-white/10 dark:text-slate-300">

                                                            <i class="fa-solid fa-circle mr-1.5 text-[6px]"></i>

                                                            Tidak Aktif
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                                                    {{ $category->slug }}
                                                </p>

                                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                    {{ $category->description ?: 'Belum ada deskripsi kategori.' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex flex-shrink-0 items-center gap-3">
                                            <div
                                                class="rounded-2xl bg-slate-50 px-4 py-2.5 text-center dark:bg-white/[0.04]">

                                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($category->facilities_count) }}
                                                </p>

                                                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">
                                                    Fasilitas
                                                </p>
                                            </div>

                                            <div
                                                class="rounded-2xl bg-slate-50 px-4 py-2.5 text-center dark:bg-white/[0.04]">

                                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                                    {{ $category->sort_order }}
                                                </p>

                                                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">
                                                    Urutan
                                                </p>
                                            </div>

                                            <span
                                                class="flex h-10 w-10 items-center justify-center rounded-xl text-gray-400 transition group-open:rotate-180">

                                                <i class="fa-solid fa-chevron-down"></i>
                                            </span>
                                        </div>
                                    </summary>

                                    {{-- Edit Form --}}
                                    <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                                        <form method="POST"
                                            action="{{ route('qc-facility-categories.update', $category) }}"
                                            class="space-y-5">

                                            @csrf
                                            @method('PUT')

                                            <div class="grid gap-5 md:grid-cols-2">
                                                {{-- Edit Name --}}
                                                <div>
                                                    <label for="name-{{ $category->id }}"
                                                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                                        Nama Kategori
                                                        <span class="text-red-500">*</span>
                                                    </label>

                                                    <input id="name-{{ $category->id }}"
                                                        type="text"
                                                        name="name"
                                                        value="{{ $category->name }}"
                                                        required
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                                </div>

                                                {{-- Edit Sort Order --}}
                                                <div>
                                                    <label for="sort-order-{{ $category->id }}"
                                                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                                        Urutan
                                                    </label>

                                                    <input id="sort-order-{{ $category->id }}"
                                                        type="number"
                                                        name="sort_order"
                                                        value="{{ $category->sort_order }}"
                                                        min="0"
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                                </div>
                                            </div>

                                            {{-- Edit Description --}}
                                            <div>
                                                <label for="description-{{ $category->id }}"
                                                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                                                    Deskripsi
                                                </label>

                                                <textarea id="description-{{ $category->id }}"
                                                    name="description"
                                                    rows="3"
                                                    class="w-full resize-none rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">{{ $category->description }}</textarea>
                                            </div>

                                            {{-- Edit Active Status --}}
                                            <div
                                                class="rounded-2xl border border-gray-200 bg-slate-50 p-4 dark:border-gray-700 dark:bg-gray-800/60">

                                                <input type="hidden" name="is_active" value="0">

                                                <label class="flex cursor-pointer items-start gap-3">
                                                    <input type="checkbox"
                                                        name="is_active"
                                                        value="1"
                                                        @checked($category->is_active)
                                                        class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">

                                                    <span>
                                                        <span class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                            Kategori Aktif
                                                        </span>

                                                        <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">
                                                            Nonaktifkan jika kategori tidak boleh digunakan lagi.
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>

                                            {{-- Actions --}}
                                            <div
                                                class="flex flex-col gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">

                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

                                                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                                                    Simpan Perubahan
                                                </button>
                                        </form>

                                                <form method="POST"
                                                    action="{{ route('qc-facility-categories.destroy', $category) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        @disabled($category->facilities_count > 0)
                                                        title="{{ $category->facilities_count > 0
                                                            ? 'Kategori masih digunakan oleh fasilitas'
                                                            : 'Hapus kategori' }}"
                                                        class="inline-flex items-center justify-center rounded-xl border px-4 py-2.5 text-sm font-semibold transition
                                                            {{ $category->facilities_count > 0
                                                                ? 'cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600'
                                                                : 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 dark:border-red-900/40 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20' }}">

                                                        <i class="fa-solid fa-trash mr-2"></i>

                                                        Hapus Kategori
                                                    </button>
                                                </form>
                                            </div>
                                    </div>
                                </details>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div
                            class="rounded-2xl border border-white/70 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                            {{ $categories->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div
                            class="rounded-3xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900">

                            <span
                                class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100 text-3xl text-slate-400 dark:bg-white/10 dark:text-gray-500">

                                <i class="fa-solid fa-folder-open"></i>
                            </span>

                            <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">
                                Kategori tidak ditemukan
                            </h3>

                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500 dark:text-gray-400">
                                Belum ada kategori yang sesuai dengan pencarian atau filter yang diterapkan.
                            </p>

                            <a href="{{ route('qc-facility-categories.index') }}"
                                class="mt-6 inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">

                                <i class="fa-solid fa-rotate-left mr-2"></i>

                                Reset Filter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
