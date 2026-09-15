@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-[1600px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                    Monitoring QC
                </div>

                <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">
                    QC Product & Final Mekanik
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Dashboard monitoring Product & Final Inspection Mekanik.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.daily-check.index'
                    ) }}"
                    class="inline-flex items-center gap-2 rounded-lg border
                        border-slate-300 bg-white px-4 py-2 text-sm font-medium
                        text-slate-700 shadow-sm hover:bg-slate-50
                        dark:border-slate-700 dark:bg-slate-900
                        dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    <i class="fa-solid fa-clipboard-check"></i>
                    Daily Check
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.ncr.index'
                    ) }}"
                    class="inline-flex items-center gap-2 rounded-lg
                        bg-indigo-600 px-4 py-2 text-sm font-semibold
                        text-white shadow-sm hover:bg-indigo-700"
                >
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Detail NCR
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <form
            method="GET"
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <div>
                    <x-input-label
                        for="year"
                        value="Tahun"
                    />

                    <select
                        id="year"
                        name="year"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    >
                        @if ($availableYears->isEmpty())
                            <option value="{{ $selectedYear }}">
                                {{ $selectedYear }}
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

                <div>
                    <x-input-label
                        for="month_from"
                        value="Bulan Dari"
                    />

                    <select
                        id="month_from"
                        name="month_from"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="">
                            Januari
                        </option>

                        @foreach ($monthOptions as $month => $label)
                            <option
                                value="{{ $month }}"
                                @selected(
                                    (string) request('month_from')
                                    === (string) $month
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label
                        for="month_to"
                        value="Bulan Sampai"
                    />

                    <select
                        id="month_to"
                        name="month_to"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="">
                            Desember
                        </option>

                        @foreach ($monthOptions as $month => $label)
                            <option
                                value="{{ $month }}"
                                @selected(
                                    (string) request('month_to')
                                    === (string) $month
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label
                        for="project_id"
                        value="Project"
                    />

                    <select
                        id="project_id"
                        name="project_id"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900 dark:text-white"
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
                                -
                                {{ $project->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label
                        for="inspection_gate"
                        value="Inspection Gate"
                    />

                    <select
                        id="inspection_gate"
                        name="inspection_gate"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="">
                            Semua Gate
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
            </div>

            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Filter Inspection Gate hanya memengaruhi data Daily Check.
                    NCR tetap mengikuti periode dan project.
                </p>

                <div class="flex justify-end gap-2">
                    <a
                        href="{{ route(
                            'monitoring-qc.product-final-mechanical.dashboard'
                        ) }}"
                        class="rounded-lg border border-slate-300 px-4 py-2
                            text-sm font-medium text-slate-700 hover:bg-slate-50
                            dark:border-slate-700 dark:text-slate-200
                            dark:hover:bg-slate-800"
                    >
                        Reset
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg
                            bg-indigo-600 px-4 py-2 text-sm font-semibold
                            text-white hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-filter"></i>
                        Terapkan
                    </button>
                </div>
            </div>
        </form>

        {{-- Daily KPI --}}
        <div>
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide
                text-slate-500 dark:text-slate-400"
            >
                Daily Check
            </h2>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-9">
                @php
                    $dailyCards = [
                        ['Total Check', $summary['total_check']],
                        ['Open', $summary['open']],
                        ['Close', $summary['close']],
                        ['Pending', $summary['pending']],
                        ['Close %', $summary['close_percentage'] . '%'],
                        ['Total Temuan', $summary['total_findings']],
                        ['Qty OK', $summary['qty_ok']],
                        ['Qty NOK', $summary['qty_nok']],
                        ['Total NCR', $summary['total_ncr']],
                    ];
                @endphp

                @foreach ($dailyCards as [$label, $value])
                    <div class="rounded-xl border border-slate-200 bg-white
                        p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900"
                    >
                        <div class="text-xs font-medium uppercase tracking-wide
                            text-slate-500 dark:text-slate-400"
                        >
                            {{ $label }}
                        </div>

                        <div class="mt-2 text-2xl font-bold
                            text-slate-900 dark:text-white"
                        >
                            {{ is_numeric($value)
                                ? number_format($value)
                                : $value }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Daily Status Chart --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
            dark:border-slate-800 dark:bg-slate-900"
        >
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                Status Daily Check
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Open, Close dan Pending per periode pelaporan.
            </p>

            <div class="mt-6 h-[340px]">
                <canvas id="dailyStatusChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            {{-- Finding Method --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Temuan Berdasarkan Metode Pengecekan
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    VT, DM, WG, PT, CP dan FT.
                </p>

                <div class="mt-6 h-[340px]">
                    <canvas id="findingMethodChart"></canvas>
                </div>
            </div>

            {{-- Gate --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Temuan Berdasarkan Inspection Gate
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Distribusi jumlah temuan pada setiap proses inspeksi.
                </p>

                <div class="mt-6 h-[340px]">
                    <canvas id="inspectionGateChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Finding --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
            dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Top 5 Product dengan Temuan Terbanyak
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Berdasarkan total VT + DM + WG + PT + CP + FT.
                </p>
            </div>

            <div class="space-y-3">
                @forelse ($topFindings as $index => $row)
                    <div class="flex items-center gap-4 rounded-lg
                        bg-slate-50 p-4 dark:bg-slate-800/60"
                    >
                        <div class="flex h-9 w-9 flex-shrink-0 items-center
                            justify-center rounded-lg bg-white text-sm
                            font-bold text-slate-600 shadow-sm
                            dark:bg-slate-900 dark:text-slate-300"
                        >
                            {{ $index + 1 }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="truncate font-medium
                                text-slate-900 dark:text-white"
                            >
                                {{ $row->final_assembly_product_name }}
                            </div>
                        </div>

                        <div class="text-xl font-bold
                            text-slate-900 dark:text-white"
                        >
                            {{ number_format($row->total_findings) }}
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm
                        text-slate-500 dark:text-slate-400"
                    >
                        Belum ada data temuan pada filter ini.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- NCR --}}
        <div class="border-t border-slate-200 pt-6 dark:border-slate-800">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Monitoring NCR
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Monitoring Detail NCR Mekanik berdasarkan periode pelaporan.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
            @php
                $ncrCards = [
                    ['Total NCR', $ncrSummary['total']],
                    ['Open', $ncrSummary['open']],
                    ['Close', $ncrSummary['close']],
                    ['Pending', $ncrSummary['pending']],
                    ['Total Temuan', $ncrSummary['total_findings']],
                ];
            @endphp

            @foreach ($ncrCards as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white
                    p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900"
                >
                    <div class="text-xs font-medium uppercase tracking-wide
                        text-slate-500 dark:text-slate-400"
                    >
                        {{ $label }}
                    </div>

                    <div class="mt-2 text-2xl font-bold
                        text-slate-900 dark:text-white"
                    >
                        {{ number_format($value) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white
                p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"
            >
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Status NCR
                </h2>

                <div class="mt-6 h-[340px]">
                    <canvas id="ncrStatusChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white
                p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"
            >
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Kategori Defect NCR
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Visual, Dimensi dan Fungsi.
                </p>

                <div class="mt-6 h-[340px]">
                    <canvas id="ncrFindingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('load', function () {
            if (typeof window.Chart === 'undefined') {
                console.error('Chart.js belum tersedia.');
                return;
            }

            const chartData = @js($chart);

            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            };

            new window.Chart(
                document.getElementById('dailyStatusChart'),
                {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Open',
                                data: chartData.status.open,
                            },
                            {
                                label: 'Close',
                                data: chartData.status.close,
                            },
                            {
                                label: 'Pending',
                                data: chartData.status.pending,
                            },
                            {
                                label: 'Total Check',
                                data: chartData.status.total,
                                type: 'line',
                                tension: 0.3,
                            },
                        ],
                    },
                    options: baseOptions,
                }
            );

            new window.Chart(
                document.getElementById('findingMethodChart'),
                {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'VT',
                                data: chartData.findings.vt,
                            },
                            {
                                label: 'DM',
                                data: chartData.findings.dm,
                            },
                            {
                                label: 'WG',
                                data: chartData.findings.wg,
                            },
                            {
                                label: 'PT',
                                data: chartData.findings.pt,
                            },
                            {
                                label: 'CP',
                                data: chartData.findings.cp,
                            },
                            {
                                label: 'FT',
                                data: chartData.findings.ft,
                            },
                        ],
                    },
                    options: baseOptions,
                }
            );

            new window.Chart(
                document.getElementById('inspectionGateChart'),
                {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: chartData.inspection_gates.map(
                            function (item) {
                                return {
                                    label: item.label,
                                    data: item.data,
                                    tension: 0.3,
                                };
                            }
                        ),
                    },
                    options: baseOptions,
                }
            );

            new window.Chart(
                document.getElementById('ncrStatusChart'),
                {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Open',
                                data: chartData.ncr_status.open,
                            },
                            {
                                label: 'Close',
                                data: chartData.ncr_status.close,
                            },
                            {
                                label: 'Pending',
                                data: chartData.ncr_status.pending,
                            },
                            {
                                label: 'Total NCR',
                                data: chartData.ncr_status.total,
                                type: 'line',
                                tension: 0.3,
                            },
                        ],
                    },
                    options: baseOptions,
                }
            );

            new window.Chart(
                document.getElementById('ncrFindingChart'),
                {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Visual',
                                data: chartData.ncr_findings.visual,
                            },
                            {
                                label: 'Dimensi',
                                data: chartData.ncr_findings.dimension,
                            },
                            {
                                label: 'Fungsi',
                                data: chartData.ncr_findings.function,
                            },
                        ],
                    },
                    options: baseOptions,
                }
            );
        });
    </script>
@endpush
