@extends('layouts.app')

@section('header')
    Daily Check QC Product Elektrik
@endsection

@section('content_width', 'w-full')

@section('content')
    @php
        $resultLabels = [
            'pending' => 'Belum Ditentukan',
            'ok' => 'OK',
            'nok' => 'NOK',
        ];

        $resultBadgeClasses = [
            'pending' => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
            'ok' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
            'nok' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
        ];

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $baseFilters = request()->except([
            'page',
            'result',
        ]);
    @endphp

    <div
        class="min-h-screen bg-slate-50 px-4 py-6
            dark:bg-gray-950 sm:px-6 lg:px-8"
    >
        <div class="mx-auto w-full max-w-full space-y-6">

            {{-- Header --}}
            <div
                class="relative overflow-hidden rounded-3xl border
                    border-white/70 bg-white p-6 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="absolute -right-20 -top-20 h-56 w-56
                        rounded-full bg-indigo-500/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-24 left-10 h-56 w-56
                        rounded-full bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-5
                        xl:flex-row xl:items-end xl:justify-between"
                >
                    <div>
                        <p
                            class="text-xs font-extrabold uppercase
                                tracking-[0.18em] text-indigo-600
                                dark:text-indigo-400"
                        >
                            Monitoring QC · QC Product Elektrik
                        </p>

                        <h1
                            class="mt-2 text-2xl font-bold tracking-tight
                                text-gray-900 dark:text-white"
                        >
                            Daily Check
                        </h1>

                        <p
                            class="mt-1 max-w-3xl text-sm leading-6
                                text-gray-500 dark:text-gray-400"
                        >
                            Data pemeriksaan harian QC Product Elektrik
                            sebagai sumber utama dashboard Monitoring QC.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        {{--
                            Tombol dashboard kita aktifkan setelah
                            route dashboard dibuat pada tahap berikutnya.
                        --}}
                        <a
                            href="{{ route(
                                'monitoring-qc.product-electrical.dashboard'
                            ) }}"
                            class="inline-flex items-center justify-center rounded-xl
                                border border-gray-200 bg-white px-4 py-2.5
                                text-sm font-semibold text-gray-700 shadow-sm
                                transition hover:bg-gray-50 hover:text-indigo-600
                                dark:border-gray-700 dark:bg-gray-800
                                dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-chart-line mr-2"></i>
                            Dashboard
                        </a>

                        <a
                            href="{{ route(
                                'monitoring-qc.product-electrical.daily-check.create'
                            ) }}"
                            class="inline-flex items-center justify-center
                                rounded-xl bg-indigo-600 px-4 py-2.5
                                text-sm font-semibold text-white shadow-sm
                                transition hover:bg-indigo-700"
                        >
                            <i class="fa-solid fa-plus mr-2"></i>
                            Tambah Daily Check
                        </a>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div
                    class="flex items-start gap-3 rounded-2xl border
                        border-emerald-200 bg-emerald-50 px-5 py-4
                        text-sm text-emerald-700
                        dark:border-emerald-900/40 dark:bg-emerald-900/20
                        dark:text-emerald-300"
                >
                    <i class="fa-solid fa-circle-check mt-0.5"></i>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="flex items-start gap-3 rounded-2xl border
                        border-red-200 bg-red-50 px-5 py-4
                        text-sm text-red-700
                        dark:border-red-900/40 dark:bg-red-900/20
                        dark:text-red-300"
                >
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                    <span>
                        {{ session('error') }}
                    </span>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">

                {{-- Total --}}
                <a
                    href="{{ route(
                        'monitoring-qc.product-electrical.daily-check.index',
                        $baseFilters
                    ) }}"
                    class="group rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-sm font-medium text-gray-500
                                    dark:text-gray-400"
                            >
                                Total Check
                            </p>

                            <p
                                class="mt-3 text-3xl font-bold
                                    text-gray-900 dark:text-white"
                            >
                                {{ number_format($summary['total']) }}
                            </p>

                            <p
                                class="mt-1 text-xs text-gray-400
                                    dark:text-gray-500"
                            >
                                Sesuai filter aktif
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-indigo-50 text-indigo-600
                                transition group-hover:scale-105
                                dark:bg-indigo-500/15 dark:text-indigo-300"
                        >
                            <i class="fa-solid fa-clipboard-check"></i>
                        </span>
                    </div>
                </a>

                {{-- Open --}}
                <a
                    href="{{ route(
                        'monitoring-qc.product-electrical.daily-check.index',
                        array_merge($baseFilters, ['result' => 'nok'])
                    ) }}"
                    class="group rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-sm font-medium text-gray-500
                                    dark:text-gray-400"
                            >
                                Open
                            </p>

                            <p
                                class="mt-3 text-3xl font-bold
                                    text-red-600 dark:text-red-400"
                            >
                                {{ number_format($summary['open']) }}
                            </p>

                            <p
                                class="mt-1 text-xs text-gray-400
                                    dark:text-gray-500"
                            >
                                Result NOK
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-red-50 text-red-600
                                transition group-hover:scale-105
                                dark:bg-red-500/15 dark:text-red-300"
                        >
                            <i class="fa-solid fa-circle-xmark"></i>
                        </span>
                    </div>
                </a>

                {{-- Close --}}
                <a
                    href="{{ route(
                        'monitoring-qc.product-electrical.daily-check.index',
                        array_merge($baseFilters, ['result' => 'ok'])
                    ) }}"
                    class="group rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-sm font-medium text-gray-500
                                    dark:text-gray-400"
                            >
                                Close
                            </p>

                            <p
                                class="mt-3 text-3xl font-bold
                                    text-emerald-600 dark:text-emerald-400"
                            >
                                {{ number_format($summary['closed']) }}
                            </p>

                            <p
                                class="mt-1 text-xs text-gray-400
                                    dark:text-gray-500"
                            >
                                Result OK
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-emerald-50 text-emerald-600
                                transition group-hover:scale-105
                                dark:bg-emerald-500/15 dark:text-emerald-300"
                        >
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                    </div>
                </a>

                {{-- Pending --}}
                <a
                    href="{{ route(
                        'monitoring-qc.product-electrical.daily-check.index',
                        array_merge($baseFilters, ['result' => 'pending'])
                    ) }}"
                    class="group rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-sm font-medium text-gray-500
                                    dark:text-gray-400"
                            >
                                Pending
                            </p>

                            <p
                                class="mt-3 text-3xl font-bold
                                    text-amber-600 dark:text-amber-400"
                            >
                                {{ number_format($summary['pending']) }}
                            </p>

                            <p
                                class="mt-1 text-xs text-gray-400
                                    dark:text-gray-500"
                            >
                                Belum ditentukan
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-amber-50 text-amber-600
                                transition group-hover:scale-105
                                dark:bg-amber-500/15 dark:text-amber-300"
                        >
                            <i class="fa-solid fa-clock"></i>
                        </span>
                    </div>
                </a>

                {{-- Total Temuan --}}
                <div
                    class="rounded-3xl border border-white/70 bg-white
                        p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-sm font-medium text-gray-500
                                    dark:text-gray-400"
                            >
                                Total Temuan
                            </p>

                            <p
                                class="mt-3 text-3xl font-bold
                                    text-violet-600 dark:text-violet-400"
                            >
                                {{ number_format($summary['total_findings']) }}
                            </p>

                            <p
                                class="mt-1 text-xs text-gray-400
                                    dark:text-gray-500"
                            >
                                VT + SK + CR + MK + BT + FT
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-violet-50 text-violet-600
                                dark:bg-violet-500/15 dark:text-violet-300"
                        >
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div
                x-data="{ showAdvanced: false }"
                class="rounded-3xl border border-white/70 bg-white
                    p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <form
                    method="GET"
                    action="{{ route(
                        'monitoring-qc.product-electrical.daily-check.index'
                    ) }}"
                >
                    {{-- Main Filters --}}
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-12">

                        {{-- Search --}}
                        <div class="xl:col-span-4">
                            <label
                                for="search"
                                class="mb-2 block text-xs font-bold uppercase
                                    tracking-wide text-gray-500
                                    dark:text-gray-400"
                            >
                                Pencarian
                            </label>

                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute inset-y-0
                                        left-0 flex items-center pl-3
                                        text-gray-400"
                                >
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>

                                <input
                                    id="search"
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Produk, proyek, batch, OIL, NCR..."
                                    class="w-full rounded-xl border border-gray-200
                                        bg-white py-2.5 pl-10 pr-3 text-sm
                                        text-gray-700 shadow-sm transition
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                            </div>
                        </div>

                        {{-- Year --}}
                        <div class="xl:col-span-2">
                            <label
                                for="year"
                                class="mb-2 block text-xs font-bold uppercase
                                    tracking-wide text-gray-500
                                    dark:text-gray-400"
                            >
                                Tahun
                            </label>

                            <select
                                id="year"
                                name="year"
                                class="w-full rounded-xl border border-gray-200
                                    bg-white px-3 py-2.5 text-sm text-gray-700
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500
                                    dark:border-gray-700 dark:bg-gray-800
                                    dark:text-gray-100"
                            >
                                <option value="">
                                    Semua Tahun
                                </option>

                                @foreach ($years as $year)
                                    <option
                                        value="{{ $year }}"
                                        @selected(
                                            (string) request('year') ===
                                            (string) $year
                                        )
                                    >
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Month --}}
                        <div class="xl:col-span-2">
                            <label
                                for="month"
                                class="mb-2 block text-xs font-bold uppercase
                                    tracking-wide text-gray-500
                                    dark:text-gray-400"
                            >
                                Bulan
                            </label>

                            <select
                                id="month"
                                name="month"
                                class="w-full rounded-xl border border-gray-200
                                    bg-white px-3 py-2.5 text-sm text-gray-700
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500
                                    dark:border-gray-700 dark:bg-gray-800
                                    dark:text-gray-100"
                            >
                                <option value="">
                                    Semua Bulan
                                </option>

                                @foreach ($months as $number => $monthLabel)
                                    <option
                                        value="{{ $number }}"
                                        @selected(
                                            (string) request('month') ===
                                            (string) $number
                                        )
                                    >
                                        {{ $monthLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Result --}}
                        <div class="xl:col-span-2">
                            <label
                                for="result"
                                class="mb-2 block text-xs font-bold uppercase
                                    tracking-wide text-gray-500
                                    dark:text-gray-400"
                            >
                                Result
                            </label>

                            <select
                                id="result"
                                name="result"
                                class="w-full rounded-xl border border-gray-200
                                    bg-white px-3 py-2.5 text-sm text-gray-700
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500
                                    dark:border-gray-700 dark:bg-gray-800
                                    dark:text-gray-100"
                            >
                                <option value="">
                                    Semua Result
                                </option>

                                @foreach ($resultLabels as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected(request('result') === $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-end gap-2 xl:col-span-2">
                            <button
                                type="submit"
                                class="inline-flex flex-1 items-center
                                    justify-center rounded-xl bg-indigo-600
                                    px-4 py-2.5 text-sm font-semibold
                                    text-white shadow-sm transition
                                    hover:bg-indigo-700"
                            >
                                <i class="fa-solid fa-filter mr-2"></i>
                                Filter
                            </button>

                            <a
                                href="{{ route(
                                    'monitoring-qc.product-electrical.daily-check.index'
                                ) }}"
                                title="Reset filter"
                                class="inline-flex items-center justify-center
                                    rounded-xl border border-gray-200 bg-white
                                    px-3.5 py-2.5 text-sm font-semibold
                                    text-gray-600 shadow-sm transition
                                    hover:bg-gray-50 hover:text-red-600
                                    dark:border-gray-700 dark:bg-gray-800
                                    dark:text-gray-300 dark:hover:bg-gray-700"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Advanced Toggle --}}
                    <div
                        class="mt-4 border-t border-gray-100 pt-4
                            dark:border-gray-800"
                    >
                        <button
                            type="button"
                            @click="showAdvanced = !showAdvanced"
                            class="inline-flex items-center text-xs font-bold
                                text-indigo-600 transition hover:text-indigo-700
                                dark:text-indigo-400"
                        >
                            <i class="fa-solid fa-sliders mr-2"></i>

                            Filter Lanjutan

                            <i
                                class="fa-solid fa-chevron-down ml-2
                                    transition-transform duration-200"
                                :class="showAdvanced ? 'rotate-180' : ''"
                            ></i>
                        </button>

                        <div
                            x-cloak
                            x-show="showAdvanced"
                            x-transition
                            class="mt-4 grid gap-4
                                md:grid-cols-2 xl:grid-cols-4"
                        >
                            {{-- Date From --}}
                            <div>
                                <label
                                    for="date_from"
                                    class="mb-2 block text-xs font-bold uppercase
                                        tracking-wide text-gray-500
                                        dark:text-gray-400"
                                >
                                    Tanggal Mulai
                                </label>

                                <input
                                    id="date_from"
                                    type="date"
                                    name="date_from"
                                    value="{{ request('date_from') }}"
                                    class="w-full rounded-xl border
                                        border-gray-200 bg-white px-3 py-2.5
                                        text-sm text-gray-700 shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                            </div>

                            {{-- Date To --}}
                            <div>
                                <label
                                    for="date_to"
                                    class="mb-2 block text-xs font-bold uppercase
                                        tracking-wide text-gray-500
                                        dark:text-gray-400"
                                >
                                    Tanggal Akhir
                                </label>

                                <input
                                    id="date_to"
                                    type="date"
                                    name="date_to"
                                    value="{{ request('date_to') }}"
                                    class="w-full rounded-xl border
                                        border-gray-200 bg-white px-3 py-2.5
                                        text-sm text-gray-700 shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                            </div>

                            {{-- Project --}}
                            <div>
                                <label
                                    for="project_id"
                                    class="mb-2 block text-xs font-bold uppercase
                                        tracking-wide text-gray-500
                                        dark:text-gray-400"
                                >
                                    Proyek
                                </label>

                                <select
                                    id="project_id"
                                    name="project_id"
                                    class="w-full rounded-xl border
                                        border-gray-200 bg-white px-3 py-2.5
                                        text-sm text-gray-700 shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                                    <option value="">
                                        Semua Proyek
                                    </option>

                                    @foreach ($projects as $project)
                                        <option
                                            value="{{ $project->id }}"
                                            @selected(
                                                (string) request('project_id') ===
                                                (string) $project->id
                                            )
                                        >
                                            {{ $project->kode_proyek }}
                                            —
                                            {{ $project->nama_proyek }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Inspection Gate --}}
                            <div>
                                <label
                                    for="inspection_gate"
                                    class="mb-2 block text-xs font-bold uppercase
                                        tracking-wide text-gray-500
                                        dark:text-gray-400"
                                >
                                    Inspection Gate
                                </label>

                                <select
                                    id="inspection_gate"
                                    name="inspection_gate"
                                    class="w-full rounded-xl border
                                        border-gray-200 bg-white px-3 py-2.5
                                        text-sm text-gray-700 shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                                    <option value="">
                                        Semua Lokasi
                                    </option>

                                    @foreach ($inspectionGates as $gate)
                                        <option
                                            value="{{ $gate }}"
                                            @selected(
                                                request('inspection_gate') === $gate
                                            )
                                        >
                                            {{ $gate }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Check Category --}}
                            <div>
                                <label
                                    for="check_category"
                                    class="mb-2 block text-xs font-bold uppercase
                                        tracking-wide text-gray-500
                                        dark:text-gray-400"
                                >
                                    Check Category
                                </label>

                                <select
                                    id="check_category"
                                    name="check_category"
                                    class="w-full rounded-xl border
                                        border-gray-200 bg-white px-3 py-2.5
                                        text-sm text-gray-700 shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                                    <option value="">
                                        Semua Kategori
                                    </option>

                                    @foreach ($checkCategories as $category)
                                        <option
                                            value="{{ $category }}"
                                            @selected(
                                                request('check_category') ===
                                                $category
                                            )
                                        >
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Inspector --}}
                            <div>
                                <label
                                    for="inspector"
                                    class="mb-2 block text-xs font-bold uppercase
                                        tracking-wide text-gray-500
                                        dark:text-gray-400"
                                >
                                    Inspector
                                </label>

                                <select
                                    id="inspector"
                                    name="inspector"
                                    class="w-full rounded-xl border
                                        border-gray-200 bg-white px-3 py-2.5
                                        text-sm text-gray-700 shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                        dark:border-gray-700 dark:bg-gray-800
                                        dark:text-gray-100"
                                >
                                    <option value="">
                                        Semua Inspector
                                    </option>

                                    @foreach ($inspectors as $inspector)
                                        <option
                                            value="{{ $inspector }}"
                                            @selected(
                                                request('inspector') === $inspector
                                            )
                                        >
                                            {{ $inspector }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div
                class="overflow-hidden rounded-3xl border border-white/70
                    bg-white shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                {{-- Table Header --}}
                <div
                    class="flex flex-col gap-3 border-b border-gray-100
                        px-5 py-4 sm:flex-row sm:items-center
                        sm:justify-between dark:border-gray-800"
                >
                    <div>
                        <h2
                            class="font-bold text-gray-900 dark:text-white"
                        >
                            Detail Daily Check
                        </h2>

                        <p
                            class="mt-1 text-xs text-gray-500
                                dark:text-gray-400"
                        >
                            Menampilkan
                            {{ number_format($dailyChecks->firstItem() ?? 0) }}
                            –
                            {{ number_format($dailyChecks->lastItem() ?? 0) }}
                            dari
                            {{ number_format($dailyChecks->total()) }}
                            data.
                        </p>
                    </div>

                    <span
                        class="inline-flex w-fit items-center rounded-xl
                            bg-slate-100 px-3 py-2 text-xs font-semibold
                            text-slate-600
                            dark:bg-white/10 dark:text-slate-300"
                    >
                        <i class="fa-solid fa-table-list mr-2"></i>
                        {{ number_format($dailyChecks->total()) }} Record
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-fixed">
                        <thead>
                            <tr
                                class="bg-slate-50 text-left
                                    dark:bg-gray-800/60"
                            >
                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Tanggal
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Proyek
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Produk
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Gate
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Category
                                </th>

                                <th
                                    class="px-5 py-3 text-center text-xs
                                        font-bold uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Temuan
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Produk
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Kabel
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Status
                                </th>

                                <th
                                    class="px-5 py-3 text-xs font-bold
                                        uppercase tracking-wider
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Inspector
                                </th>

                                <th
                                    class="sticky right-0 bg-slate-50
                                        px-5 py-3 text-right text-xs
                                        font-bold uppercase tracking-wider
                                        text-gray-500
                                        dark:bg-gray-800 dark:text-gray-400"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100
                                dark:divide-gray-800"
                        >
                            @forelse ($dailyChecks as $dailyCheck)
                                @php
                                    $status = $dailyCheck->status_product;

                                    $statusLabel = match ($status) {
                                        'open' => 'Open',
                                        'close' => 'Close',
                                        default => 'Pending',
                                    };

                                    $statusClass = match ($status) {
                                        'open' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
                                        'close' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
                                        default => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
                                    };
                                @endphp

                                <tr
                                    class="group transition
                                        hover:bg-slate-50/80
                                        dark:hover:bg-white/[0.03]"
                                >
                                    {{-- Date --}}
                                    <td
                                        class="whitespace-nowrap px-5 py-4
                                            align-top"
                                    >
                                        <p
                                            class="text-sm font-bold
                                                text-gray-900
                                                dark:text-gray-100"
                                        >
                                            {{ $dailyCheck->check_date->format('d/m/Y') }}
                                        </p>

                                        <p
                                            class="mt-1 text-xs text-gray-400"
                                        >
                                            {{ $dailyCheck->document_check }}
                                        </p>
                                    </td>

                                    {{-- Project --}}
                                    <td class="px-5 py-4 align-top">
                                        <p
                                            class="max-w-[220px] truncate
                                                text-sm font-semibold
                                                text-gray-800
                                                dark:text-gray-200"
                                            title="{{ $dailyCheck->project_name }}"
                                        >
                                            {{ $dailyCheck->project_name }}
                                        </p>

                                        @if ($dailyCheck->project?->kode_proyek)
                                            <p
                                                class="mt-1 text-xs
                                                    text-gray-400"
                                            >
                                                {{ $dailyCheck->project->kode_proyek }}
                                            </p>
                                        @endif
                                    </td>

                                    {{-- Product --}}
                                    <td class="px-5 py-4 align-top">
                                        <a
                                            href="{{ route(
                                                'monitoring-qc.product-electrical.daily-check.show',
                                                $dailyCheck
                                            ) }}"
                                            class="block max-w-[260px]
                                                truncate text-sm font-bold
                                                text-indigo-600
                                                hover:text-indigo-700
                                                dark:text-indigo-400
                                                dark:hover:text-indigo-300"
                                            title="{{ $dailyCheck->product_name }}"
                                        >
                                            {{ $dailyCheck->product_name }}
                                        </a>

                                        <p
                                            class="mt-1 max-w-[260px]
                                                truncate text-xs
                                                text-gray-400"
                                            title="{{ $dailyCheck->batch_reference }}"
                                        >
                                            {{ $dailyCheck->batch_reference ?: '-' }}
                                        </p>
                                    </td>

                                    {{-- Gate --}}
                                    <td class="px-5 py-4 align-top">
                                        <span
                                            class="inline-flex rounded-lg
                                                bg-blue-50 px-2.5 py-1
                                                text-xs font-semibold
                                                text-blue-700
                                                dark:bg-blue-500/15
                                                dark:text-blue-300"
                                        >
                                            {{ $dailyCheck->inspection_gate }}
                                        </span>
                                    </td>

                                    {{-- Category --}}
                                    <td class="px-5 py-4 align-top">
                                        <p
                                            class="text-sm text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{ $dailyCheck->check_category }}
                                        </p>
                                    </td>

                                    {{-- Findings --}}
                                    <td
                                        class="px-5 py-4 text-center
                                            align-top"
                                    >
                                        @if ($dailyCheck->total_findings > 0)
                                            <span
                                                class="inline-flex min-w-9
                                                    items-center justify-center
                                                    rounded-xl bg-amber-50
                                                    px-2.5 py-1.5 text-sm
                                                    font-black text-amber-700
                                                    dark:bg-amber-500/15
                                                    dark:text-amber-300"
                                            >
                                                {{ number_format(
                                                    $dailyCheck->total_findings
                                                ) }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex min-w-9
                                                    items-center justify-center
                                                    rounded-xl bg-slate-100
                                                    px-2.5 py-1.5 text-sm
                                                    font-bold text-slate-400
                                                    dark:bg-white/10"
                                            >
                                                0
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Product Qty --}}
                                    <td
                                        class="whitespace-nowrap px-5 py-4
                                            align-top"
                                    >
                                        <div
                                            class="flex items-center gap-2
                                                text-xs"
                                        >
                                            <span
                                                class="font-bold
                                                    text-emerald-600
                                                    dark:text-emerald-400"
                                            >
                                                OK
                                                {{ number_format(
                                                    $dailyCheck->product_ok_qty
                                                ) }}
                                            </span>

                                            <span class="text-gray-300">
                                                /
                                            </span>

                                            <span
                                                class="font-bold
                                                    text-red-600
                                                    dark:text-red-400"
                                            >
                                                NOK
                                                {{ number_format(
                                                    $dailyCheck->product_nok_qty
                                                ) }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Cable Qty --}}
                                    <td
                                        class="whitespace-nowrap px-5 py-4
                                            align-top"
                                    >
                                        <div
                                            class="flex items-center gap-2
                                                text-xs"
                                        >
                                            <span
                                                class="font-bold
                                                    text-emerald-600
                                                    dark:text-emerald-400"
                                            >
                                                OK
                                                {{ number_format(
                                                    $dailyCheck->cable_ok_qty
                                                ) }}
                                            </span>

                                            <span class="text-gray-300">
                                                /
                                            </span>

                                            <span
                                                class="font-bold
                                                    text-red-600
                                                    dark:text-red-400"
                                            >
                                                NOK
                                                {{ number_format(
                                                    $dailyCheck->cable_nok_qty
                                                ) }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-5 py-4 align-top">
                                        <span
                                            class="inline-flex items-center
                                                rounded-full px-2.5 py-1
                                                text-xs font-bold ring-1
                                                ring-inset {{ $statusClass }}"
                                        >
                                            <i
                                                class="fa-solid
                                                    {{ $status === 'close'
                                                        ? 'fa-circle-check'
                                                        : ($status === 'open'
                                                            ? 'fa-circle-xmark'
                                                            : 'fa-clock') }}
                                                    mr-1.5"
                                            ></i>

                                            {{ $statusLabel }}
                                        </span>

                                        @if ($dailyCheck->ncr_number)
                                            <p
                                                class="mt-2 text-[11px]
                                                    font-semibold
                                                    text-red-500"
                                            >
                                                NCR:
                                                {{ $dailyCheck->ncr_number }}
                                            </p>
                                        @endif
                                    </td>

                                    {{-- Inspector --}}
                                    <td class="px-5 py-4 align-top">
                                        <p
                                            class="max-w-[180px] truncate
                                                text-sm font-medium
                                                text-gray-700
                                                dark:text-gray-300"
                                            title="{{ $dailyCheck->inspector }}"
                                        >
                                            {{ $dailyCheck->inspector }}
                                        </p>
                                    </td>

                                    {{-- Actions --}}
                                    <td
                                        class="sticky right-0 bg-white
                                            px-5 py-4 text-right align-top
                                            transition
                                            group-hover:bg-slate-50
                                            dark:bg-gray-900
                                            dark:group-hover:bg-[#111827]"
                                    >
                                        <div
                                            class="flex items-center
                                                justify-end gap-2"
                                        >
                                            <a
                                                href="{{ route(
                                                    'monitoring-qc.product-electrical.daily-check.show',
                                                    $dailyCheck
                                                ) }}"
                                                title="Detail"
                                                class="inline-flex h-9 w-9
                                                    items-center justify-center
                                                    rounded-xl border
                                                    border-gray-200 bg-white
                                                    text-gray-600 shadow-sm
                                                    transition
                                                    hover:border-indigo-200
                                                    hover:bg-indigo-50
                                                    hover:text-indigo-600
                                                    dark:border-gray-700
                                                    dark:bg-gray-800
                                                    dark:text-gray-300
                                                    dark:hover:bg-indigo-500/15
                                                    dark:hover:text-indigo-300"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a
                                                href="{{ route(
                                                    'monitoring-qc.product-electrical.daily-check.edit',
                                                    $dailyCheck
                                                ) }}"
                                                title="Edit"
                                                class="inline-flex h-9 w-9
                                                    items-center justify-center
                                                    rounded-xl border
                                                    border-gray-200 bg-white
                                                    text-gray-600 shadow-sm
                                                    transition
                                                    hover:border-amber-200
                                                    hover:bg-amber-50
                                                    hover:text-amber-600
                                                    dark:border-gray-700
                                                    dark:bg-gray-800
                                                    dark:text-gray-300
                                                    dark:hover:bg-amber-500/15
                                                    dark:hover:text-amber-300"
                                            >
                                                <i
                                                    class="fa-solid
                                                        fa-pen-to-square"
                                                ></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="11"
                                        class="px-6 py-16 text-center"
                                    >
                                        <span
                                            class="mx-auto flex h-16 w-16
                                                items-center justify-center
                                                rounded-3xl bg-slate-100
                                                text-2xl text-slate-400
                                                dark:bg-white/10
                                                dark:text-gray-500"
                                        >
                                            <i
                                                class="fa-solid
                                                    fa-clipboard-list"
                                            ></i>
                                        </span>

                                        <h3
                                            class="mt-4 font-bold
                                                text-gray-700
                                                dark:text-gray-300"
                                        >
                                            Data Daily Check belum tersedia
                                        </h3>

                                        <p
                                            class="mt-1 text-sm
                                                text-gray-400"
                                        >
                                            Tambahkan data pemeriksaan atau
                                            ubah filter pencarian.
                                        </p>

                                        <a
                                            href="{{ route(
                                                'monitoring-qc.product-electrical.daily-check.create'
                                            ) }}"
                                            class="mt-5 inline-flex
                                                items-center rounded-xl
                                                bg-indigo-600 px-4 py-2.5
                                                text-sm font-semibold
                                                text-white transition
                                                hover:bg-indigo-700"
                                        >
                                            <i
                                                class="fa-solid fa-plus
                                                    mr-2"
                                            ></i>
                                            Tambah Daily Check
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($dailyChecks->hasPages())
                    <div
                        class="border-t border-gray-100 px-5 py-4
                            dark:border-gray-800"
                    >
                        {{ $dailyChecks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
