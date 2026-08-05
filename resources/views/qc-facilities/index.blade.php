@extends('layouts.app')

@section('header')
    Fasilitas Quality Control
@endsection

@section('content_width', 'w-full')

@section('content')
    @php
        $level = strtolower(auth()->user()->level ?? '');
        $canManage = in_array($level, ['admin', 'superadmin'], true);

        $conditionLabels = [
            'baik' => 'Baik',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'dalam_perbaikan' => 'Dalam Perbaikan',
            'tidak_layak' => 'Tidak Layak Digunakan',
        ];

        $conditionBadgeClasses = [
            'baik' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
            'perlu_perbaikan' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',
            'dalam_perbaikan' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-400/20',
            'tidak_layak' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
        ];

        $calibrationBadgeClasses = [
            'valid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
            'expiring' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',
            'expired' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
            'not_available' => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
        ];
    @endphp

    <div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1600px] space-y-6">

            {{-- Header --}}
            <div
                class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>

                <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl">
                </div>

                <div class="relative flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <p
                            class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                            Quality Control
                        </p>

                        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            Fasilitas Alat Quality Control
                        </h1>

                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500 dark:text-gray-400">
                            Pusat informasi fasilitas dan peralatan Quality Control berdasarkan kategori,
                            spesifikasi teknis, kondisi alat, serta status masa berlaku kalibrasi.
                        </p>
                    </div>

                    @if ($canManage)
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('qc-facility-categories.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-blue-300">

                                <i class="fa-solid fa-folder-tree mr-2"></i>

                                Kelola Kategori
                            </a>

                            <a href="{{ route('qc-facilities.create') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                                <i class="fa-solid fa-plus mr-2"></i>

                                Tambah Fasilitas
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Success Alert --}}
            @if (session('success'))
                <div
                    class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">

                    <i class="fa-solid fa-circle-check mt-0.5"></i>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- Error Alert --}}
            @if (session('error'))
                <div
                    class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">

                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                    <span>
                        {{ session('error') }}
                    </span>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- Total Fasilitas --}}
                <a href="{{ route('qc-facilities.index') }}"
                    class="group rounded-3xl border border-white/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Total Fasilitas
                            </p>

                            <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($summary['total']) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                Seluruh alat yang terdaftar
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:scale-105 dark:bg-blue-500/15 dark:text-blue-300">

                            <i class="fa-solid fa-toolbox"></i>
                        </span>
                    </div>
                </a>

                {{-- Kalibrasi Berlaku --}}
                <a href="{{ route('qc-facilities.index', ['calibration_status' => 'valid']) }}"
                    class="group rounded-3xl border border-white/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Kalibrasi Berlaku
                            </p>

                            <p class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($summary['valid']) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                Berlaku lebih dari 30 hari
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition group-hover:scale-105 dark:bg-emerald-500/15 dark:text-emerald-300">

                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                    </div>
                </a>

                {{-- Akan Kedaluwarsa --}}
                <a href="{{ route('qc-facilities.index', ['calibration_status' => 'expiring']) }}"
                    class="group rounded-3xl border border-white/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Akan Kedaluwarsa
                            </p>

                            <p class="mt-3 text-3xl font-bold text-amber-600 dark:text-amber-400">
                                {{ number_format($summary['expiring']) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                Tersisa maksimal 30 hari
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 transition group-hover:scale-105 dark:bg-amber-500/15 dark:text-amber-300">

                            <i class="fa-solid fa-clock"></i>
                        </span>
                    </div>
                </a>

                {{-- Kalibrasi Kedaluwarsa --}}
                <a href="{{ route('qc-facilities.index', ['calibration_status' => 'expired']) }}"
                    class="group rounded-3xl border border-white/70 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Kalibrasi Kedaluwarsa
                            </p>

                            <p class="mt-3 text-3xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($summary['expired']) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                Melewati masa berlaku
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600 transition group-hover:scale-105 dark:bg-red-500/15 dark:text-red-300">

                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- Filter --}}
            <div
                class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <form method="GET" action="{{ route('qc-facilities.index') }}"
                    class="grid gap-4 md:grid-cols-2 xl:grid-cols-12">

                    {{-- Search --}}
                    <div class="xl:col-span-4">
                        <label for="search"
                            class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                            Pencarian
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>

                            <input id="search" type="text" name="search" value="{{ $search }}"
                                placeholder="Nama, merk, model, inventaris..."
                                class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="xl:col-span-2">
                        <label for="category_id"
                            class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                            Kategori
                        </label>

                        <select id="category_id" name="category_id"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

                            <option value="">
                                Semua Kategori
                            </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected((string) $categoryId === (string) $category->id)>

                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kondisi --}}
                    <div class="xl:col-span-2">
                        <label for="condition"
                            class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                            Kondisi
                        </label>

                        <select id="condition" name="condition"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

                            <option value="">
                                Semua Kondisi
                            </option>

                            @foreach ($conditionLabels as $value => $label)
                                <option value="{{ $value }}" @selected($condition === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Kalibrasi --}}
                    <div class="xl:col-span-2">
                        <label for="calibration_status"
                            class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">

                            Status Kalibrasi
                        </label>

                        <select id="calibration_status" name="calibration_status"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

                            <option value="">
                                Semua Status
                            </option>

                            <option value="valid" @selected($calibrationStatus === 'valid')>
                                Masih Berlaku
                            </option>

                            <option value="expiring" @selected($calibrationStatus === 'expiring')>
                                Akan Kedaluwarsa
                            </option>

                            <option value="expired" @selected($calibrationStatus === 'expired')>
                                Kedaluwarsa
                            </option>

                            <option value="not_available" @selected($calibrationStatus === 'not_available')>
                                Belum Ada Data
                            </option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-2 xl:col-span-2">
                        <button type="submit"
                            class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                            <i class="fa-solid fa-filter mr-2"></i>

                            Filter
                        </button>

                        <a href="{{ route('qc-facilities.index') }}" title="Reset filter"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">

                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>

            {{-- Active Filter Information --}}
            @if ($search || $categoryId || $condition || $calibrationStatus)
                <div
                    class="flex flex-col gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-filter mt-0.5"></i>

                        <div>
                            <p class="font-semibold">
                                Filter sedang diterapkan
                            </p>

                            <p class="mt-1 text-xs opacity-80">
                                Ditemukan {{ number_format($facilities->total()) }} fasilitas yang sesuai.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('qc-facilities.index') }}" class="text-xs font-bold hover:underline">
                        Hapus seluruh filter
                    </a>
                </div>
            @endif

            {{-- Section Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        Daftar Fasilitas
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $facilities->firstItem() ?? 0 }}–{{ $facilities->lastItem() ?? 0 }}
                        dari {{ number_format($facilities->total()) }} fasilitas.
                    </p>
                </div>
            </div>

            {{-- Facility Cards --}}
            @if ($facilities->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 2xl:grid-cols-3">
                    @foreach ($facilities as $facility)
                        @php
                            $specificationLines = collect(
                                preg_split(
                                    '/\r\n|\r|\n/',
                                    trim((string) $facility->technical_specifications),
                                ),
                            )
                                ->map(fn($line) => trim($line))
                                ->filter()
                                ->values();

                            $specificationPreview = $specificationLines->take(7);

                            $remainingSpecifications = max(
                                $specificationLines->count() - $specificationPreview->count(),
                                0,
                            );
                        @endphp

                        <article
                            class="group flex h-full flex-col overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl dark:border-gray-800 dark:bg-gray-900">

                            {{-- Image --}}
                            <a href="{{ route('qc-facilities.show', $facility) }}"
                                class="relative block aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-gray-800">

                                @if ($facility->photo_url)
                                    <img src="{{ $facility->photo_url }}" alt="{{ $facility->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-slate-100 via-blue-50 to-cyan-50 text-slate-400 dark:from-gray-800 dark:via-gray-800 dark:to-slate-900 dark:text-gray-500">

                                        <span
                                            class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white/80 text-2xl shadow-sm ring-1 ring-slate-200 dark:bg-white/10 dark:ring-white/10">

                                            <i class="fa-solid fa-screwdriver-wrench"></i>
                                        </span>

                                        <span class="mt-3 text-xs font-semibold">
                                            Foto belum tersedia
                                        </span>
                                    </div>
                                @endif

                                {{-- Category --}}
                                <div class="absolute left-4 top-4">
                                    <span
                                        class="inline-flex rounded-full bg-white/90 px-3 py-1.5 text-xs font-bold text-blue-700 shadow-sm backdrop-blur dark:bg-gray-900/90 dark:text-blue-300">

                                        {{ $facility->category?->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </div>

                                {{-- Calibration Status --}}
                                <div class="absolute bottom-4 right-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1.5 text-[11px] font-semibold shadow-sm ring-1 ring-inset backdrop-blur {{ $calibrationBadgeClasses[$facility->calibration_status] ?? $calibrationBadgeClasses['not_available'] }}">

                                        <i class="fa-solid fa-calendar-check mr-1.5"></i>

                                        {{ $facility->calibration_status_label }}
                                    </span>
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="flex flex-1 flex-col p-5">

                                {{-- Title --}}
                                <div>
                                    <a href="{{ route('qc-facilities.show', $facility) }}"
                                        class="line-clamp-2 text-lg font-extrabold leading-7 text-gray-900 transition hover:text-blue-600 dark:text-white dark:hover:text-blue-400">

                                        {{ $facility->name }}
                                    </a>

                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        @if ($facility->brand)
                                            <span
                                                class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-white/10 dark:text-slate-300">

                                                <i class="fa-solid fa-tag mr-1.5 text-[10px]"></i>

                                                {{ $facility->brand }}
                                            </span>
                                        @endif

                                        @if ($facility->model)
                                            <span
                                                class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-white/10 dark:text-slate-300">

                                                <i class="fa-solid fa-cube mr-1.5 text-[10px]"></i>

                                                {{ $facility->model }}
                                            </span>
                                        @endif

                                        <span
                                            class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $conditionBadgeClasses[$facility->condition] ?? $conditionBadgeClasses['baik'] }}">

                                            <i class="fa-solid fa-circle mr-1.5 text-[6px]"></i>

                                            {{ $facility->condition_label }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Technical Specifications --}}
                                <div
                                    class="mt-5 rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/[0.04]">

                                    <div class="mb-3 flex items-center justify-between gap-3">
                                        <h3
                                            class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-700 dark:text-slate-200">

                                            Spesifikasi Teknis
                                        </h3>

                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm ring-1 ring-slate-200 dark:bg-white/10 dark:text-blue-300 dark:ring-white/10">

                                            <i class="fa-solid fa-list-check text-xs"></i>
                                        </span>
                                    </div>

                                    @if ($specificationPreview->isNotEmpty())
                                        <dl class="space-y-2.5">
                                            @foreach ($specificationPreview as $specificationLine)
                                                @php
                                                    $specificationParts = explode(
                                                        ':',
                                                        $specificationLine,
                                                        2,
                                                    );

                                                    $specificationLabel = trim(
                                                        $specificationParts[0] ?? '',
                                                    );

                                                    $specificationValue = trim(
                                                        $specificationParts[1] ?? '',
                                                    );
                                                @endphp

                                                @if ($specificationValue !== '')
                                                    <div
                                                        class="grid grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] gap-3 text-xs leading-5">

                                                        <dt class="font-semibold text-slate-500 dark:text-slate-400">
                                                            {{ $specificationLabel }}
                                                        </dt>

                                                        <dd
                                                            class="break-words font-medium text-slate-800 dark:text-slate-200">
                                                            {{ $specificationValue }}
                                                        </dd>
                                                    </div>
                                                @else
                                                    <div class="flex items-start gap-2 text-xs leading-5">
                                                        <span
                                                            class="mt-2 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-blue-500">
                                                        </span>

                                                        <p class="text-slate-700 dark:text-slate-300">
                                                            {{ $specificationLine }}
                                                        </p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </dl>

                                        @if ($remainingSpecifications > 0)
                                            <div class="mt-3 border-t border-slate-200 pt-3 dark:border-white/10">
                                                <a href="{{ route('qc-facilities.show', $facility) }}"
                                                    class="inline-flex items-center text-xs font-bold text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">

                                                    Lihat {{ $remainingSpecifications }} spesifikasi lainnya

                                                    <i class="fa-solid fa-arrow-right ml-2"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            class="rounded-xl border border-dashed border-slate-300 px-4 py-5 text-center dark:border-gray-700">

                                            <i
                                                class="fa-solid fa-clipboard-list text-lg text-slate-300 dark:text-gray-600">
                                            </i>

                                            <p class="mt-2 text-xs text-slate-400 dark:text-gray-500">
                                                Spesifikasi teknis belum tersedia.
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Additional Information --}}
                                <div class="mt-5 grid grid-cols-2 gap-3">

                                    {{-- Nomor Inventaris --}}
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-white/[0.04]">
                                        <p
                                            class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">

                                            Nomor Inventaris
                                        </p>

                                        <p
                                            class="mt-1 truncate text-xs font-semibold text-slate-700 dark:text-slate-200">

                                            {{ $facility->inventory_number ?: '-' }}
                                        </p>
                                    </div>

                                    {{-- Serial Number --}}
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-white/[0.04]">
                                        <p
                                            class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">

                                            Serial Number
                                        </p>

                                        <p
                                            class="mt-1 truncate text-xs font-semibold text-slate-700 dark:text-slate-200">

                                            {{ $facility->serial_number ?: '-' }}
                                        </p>
                                    </div>

                                    {{-- Lokasi --}}
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-white/[0.04]">
                                        <p
                                            class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">

                                            Lokasi
                                        </p>

                                        <p
                                            class="mt-1 truncate text-xs font-semibold text-slate-700 dark:text-slate-200">

                                            {{ $facility->location ?: '-' }}
                                        </p>
                                    </div>

                                    {{-- Masa Berlaku Kalibrasi --}}
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-white/[0.04]">
                                        <p
                                            class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">

                                            Berlaku Kalibrasi
                                        </p>

                                        <p
                                            class="mt-1 truncate text-xs font-semibold text-slate-700 dark:text-slate-200">

                                            {{ $facility->calibration_valid_until?->translatedFormat('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div
                                    class="mt-auto flex items-center gap-2 border-t border-gray-100 pt-5 dark:border-gray-800">

                                    <a href="{{ route('qc-facilities.show', $facility) }}"
                                        class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700">

                                        <i class="fa-solid fa-eye mr-2"></i>

                                        Lihat Detail
                                    </a>

                                    @if ($canManage)
                                        <a href="{{ route('qc-facilities.edit', $facility) }}"
                                            title="Edit fasilitas"
                                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-50 hover:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-blue-300">

                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form method="POST"
                                            action="{{ route('qc-facilities.destroy', $facility) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" title="Hapus fasilitas"
                                                class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-semibold text-red-600 transition hover:bg-red-100 hover:text-red-700 dark:border-red-900/40 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20">

                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div
                    class="rounded-2xl border border-white/70 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    {{ $facilities->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div
                    class="rounded-3xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900">

                    <span
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100 text-3xl text-slate-400 dark:bg-white/10 dark:text-gray-500">

                        <i class="fa-solid fa-toolbox"></i>
                    </span>

                    <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">
                        Data fasilitas tidak ditemukan
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500 dark:text-gray-400">
                        Belum ada fasilitas yang sesuai dengan pencarian atau filter yang diterapkan.
                    </p>

                    <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('qc-facilities.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">

                            <i class="fa-solid fa-rotate-left mr-2"></i>

                            Reset Filter
                        </a>

                        @if ($canManage)
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('qc-facility-categories.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-blue-300">

                                    <i class="fa-solid fa-folder-tree mr-2"></i>

                                    Kelola Kategori
                                </a>

                                <a href="{{ route('qc-facilities.create') }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                                    <i class="fa-solid fa-plus mr-2"></i>

                                    Tambah Fasilitas
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
