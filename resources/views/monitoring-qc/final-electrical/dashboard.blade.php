@extends('layouts.app')

@section('header')
    Dashboard QC Final - Elektrik
@endsection

@section('content')
    @php
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

        $inputClass = 'block w-full rounded-xl border-gray-300
            bg-white text-sm text-gray-700 shadow-sm
            focus:border-indigo-500 focus:ring-indigo-500
            dark:border-gray-600 dark:bg-gray-900
            dark:text-gray-200';

        $topTabs = [
            'visual' => 'Visual',
            'completeness' => 'Kelengkapan',
            'belltest' => 'Belltest',
            'function' => 'Fungsi',
            'torque' => 'Torsi',
        ];
    @endphp

    <div class="w-full max-w-full space-y-6">

        {{-- Header --}}
        <div
            class="flex flex-col gap-4
                lg:flex-row lg:items-start lg:justify-between"
        >
            <div>
                <p
                    class="text-sm font-medium text-indigo-600
                        dark:text-indigo-400"
                >
                    Monitoring QC · QC Final - Elektrik
                </p>

                <h1
                    class="mt-1 text-2xl font-bold text-gray-900
                        dark:text-gray-100"
                >
                    Dashboard
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Monitoring Final Inspection Elektrik berdasarkan
                    Daily Check PADUKA.
                </p>
            </div>

            <a
                href="{{ route(
                    'monitoring-qc.final-electrical.daily-check.index'
                ) }}"
                class="inline-flex items-center justify-center rounded-xl
                    bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                    text-white shadow-sm hover:bg-indigo-700"
            >
                Daily Check
            </a>
        </div>

        {{-- Filter --}}
        <form
            method="GET"
            class="rounded-3xl border border-gray-100 bg-white
                p-5 shadow-sm dark:border-gray-700
                dark:bg-gray-800"
        >
            <div
                class="grid grid-cols-1 gap-4
                    sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6"
            >
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500">
                        Tahun
                    </label>

                    <select
                        name="year"
                        class="{{ $inputClass }}"
                    >
                        @forelse ($availableYears as $year)
                            <option
                                value="{{ $year }}"
                                @selected(
                                    (int) $selectedYear
                                    === (int) $year
                                )
                            >
                                {{ $year }}
                            </option>
                        @empty
                            <option value="{{ now()->year }}">
                                {{ now()->year }}
                            </option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500">
                        Bulan Dari
                    </label>

                    <select
                        name="month_from"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Januari
                        </option>

                        @foreach ($months as $number => $month)
                            <option
                                value="{{ $number }}"
                                @selected(
                                    (string) request('month_from')
                                    === (string) $number
                                )
                            >
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500">
                        Bulan Sampai
                    </label>

                    <select
                        name="month_to"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Desember
                        </option>

                        @foreach ($months as $number => $month)
                            <option
                                value="{{ $number }}"
                                @selected(
                                    (string) request('month_to')
                                    === (string) $number
                                )
                            >
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500">
                        Project
                    </label>

                    <select
                        name="project_id"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua Project
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

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-500">
                        Lokasi
                    </label>

                    <select
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
                                    request('inspection_gate')
                                    === $gate
                                )
                            >
                                {{ $gate }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-gray-900
                            px-4 py-2.5 text-sm font-semibold
                            text-white hover:bg-gray-800
                            dark:bg-indigo-600 dark:hover:bg-indigo-700"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route(
                            'monitoring-qc.final-electrical.dashboard'
                        ) }}"
                        class="rounded-xl border border-gray-300
                            px-4 py-2.5 text-sm font-medium text-gray-600
                            hover:bg-gray-50 dark:border-gray-600
                            dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- KPI --}}
        <div
            class="grid grid-cols-2 gap-4
                lg:grid-cols-4 2xl:grid-cols-8"
        >
            @php
                $cards = [
                    ['label' => 'Total Check', 'value' => $summary['total_check']],
                    ['label' => 'Open', 'value' => $summary['open']],
                    ['label' => 'Close', 'value' => $summary['close']],
                    ['label' => 'Pending', 'value' => $summary['pending']],
                    ['label' => 'Close %', 'value' => number_format($summary['close_percentage'], 1) . '%'],
                    ['label' => 'Total Temuan', 'value' => $summary['total_findings']],
                    ['label' => 'Jumlah OIL', 'value' => $summary['total_oil']],
                    ['label' => 'NCR', 'value' => $summary['total_ncr']],
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="rounded-2xl border border-gray-100 bg-white
                        p-5 shadow-sm dark:border-gray-700
                        dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase tracking-wide
                            text-gray-500 dark:text-gray-400"
                    >
                        {{ $card['label'] }}
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold text-gray-900
                            dark:text-gray-100"
                    >
                        {{ is_numeric($card['value'])
                            ? number_format($card['value'])
                            : $card['value']
                        }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Status --}}
        <div
            class="rounded-3xl border border-gray-100 bg-white
                p-6 shadow-sm dark:border-gray-700
                dark:bg-gray-800"
        >
            <div>
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Status Temuan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Open, Close, Pending, dan Total Check per bulan.
                </p>
            </div>

            <div class="mt-6 h-[360px]">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        {{-- Finding + Location --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            <div
                class="rounded-3xl border border-gray-100 bg-white
                    p-6 shadow-sm dark:border-gray-700
                    dark:bg-gray-800"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Jenis Temuan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Visual, Kelengkapan, Belltest, Fungsi, dan Torsi.
                </p>

                <div class="mt-6 h-[380px]">
                    <canvas id="findingChart"></canvas>
                </div>
            </div>

            <div
                class="rounded-3xl border border-gray-100 bg-white
                    p-6 shadow-sm dark:border-gray-700
                    dark:bg-gray-800"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Lokasi Temuan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Quantity temuan berdasarkan Inspection Gate.
                </p>

                <div class="mt-6 h-[380px]">
                    <canvas id="locationChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top 3 --}}
        <div
            x-data="{ tab: 'visual' }"
            class="rounded-3xl border border-gray-100 bg-white
                p-6 shadow-sm dark:border-gray-700
                dark:bg-gray-800"
        >
            <div
                class="flex flex-col gap-4
                    lg:flex-row lg:items-center lg:justify-between"
            >
                <div>
                    <h2
                        class="text-base font-semibold text-gray-900
                            dark:text-gray-100"
                    >
                        Top 3 Temuan
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Produk dengan jumlah temuan tertinggi per bulan.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($topTabs as $key => $label)
                        <button
                            type="button"
                            @click="tab = '{{ $key }}'"
                            :class="
                                tab === '{{ $key }}'
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'
                            "
                            class="rounded-xl px-3 py-2 text-xs
                                font-semibold transition"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach ($topTabs as $key => $label)
                <div
                    x-cloak
                    x-show="tab === '{{ $key }}'"
                    class="mt-6 overflow-x-auto"
                >
                    <table class="w-full min-w-[900px] text-sm">
                        <thead
                            class="bg-gray-50 text-xs uppercase
                                tracking-wide text-gray-500
                                dark:bg-gray-900/50"
                        >
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    Periode
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Rank 1
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Qty
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Rank 2
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Qty
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Rank 3
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Qty
                                </th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100
                                dark:divide-gray-700"
                        >
                            @php
                                $hasRows = false;
                            @endphp

                            @foreach ($months as $monthNumber => $monthName)
                                @php
                                    $rows = $topFindings[$key]
                                        ->get(
                                            $monthNumber,
                                            collect()
                                        );

                                    if ($rows->isNotEmpty()) {
                                        $hasRows = true;
                                    }

                                    $rank1 = $rows->get(0);
                                    $rank2 = $rows->get(1);
                                    $rank3 = $rows->get(2);
                                @endphp

                                @if ($rows->isNotEmpty())
                                    <tr>
                                        <td
                                            class="px-4 py-4
                                                font-medium text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{ $monthName }}
                                            {{ $selectedYear }}
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $rank1?->product_name ?? '-' }}
                                        </td>

                                        <td
                                            class="px-4 py-4 text-center
                                                font-semibold"
                                        >
                                            {{ (int) ($rank1?->total ?? 0) }}
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $rank2?->product_name ?? '-' }}
                                        </td>

                                        <td
                                            class="px-4 py-4 text-center
                                                font-semibold"
                                        >
                                            {{ (int) ($rank2?->total ?? 0) }}
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $rank3?->product_name ?? '-' }}
                                        </td>

                                        <td
                                            class="px-4 py-4 text-center
                                                font-semibold"
                                        >
                                            {{ (int) ($rank3?->total ?? 0) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if (! $hasRows)
                                <tr>
                                    <td
                                        colspan="7"
                                        class="px-5 py-10 text-center
                                            text-gray-500"
                                    >
                                        Belum ada data temuan
                                        {{ $label }}.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        {{-- NCR Monitoring --}}
        <div class="space-y-6">
            <div
                class="flex flex-col gap-4
                    lg:flex-row lg:items-center lg:justify-between"
            >
                <div>
                    <p
                        class="text-sm font-medium text-indigo-600
                            dark:text-indigo-400"
                    >
                        Non-Conformance Report
                    </p>

                    <h2
                        class="mt-1 text-xl font-bold
                            text-gray-900 dark:text-gray-100"
                    >
                        Monitoring NCR
                    </h2>

                    <p
                        class="mt-1 text-sm text-gray-500
                            dark:text-gray-400"
                    >
                        Status dan kategori defect NCR
                        QC Final Elektrik.
                    </p>
                </div>

                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.ncr.index'
                    ) }}"
                    class="inline-flex items-center justify-center
                        rounded-xl bg-indigo-600 px-4 py-2.5
                        text-sm font-semibold text-white
                        shadow-sm transition hover:bg-indigo-700"
                >
                    Buka Detail NCR
                </a>
            </div>

            {{-- KPI NCR --}}
            <div
                class="grid grid-cols-2 gap-4
                    lg:grid-cols-4"
            >
                <div
                    class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase tracking-wide
                            text-gray-500 dark:text-gray-400"
                    >
                        Total NCR
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold
                            text-gray-900 dark:text-gray-100"
                    >
                        {{ number_format($ncrSummary['total']) }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-red-100
                        bg-white p-5 shadow-sm
                        dark:border-red-900/30 dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase tracking-wide
                            text-red-500"
                    >
                        Open
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold
                            text-red-600 dark:text-red-400"
                    >
                        {{ number_format($ncrSummary['open']) }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-emerald-100
                        bg-white p-5 shadow-sm
                        dark:border-emerald-900/30 dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase tracking-wide
                            text-emerald-500"
                    >
                        Close
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold
                            text-emerald-600 dark:text-emerald-400"
                    >
                        {{ number_format($ncrSummary['closed']) }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase tracking-wide
                            text-gray-500 dark:text-gray-400"
                    >
                        Pending
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold
                            text-gray-700 dark:text-gray-300"
                    >
                        {{ number_format($ncrSummary['pending']) }}
                    </p>
                </div>
            </div>

            {{-- Grafik NCR --}}
            <div
                class="grid grid-cols-1 gap-6
                    xl:grid-cols-2"
            >
                <div
                    class="rounded-3xl border border-gray-100
                        bg-white p-6 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <h3
                        class="font-semibold text-gray-900
                            dark:text-gray-100"
                    >
                        Status NCR per Bulan
                    </h3>

                    <p
                        class="mt-1 text-sm text-gray-500
                            dark:text-gray-400"
                    >
                        Jumlah nomor NCR unik berdasarkan status.
                    </p>

                    <div class="mt-6 h-[360px]">
                        <canvas id="ncrStatusChart"></canvas>
                    </div>
                </div>

                <div
                    class="rounded-3xl border border-gray-100
                        bg-white p-6 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <h3
                        class="font-semibold text-gray-900
                            dark:text-gray-100"
                    >
                        Kategori Defect NCR
                    </h3>

                    <p
                        class="mt-1 text-sm text-gray-500
                            dark:text-gray-400"
                    >
                        Visual, Dimensi, Kelengkapan,
                        Spesifikasi, dan Fungsi.
                    </p>

                    <div class="mt-6 h-[360px]">
                        <canvas id="ncrFindingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('load', function () {
            if (!window.Chart) {
                console.error(
                    'Chart.js belum tersedia.'
                );

                return;
            }

            const chartData = @js($chart);

            const isDark =
                document.documentElement
                    .classList
                    .contains('dark');

            const textColor =
                isDark
                    ? '#d1d5db'
                    : '#4b5563';

            const gridColor =
                isDark
                    ? 'rgba(75, 85, 99, 0.35)'
                    : 'rgba(229, 231, 235, 0.8)';

            const baseOptions = () => ({
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                            usePointStyle: true
                        }
                    }
                },

                scales: {
                    x: {
                        ticks: {
                            color: textColor
                        },

                        grid: {
                            display: false
                        }
                    },

                    y: {
                        beginAtZero: true,

                        ticks: {
                            color: textColor,
                            precision: 0
                        },

                        grid: {
                            color: gridColor
                        }
                    }
                }
            });

            /*
             * Status
             */
            new window.Chart(
                document.getElementById('statusChart'),
                {
                    type: 'bar',

                    data: {
                        labels:
                            chartData.labels,

                        datasets: [
                            {
                                label: 'Open',
                                data:
                                    chartData.status.open,
                                backgroundColor:
                                    '#ef4444'
                            },
                            {
                                label: 'Close',
                                data:
                                    chartData.status.close,
                                backgroundColor:
                                    '#10b981'
                            },
                            {
                                label: 'Pending',
                                data:
                                    chartData.status.pending,
                                backgroundColor:
                                    '#f59e0b'
                            },
                            {
                                label: 'Total Check',
                                data:
                                    chartData.status.total,
                                backgroundColor:
                                    '#3b82f6'
                            }
                        ]
                    },

                    options:
                        baseOptions()
                }
            );

            /*
             * Finding
             */
            new window.Chart(
                document.getElementById('findingChart'),
                {
                    type: 'bar',

                    data: {
                        labels:
                            chartData.labels,

                        datasets: [
                            {
                                label: 'Visual',
                                data:
                                    chartData.findings.visual,
                                backgroundColor:
                                    '#ef4444'
                            },
                            {
                                label: 'Kelengkapan',
                                data:
                                    chartData.findings.completeness,
                                backgroundColor:
                                    '#10b981'
                            },
                            {
                                label: 'Belltest',
                                data:
                                    chartData.findings.belltest,
                                backgroundColor:
                                    '#06b6d4'
                            },
                            {
                                label: 'Fungsi',
                                data:
                                    chartData.findings.function,
                                backgroundColor:
                                    '#3b82f6'
                            },
                            {
                                label: 'Torsi',
                                data:
                                    chartData.findings.torque,
                                backgroundColor:
                                    '#f59e0b'
                            }
                        ]
                    },

                    options:
                        baseOptions()
                }
            );

            /*
             * Location
             */
            const locationPalette = [
                '#3b82f6',
                '#10b981',
                '#f59e0b',
                '#ef4444',
                '#8b5cf6',
                '#06b6d4'
            ];

        const locationDatasets =
            chartData.locations.map(
                (dataset, index) => ({
                    label:
                        dataset.label,

                    data:
                        dataset.data,

                    backgroundColor:
                        locationPalette[
                            index
                            % locationPalette.length
                        ]
                })
            );

            new window.Chart(
                document.getElementById('locationChart'),
                {
                    type: 'bar',

                    data: {
                        labels:
                            chartData.labels,

                        datasets:
                            locationDatasets
                    },

                    options:
                        baseOptions()
                }
            );

            /**
             * NCR Status
             */
            const ncrStatusCanvas =
                document.getElementById(
                    'ncrStatusChart'
                );

            if (ncrStatusCanvas) {
                new window.Chart(
                    ncrStatusCanvas,
                    {
                        type: 'bar',

                        data: {
                            labels:
                                chartData.labels,

                            datasets: [
                                {
                                    label: 'Open',
                                    data:
                                        chartData
                                            .ncr_status
                                            .open,
                                    backgroundColor:
                                        '#ef4444'
                                },
                                {
                                    label: 'Close',
                                    data:
                                        chartData
                                            .ncr_status
                                            .close,
                                    backgroundColor:
                                        '#10b981'
                                },
                                {
                                    label: 'Pending',
                                    data:
                                        chartData
                                            .ncr_status
                                            .pending,
                                    backgroundColor:
                                        '#f59e0b'
                                },
                                {
                                    label: 'Total NCR',
                                    data:
                                        chartData
                                            .ncr_status
                                            .total,
                                    type: 'line',
                                    borderColor:
                                        '#3b82f6',
                                    backgroundColor:
                                        '#3b82f6',
                                    tension: 0.3
                                }
                            ]
                        },

                        options:
                            baseOptions()
                    }
                );
            }

            /**
             * NCR Finding Category
             */
            const ncrFindingCanvas =
                document.getElementById(
                    'ncrFindingChart'
                );

            if (ncrFindingCanvas) {
                new window.Chart(
                    ncrFindingCanvas,
                    {
                        type: 'bar',

                        data: {
                            labels:
                                chartData.labels,

                            datasets: [
                                {
                                    label: 'Visual',
                                    data:
                                        chartData
                                            .ncr_findings
                                            .visual,
                                    backgroundColor:
                                        '#ef4444'
                                },
                                {
                                    label: 'Dimensi',
                                    data:
                                        chartData
                                            .ncr_findings
                                            .dimension,
                                    backgroundColor:
                                        '#8b5cf6'
                                },
                                {
                                    label: 'Kelengkapan',
                                    data:
                                        chartData
                                            .ncr_findings
                                            .completeness,
                                    backgroundColor:
                                        '#10b981'
                                },
                                {
                                    label: 'Spesifikasi',
                                    data:
                                        chartData
                                            .ncr_findings
                                            .specification,
                                    backgroundColor:
                                        '#06b6d4'
                                },
                                {
                                    label: 'Fungsi',
                                    data:
                                        chartData
                                            .ncr_findings
                                            .function,
                                    backgroundColor:
                                        '#3b82f6'
                                }
                            ]
                        },

                        options:
                            baseOptions()
                    }
                );
            }

        });
    </script>
@endpush
