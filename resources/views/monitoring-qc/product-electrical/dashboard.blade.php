@extends('layouts.app')

@section('header')
    Dashboard QC Product Elektrik
@endsection

@section('content_width', 'w-full')

@section('content')
    @php
        $topFindingTabs = [
            'visual' => [
                'label' => 'Visual',
                'icon' => 'fa-eye',
            ],
            'skun' => [
                'label' => 'Skun',
                'icon' => 'fa-plug',
            ],
            'cramping' => [
                'label' => 'Cramping',
                'icon' => 'fa-compress',
            ],
            'marking' => [
                'label' => 'Marking',
                'icon' => 'fa-tag',
            ],
            'belltest' => [
                'label' => 'Belltest',
                'icon' => 'fa-bell',
            ],
            'function' => [
                'label' => 'Function',
                'icon' => 'fa-gears',
            ],
        ];

        $inputClass = '
            w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5
            text-sm text-gray-700 shadow-sm transition
            focus:border-indigo-500 focus:ring-indigo-500
            dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100
        ';
    @endphp

    <div
        class="min-h-screen bg-slate-50 px-3 py-5
            dark:bg-gray-950 sm:px-4 lg:px-5"
    >
        <div class="mx-auto w-full max-w-full space-y-5">

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
                            Dashboard Monitoring
                        </h1>

                        <p
                            class="mt-1 max-w-3xl text-sm leading-6
                                text-gray-500 dark:text-gray-400"
                        >
                            Monitoring hasil Daily Check, status temuan,
                            kategori defect, OK/NOK, lokasi pemeriksaan,
                            NCR, dan temuan dominan.
                        </p>
                    </div>

                    <a
                        href="{{ route(
                            'monitoring-qc.product-electrical.daily-check.index'
                        ) }}"
                        class="inline-flex items-center justify-center
                            rounded-xl border border-gray-200 bg-white
                            px-4 py-2.5 text-sm font-semibold
                            text-gray-700 shadow-sm transition
                            hover:bg-gray-50 hover:text-indigo-600
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        <i class="fa-solid fa-table-list mr-2"></i>

                        Daily Check
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="mb-4 flex items-center gap-3">
                    <span
                        class="flex h-10 w-10 items-center justify-center
                            rounded-2xl bg-indigo-50 text-indigo-600
                            dark:bg-indigo-500/15 dark:text-indigo-300"
                    >
                        <i class="fa-solid fa-filter"></i>
                    </span>

                    <div>
                        <h2
                            class="font-bold text-gray-900
                                dark:text-white"
                        >
                            Filter Dashboard
                        </h2>

                        <p
                            class="mt-0.5 text-xs text-gray-500
                                dark:text-gray-400"
                        >
                            Seluruh grafik mengikuti filter berikut.
                        </p>
                    </div>
                </div>

                <form
                    method="GET"
                    action="{{ route(
                        'monitoring-qc.product-electrical.dashboard'
                    ) }}"
                    class="grid gap-4 md:grid-cols-2 xl:grid-cols-7"
                >
                    {{-- Tahun --}}
                    <div>
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
                            class="{{ $inputClass }}"
                        >
                            @if ($availableYears->isEmpty())
                                <option value="{{ now()->year }}">
                                    {{ now()->year }}
                                </option>
                            @else
                                @foreach ($availableYears as $year)
                                    <option
                                        value="{{ $year }}"
                                        @selected(
                                            (int) $selectedYear === (int) $year
                                        )
                                    >
                                        {{ $year }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Month From --}}
                    <div>
                        <label
                            for="month_from"
                            class="mb-2 block text-xs font-bold uppercase
                                tracking-wide text-gray-500
                                dark:text-gray-400"
                        >
                            Bulan Mulai
                        </label>

                        <select
                            id="month_from"
                            name="month_from"
                            class="{{ $inputClass }}"
                        >
                            <option value="">
                                Januari
                            </option>

                            @foreach ($months as $number => $label)
                                <option
                                    value="{{ $number }}"
                                    @selected(
                                        (string) request('month_from')
                                        === (string) $number
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Month To --}}
                    <div>
                        <label
                            for="month_to"
                            class="mb-2 block text-xs font-bold uppercase
                                tracking-wide text-gray-500
                                dark:text-gray-400"
                        >
                            Bulan Akhir
                        </label>

                        <select
                            id="month_to"
                            name="month_to"
                            class="{{ $inputClass }}"
                        >
                            <option value="">
                                Desember
                            </option>

                            @foreach ($months as $number => $label)
                                <option
                                    value="{{ $number }}"
                                    @selected(
                                        (string) request('month_to')
                                        === (string) $number
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
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
                            class="{{ $inputClass }}"
                        >
                            <option value="">
                                Semua Proyek
                            </option>

                            @foreach ($projects as $project)
                                <option
                                    value="{{ $project->id }}"
                                    @selected(
                                        (string) request('project_id')
                                        === (string) $project->id
                                    )
                                >
                                    {{ $project->kode_proyek }}
                                    —
                                    {{ $project->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Gate --}}
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
                            class="{{ $inputClass }}"
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

                    {{-- Category --}}
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
                            class="{{ $inputClass }}"
                        >
                            <option value="">
                                Semua Kategori
                            </option>

                            @foreach ($checkCategories as $category)
                                <option
                                    value="{{ $category }}"
                                    @selected(
                                        request('check_category')
                                        === $category
                                    )
                                >
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action --}}
                    <div class="flex items-end gap-2">
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
                                'monitoring-qc.product-electrical.dashboard'
                            ) }}"
                            title="Reset filter"
                            class="inline-flex items-center justify-center
                                rounded-xl border border-gray-200
                                bg-white px-3.5 py-2.5 text-gray-600
                                shadow-sm transition hover:bg-gray-50
                                hover:text-red-600
                                dark:border-gray-700 dark:bg-gray-800
                                dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>

            {{-- Summary --}}
            <div
                class="grid gap-4 sm:grid-cols-2
                    lg:grid-cols-4 2xl:grid-cols-8"
            >
                {{-- Total Check --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-blue-50 text-blue-600
                            dark:bg-blue-500/15 dark:text-blue-300"
                    >
                        <i class="fa-solid fa-clipboard-check"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-gray-900 dark:text-white"
                    >
                        {{ number_format($summary['total_check']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Total Check
                    </p>
                </div>

                {{-- Open --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-red-50 text-red-600
                            dark:bg-red-500/15 dark:text-red-300"
                    >
                        <i class="fa-solid fa-circle-xmark"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-red-600 dark:text-red-400"
                    >
                        {{ number_format($summary['open']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Open
                    </p>
                </div>

                {{-- Close --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-emerald-50 text-emerald-600
                            dark:bg-emerald-500/15 dark:text-emerald-300"
                    >
                        <i class="fa-solid fa-circle-check"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-emerald-600 dark:text-emerald-400"
                    >
                        {{ number_format($summary['close']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Close
                    </p>
                </div>

                {{-- Close Percentage --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-cyan-50 text-cyan-600
                            dark:bg-cyan-500/15 dark:text-cyan-300"
                    >
                        <i class="fa-solid fa-percent"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-cyan-600 dark:text-cyan-400"
                    >
                        {{ number_format(
                            $summary['close_percentage'],
                            2
                        ) }}%
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Penyelesaian
                    </p>
                </div>

                {{-- Findings --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-amber-50 text-amber-600
                            dark:bg-amber-500/15 dark:text-amber-300"
                    >
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-amber-600 dark:text-amber-400"
                    >
                        {{ number_format($summary['total_findings']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Total Temuan
                    </p>
                </div>

                {{-- OK --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-green-50 text-green-600
                            dark:bg-green-500/15 dark:text-green-300"
                    >
                        <i class="fa-solid fa-check"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-green-600 dark:text-green-400"
                    >
                        {{ number_format($summary['total_ok']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        OK Kabel
                    </p>
                </div>

                {{-- NOK --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-rose-50 text-rose-600
                            dark:bg-rose-500/15 dark:text-rose-300"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-rose-600 dark:text-rose-400"
                    >
                        {{ number_format($summary['total_nok']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        NOK Kabel
                    </p>
                </div>

                {{-- NCR --}}
                <div
                    class="rounded-3xl border border-white/70
                        bg-white p-5 shadow-sm
                        dark:border-gray-800 dark:bg-gray-900"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-violet-50 text-violet-600
                            dark:bg-violet-500/15 dark:text-violet-300"
                    >
                        <i class="fa-solid fa-file-circle-exclamation"></i>
                    </span>

                    <p
                        class="mt-4 text-3xl font-black
                            text-violet-600 dark:text-violet-400"
                    >
                        {{ number_format($summary['total_ncr']) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        NCR
                    </p>
                </div>
            </div>

            {{-- Status Temuan --}}
            <section
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                                tracking-[0.15em] text-amber-500"
                        >
                            Status Temuan
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                text-gray-900 dark:text-white"
                        >
                            Open, Close & Total Check
                        </h2>
                    </div>

                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-amber-50 text-amber-600
                            dark:bg-amber-500/15 dark:text-amber-300"
                    >
                        <i class="fa-solid fa-chart-column"></i>
                    </span>
                </div>

                <div
                    class="mt-6 grid gap-6
                        xl:grid-cols-[minmax(0,4fr)_minmax(260px,1fr)]"
                >
                    <div class="h-[360px] min-w-0">
                        <canvas id="statusChart"></canvas>
                    </div>

                    <div
                        class="overflow-hidden rounded-2xl border
                            border-gray-100 dark:border-gray-800"
                    >
                        <div
                            class="grid grid-cols-[1fr_100px]
                                bg-indigo-600 px-4 py-3
                                text-xs font-bold text-white"
                        >
                            <span>Periode</span>
                            <span class="text-right">
                                Persentase
                            </span>
                        </div>

                        <div
                            class="max-h-[315px] overflow-y-auto
                                divide-y divide-gray-100
                                dark:divide-gray-800"
                        >
                            @foreach ($months as $monthNumber => $monthLabel)
                                @php
                                    $index = $monthNumber - 1;

                                    $openMonth =
                                        (int) ($chart['status']['open'][$index] ?? 0);

                                    $closeMonth =
                                        (int) ($chart['status']['close'][$index] ?? 0);

                                    $statusTotal =
                                        $openMonth + $closeMonth;

                                    $percentage =
                                        $statusTotal > 0
                                            ? round(
                                                ($closeMonth / $statusTotal) * 100,
                                                2
                                            )
                                            : 0;
                                @endphp

                                @if (
                                    (int) ($chart['status']['total'][$index] ?? 0) > 0
                                )
                                    <div
                                        class="grid grid-cols-[1fr_100px]
                                            px-4 py-3 text-sm"
                                    >
                                        <span
                                            class="text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{ $monthLabel }},
                                            {{ $selectedYear }}
                                        </span>

                                        <span
                                            class="text-right font-bold
                                                text-gray-900
                                                dark:text-white"
                                        >
                                            {{ number_format(
                                                $percentage,
                                                2
                                            ) }}%
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- OK NOK --}}
            <section
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                                tracking-[0.15em] text-indigo-500"
                        >
                            Hasil Pemeriksaan
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                text-gray-900 dark:text-white"
                        >
                            Grafik Jumlah Total OK & NOK
                        </h2>
                    </div>

                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-indigo-50 text-indigo-600
                            dark:bg-indigo-500/15 dark:text-indigo-300"
                    >
                        <i class="fa-solid fa-chart-simple"></i>
                    </span>
                </div>

                <div class="mt-6 h-[360px] min-w-0">
                    <canvas id="okNokChart"></canvas>
                </div>
            </section>

            {{-- Location --}}
            <section
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                                tracking-[0.15em] text-amber-500"
                        >
                            Lokasi Temuan
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                text-gray-900 dark:text-white"
                        >
                            Distribusi Inspection Gate
                        </h2>
                    </div>

                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-amber-50 text-amber-600
                            dark:bg-amber-500/15 dark:text-amber-300"
                    >
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                </div>

                <div class="mt-6 h-[360px] min-w-0">
                    <canvas id="locationChart"></canvas>
                </div>
            </section>

            {{-- Finding Categories --}}
            <section
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                                tracking-[0.15em] text-cyan-500"
                        >
                            Kategori Temuan
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                text-gray-900 dark:text-white"
                        >
                            Defect per Kategori
                        </h2>
                    </div>

                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-cyan-50 text-cyan-600
                            dark:bg-cyan-500/15 dark:text-cyan-300"
                    >
                        <i class="fa-solid fa-layer-group"></i>
                    </span>
                </div>

                <div class="mt-6 h-[390px] min-w-0">
                    <canvas id="findingChart"></canvas>
                </div>
            </section>

            {{-- NCR --}}
            <section
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                                tracking-[0.15em] text-red-500"
                        >
                            Non Conformity Report
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                text-gray-900 dark:text-white"
                        >
                            Jumlah NCR Berdasarkan Kategori Defect
                        </h2>
                    </div>

                    <span
                        class="flex h-11 w-11 items-center justify-center
                            rounded-2xl bg-red-50 text-red-600
                            dark:bg-red-500/15 dark:text-red-300"
                    >
                        <i class="fa-solid fa-file-circle-exclamation"></i>
                    </span>
                </div>

                <div class="mt-6 h-[340px] min-w-0">
                    <canvas id="ncrChart"></canvas>
                </div>
            </section>

            {{-- Top Findings --}}
            <section
                x-data="{ activeTab: 'visual' }"
                class="rounded-3xl border border-white/70
                    bg-white p-5 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="flex flex-col gap-5
                        xl:flex-row xl:items-center
                        xl:justify-between"
                >
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                                tracking-[0.15em] text-violet-500"
                        >
                            Temuan Dominan
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                text-gray-900 dark:text-white"
                        >
                            Top 3 Temuan per Kategori
                        </h2>

                        <p
                            class="mt-1 text-sm text-gray-500
                                dark:text-gray-400"
                        >
                            Berdasarkan Product Name dengan jumlah
                            temuan terbesar setiap bulan.
                        </p>
                    </div>

                    <div
                        class="flex max-w-full gap-2 overflow-x-auto
                            pb-1"
                    >
                        @foreach ($topFindingTabs as $key => $tab)
                            <button
                                type="button"
                                @click="activeTab = '{{ $key }}'"
                                class="inline-flex flex-shrink-0
                                    items-center rounded-xl px-4 py-2
                                    text-sm font-semibold transition"
                                :class="activeTab === '{{ $key }}'
                                    ? 'bg-indigo-600 text-white shadow-sm'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-white/10 dark:text-slate-300 dark:hover:bg-white/15'"
                            >
                                <i
                                    class="fa-solid
                                        {{ $tab['icon'] }} mr-2"
                                ></i>

                                {{ $tab['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @foreach ($topFindingTabs as $key => $tab)
                    <div
                        x-cloak
                        x-show="activeTab === '{{ $key }}'"
                        x-transition.opacity
                        class="mt-6"
                    >
                        <div
                            class="overflow-x-auto rounded-2xl
                                border border-gray-100
                                dark:border-gray-800"
                        >
                            <table class="w-full min-w-[900px]">
                                <thead>
                                    <tr
                                        class="bg-slate-50
                                            dark:bg-gray-800/60"
                                    >
                                        <th
                                            class="px-4 py-3 text-left
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            Periode
                                        </th>

                                        <th
                                            class="px-4 py-3 text-left
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            No. 1 Temuan
                                            {{ $tab['label'] }}
                                        </th>

                                        <th
                                            class="px-4 py-3 text-center
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            Jumlah
                                        </th>

                                        <th
                                            class="px-4 py-3 text-left
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            No. 2 Temuan
                                            {{ $tab['label'] }}
                                        </th>

                                        <th
                                            class="px-4 py-3 text-center
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            Jumlah
                                        </th>

                                        <th
                                            class="px-4 py-3 text-left
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            No. 3 Temuan
                                            {{ $tab['label'] }}
                                        </th>

                                        <th
                                            class="px-4 py-3 text-center
                                                text-xs font-bold uppercase
                                                tracking-wider text-gray-500
                                                dark:text-gray-400"
                                        >
                                            Jumlah
                                        </th>
                                    </tr>
                                </thead>

                                <tbody
                                    class="divide-y divide-gray-100
                                        dark:divide-gray-800"
                                >
                                    @php
                                        $hasTopFindingData = false;
                                    @endphp

                                    @foreach ($months as $monthNumber => $monthLabel)
                                        @php
                                            $rows = $topFindings[$key]
                                                ->get(
                                                    $monthNumber,
                                                    collect()
                                                );

                                            if ($rows->isNotEmpty()) {
                                                $hasTopFindingData = true;
                                            }

                                            $rank1 = $rows->get(0);
                                            $rank2 = $rows->get(1);
                                            $rank3 = $rows->get(2);
                                        @endphp

                                        @if ($rows->isNotEmpty())
                                            <tr
                                                class="transition
                                                    hover:bg-slate-50/70
                                                    dark:hover:bg-white/[0.03]"
                                            >
                                                <td
                                                    class="whitespace-nowrap
                                                        px-4 py-3 text-sm
                                                        font-semibold
                                                        text-gray-700
                                                        dark:text-gray-300"
                                                >
                                                    {{ $monthLabel }},
                                                    {{ $selectedYear }}
                                                </td>

                                                <td
                                                    class="px-4 py-3 text-sm
                                                        text-gray-700
                                                        dark:text-gray-300"
                                                >
                                                    {{ $rank1?->product_name ?? '-' }}
                                                </td>

                                                <td
                                                    class="px-4 py-3 text-center
                                                        text-sm font-bold
                                                        text-indigo-600
                                                        dark:text-indigo-400"
                                                >
                                                    {{ $rank1
                                                        ? number_format($rank1->total)
                                                        : 0 }}
                                                </td>

                                                <td
                                                    class="px-4 py-3 text-sm
                                                        text-gray-700
                                                        dark:text-gray-300"
                                                >
                                                    {{ $rank2?->product_name ?? '-' }}
                                                </td>

                                                <td
                                                    class="px-4 py-3 text-center
                                                        text-sm font-bold
                                                        text-indigo-600
                                                        dark:text-indigo-400"
                                                >
                                                    {{ $rank2
                                                        ? number_format($rank2->total)
                                                        : 0 }}
                                                </td>

                                                <td
                                                    class="px-4 py-3 text-sm
                                                        text-gray-700
                                                        dark:text-gray-300"
                                                >
                                                    {{ $rank3?->product_name ?? '-' }}
                                                </td>

                                                <td
                                                    class="px-4 py-3 text-center
                                                        text-sm font-bold
                                                        text-indigo-600
                                                        dark:text-indigo-400"
                                                >
                                                    {{ $rank3
                                                        ? number_format($rank3->total)
                                                        : 0 }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    @if (! $hasTopFindingData)
                                        <tr>
                                            <td
                                                colspan="7"
                                                class="px-6 py-12
                                                    text-center"
                                            >
                                                <i
                                                    class="fa-solid
                                                        fa-chart-simple
                                                        text-3xl
                                                        text-gray-300
                                                        dark:text-gray-600"
                                                ></i>

                                                <p
                                                    class="mt-3 text-sm
                                                        font-semibold
                                                        text-gray-500
                                                        dark:text-gray-400"
                                                >
                                                    Belum ada temuan
                                                    {{ $tab['label'] }}
                                                    pada periode ini.
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('load', () => {
            if (typeof window.Chart === 'undefined') {
                console.error('Chart.js belum tersedia.');

                return;
            }

            const chartData = @js($chart);

            const charts = [];

            const palette = {
                red: '#ef4444',
                green: '#16a34a',
                blue: '#2563eb',
                yellow: '#eab308',
                gray: '#6b7280',
                cyan: '#06b6d4',
                amber: '#f59e0b',
                violet: '#7c3aed',
                indigo: '#4f46e5',
                rose: '#e11d48',
            };

            const locationPalette = [
                '#ef4444',
                '#facc15',
                '#2563eb',
                '#6b7280',
                '#06b6d4',
                '#7c3aed',
                '#f97316',
                '#14b8a6',
            ];

            const isDark = () =>
                document.documentElement.classList.contains('dark');

            const tickColor = () =>
                isDark() ? '#cbd5e1' : '#475569';

            const gridColor = () =>
                isDark()
                    ? 'rgba(148, 163, 184, 0.14)'
                    : 'rgba(148, 163, 184, 0.24)';

            const baseOptions = () => ({
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10,
                            boxHeight: 10,
                            padding: 18,
                            color: tickColor(),
                            font: {
                                size: 12,
                                weight: '600',
                            },
                        },
                    },
                    tooltip: {
                        padding: 12,
                        cornerRadius: 10,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: tickColor(),
                            maxRotation: 45,
                            minRotation: 0,
                            font: {
                                size: 11,
                            },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor(),
                        },
                        ticks: {
                            color: tickColor(),
                            precision: 0,
                            font: {
                                size: 11,
                            },
                        },
                    },
                },
            });

            const createChart = (
                elementId,
                configuration
            ) => {
                const canvas =
                    document.getElementById(elementId);

                if (!canvas) {
                    return null;
                }

                const chart = new window.Chart(
                    canvas,
                    configuration
                );

                charts.push(chart);

                return chart;
            };

            /*
             * Status Temuan
             */
            createChart('statusChart', {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Open',
                            data: chartData.status.open,
                            backgroundColor: palette.red,
                            borderRadius: 5,
                            maxBarThickness: 34,
                        },
                        {
                            label: 'Close',
                            data: chartData.status.close,
                            backgroundColor: palette.green,
                            borderRadius: 5,
                            maxBarThickness: 34,
                        },
                        {
                            label: 'Total Check',
                            data: chartData.status.total,
                            backgroundColor: palette.blue,
                            borderRadius: 5,
                            maxBarThickness: 34,
                        },
                    ],
                },
                options: baseOptions(),
            });

            /*
             * OK / NOK
             */
            createChart('okNokChart', {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'NOK',
                            data: chartData.ok_nok.nok,
                            backgroundColor: palette.red,
                            borderRadius: 5,
                            maxBarThickness: 42,
                        },
                        {
                            label: 'OK',
                            data: chartData.ok_nok.ok,
                            backgroundColor: palette.green,
                            borderRadius: 5,
                            maxBarThickness: 42,
                        },
                    ],
                },
                options: baseOptions(),
            });

            /*
             * Lokasi
             */
            const locationDatasets =
                Object.entries(chartData.locations ?? {})
                    .map(([location, values], index) => ({
                        label: location,
                        data: values,
                        backgroundColor:
                            locationPalette[
                                index % locationPalette.length
                            ],
                        borderRadius: 5,
                        maxBarThickness: 30,
                    }));

            createChart('locationChart', {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: locationDatasets,
                },
                options: baseOptions(),
            });

            /*
             * Kategori Temuan
             */
            createChart('findingChart', {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Visual',
                            data: chartData.findings.visual,
                            backgroundColor: palette.red,
                            borderRadius: 4,
                        },
                        {
                            label: 'Skun',
                            data: chartData.findings.skun,
                            backgroundColor: palette.green,
                            borderRadius: 4,
                        },
                        {
                            label: 'Cramping',
                            data: chartData.findings.cramping,
                            backgroundColor: palette.blue,
                            borderRadius: 4,
                        },
                        {
                            label: 'Marking',
                            data: chartData.findings.marking,
                            backgroundColor: palette.gray,
                            borderRadius: 4,
                        },
                        {
                            label: 'Belltest',
                            data: chartData.findings.belltest,
                            backgroundColor: palette.cyan,
                            borderRadius: 4,
                        },
                        {
                            label: 'Function',
                            data: chartData.findings.function,
                            backgroundColor: palette.yellow,
                            borderRadius: 4,
                        },
                    ],
                },
                options: {
                    ...baseOptions(),
                    datasets: {
                        bar: {
                            maxBarThickness: 22,
                        },
                    },
                },
            });

            /*
             * NCR
             */
            createChart('ncrChart', {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Visual',
                            data: chartData.ncr.visual,
                            backgroundColor: palette.blue,
                            borderRadius: 5,
                            maxBarThickness: 34,
                        },
                        {
                            label: 'Dimensi',
                            data: chartData.ncr.dimensi,
                            backgroundColor: palette.yellow,
                            borderRadius: 5,
                            maxBarThickness: 34,
                        },
                        {
                            label: 'Fungsi',
                            data: chartData.ncr.fungsi,
                            backgroundColor: palette.red,
                            borderRadius: 5,
                            maxBarThickness: 34,
                        },
                    ],
                },
                options: baseOptions(),
            });

            /*
             * Sinkronkan Chart ketika dark/light mode berubah.
             */
            const refreshChartTheme = () => {
                charts.forEach((chart) => {
                    if (
                        chart.options.plugins &&
                        chart.options.plugins.legend
                    ) {
                        chart.options.plugins.legend.labels.color =
                            tickColor();
                    }

                    if (chart.options.scales?.x) {
                        chart.options.scales.x.ticks.color =
                            tickColor();
                    }

                    if (chart.options.scales?.y) {
                        chart.options.scales.y.ticks.color =
                            tickColor();

                        chart.options.scales.y.grid.color =
                            gridColor();
                    }

                    chart.update('none');
                });
            };

            const observer = new MutationObserver(() => {
                refreshChartTheme();
            });

            observer.observe(
                document.documentElement,
                {
                    attributes: true,
                    attributeFilter: ['class'],
                }
            );
        });
    </script>
@endpush
