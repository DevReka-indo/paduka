@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')
<div class="py-6 max-w-7xl mx-auto space-y-6">

    {{-- Greeting --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800">
            Selamat datang, {{ Auth::user()->name }}
        </h2>
        <p class="text-sm text-gray-400 mt-0.5">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 gap-4 {{ $isAdmin ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }}">

        {{-- Total NCR --}}
        <a href="{{ route('ncr.index') }}"
            class="{{ $isAdmin ? 'lg:col-span-1' : 'col-span-2 lg:col-span-1' }} bg-gray-900 text-white rounded-2xl p-5 flex flex-col justify-between min-h-[110px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
            <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold">{{ $totalNcr }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $isAdmin ? 'Total Semua NCR' : 'Total NCR' }}
                </p>
            </div>
        </a>

        {{-- Open --}}
        <a href="{{ route('ncr.index', ['status' => 'open']) }}"
            class="bg-red-50 border border-red-100 rounded-2xl p-5 flex flex-col justify-between min-h-[110px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-200">
            <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold text-red-600">{{ $totalOpen }}</p>
                <p class="text-xs text-red-400 mt-0.5">Open</p>
            </div>
        </a>

        {{-- Process --}}
        <a href="{{ route('ncr.index', ['status' => 'process']) }}"
            class="bg-amber-50 border border-amber-100 rounded-2xl p-5 flex flex-col justify-between min-h-[110px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-200">
            <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
                <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold text-amber-600">{{ $totalProses }}</p>
                <p class="text-xs text-amber-400 mt-0.5">Process</p>
            </div>
        </a>

        {{-- Close --}}
        <a href="{{ route('ncr.index', ['status' => 'close']) }}"
            class="bg-green-50 border border-green-100 rounded-2xl p-5 flex flex-col justify-between min-h-[110px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-200">
            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
            </div>
            <div class="mt-3">
                <p class="text-3xl font-bold text-green-600">{{ $totalClose }}</p>
                <p class="text-xs text-green-400 mt-0.5">Closed</p>
            </div>
        </a>

        {{-- Project & User — hanya admin/superadmin --}}
        @if($isAdmin)
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 flex flex-col justify-between min-h-[110px]">
            <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <div class="mt-3 flex items-end justify-between">
                <div>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalProject }}</p>
                    <p class="text-xs text-indigo-400 mt-0.5">Project</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-indigo-400">{{ $totalUser }}</p>
                    <p class="text-xs text-indigo-300">User aktif</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Chart + Donut --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Bar Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Tren NCR per Bulan</h3>
                    <p class="text-xs text-gray-400">
                        12 bulan terakhir{{ !$isAdmin ? ' — NCR Anda' : '' }}
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <span class="w-2.5 h-2.5 bg-red-400 rounded-sm inline-block"></span>Open
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2.5 h-2.5 bg-amber-400 rounded-sm inline-block"></span>Process
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2.5 h-2.5 bg-green-400 rounded-sm inline-block"></span>Close
                    </span>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="chartTren"></canvas>
            </div>
        </div>

        {{-- Donut --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-800">Proporsi Status</h3>
                <p class="text-xs text-gray-400">
                    {{ $isAdmin ? 'Keseluruhan NCR' : 'NCR Anda' }}
                </p>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <div class="relative w-44 h-44">
                    <canvas id="chartDonut"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <p class="text-2xl font-bold text-gray-800">{{ $totalNcr }}</p>
                        <p class="text-xs text-gray-400">Total</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                @php
                    $pctOpen   = $totalNcr > 0 ? round($totalOpen   / $totalNcr * 100) : 0;
                    $pctProses = $totalNcr > 0 ? round($totalProses / $totalNcr * 100) : 0;
                    $pctClose  = $totalNcr > 0 ? round($totalClose  / $totalNcr * 100) : 0;
                @endphp
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="w-2.5 h-2.5 bg-red-400 rounded-full"></span>Open
                    </span>
                    <span class="font-semibold text-gray-700">
                        {{ $totalOpen }} <span class="text-gray-400 font-normal">({{ $pctOpen }}%)</span>
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="w-2.5 h-2.5 bg-amber-400 rounded-full"></span>Process
                    </span>
                    <span class="font-semibold text-gray-700">
                        {{ $totalProses }} <span class="text-gray-400 font-normal">({{ $pctProses }}%)</span>
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="w-2.5 h-2.5 bg-green-400 rounded-full"></span>Close
                    </span>
                    <span class="font-semibold text-gray-700">
                        {{ $totalClose }} <span class="text-gray-400 font-normal">({{ $pctClose }}%)</span>
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- NCR Mendekati Target --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">NCR Mendekati Target</h3>
                    <p class="text-xs text-gray-400">
                        Belum selesai & target ≤ 7 hari ke depan{{ !$isAdmin ? ' — NCR Anda' : '' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('ncr.index') }}" class="text-xs text-indigo-500 hover:underline">
                Lihat semua
            </a>
        </div>

        @if($ncrMendekatiTarget->isEmpty())
            <div class="px-4 py-10 text-center">
                <div class="flex flex-col items-center gap-2 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-400">Tidak ada NCR yang mendekati target</p>
                </div>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                        <th class="px-4 py-3 text-left font-semibold">Nomor NCR</th>
                        <th class="px-4 py-3 text-left font-semibold">Proyek</th>
                        <th class="px-4 py-3 text-left font-semibold">Penanggung Jawab</th>
                        <th class="px-4 py-3 text-left font-semibold">Target</th>
                        <th class="px-4 py-3 text-left font-semibold">Sisa Waktu</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($ncrMendekatiTarget as $item)
                    <tr class="hover:bg-gray-50 transition-colors
                        {{ $item->sisa_hari < 0 ? 'bg-red-50/50' : ($item->sisa_hari <= 2 ? 'bg-orange-50/50' : '') }}">

                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                class="font-medium text-indigo-600 hover:underline">
                                {{ $item->nomor_ncr }}
                            </a>
                        </td>

                        <td class="px-4 py-3 text-gray-600 max-w-[160px]">
                            <p class="truncate" title="{{ $item->project->nama_proyek ?? '-' }}">
                                {{ $item->project->nama_proyek ?? '-' }}
                            </p>
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $item->penanggungJawab->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tgl_target)->format('d-m-Y') }}
                        </td>

                        <td class="px-4 py-3">
                            @if($item->sisa_hari < 0)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                    </svg>
                                    Lewat {{ abs($item->sisa_hari) }} hari
                                </span>
                            @elseif($item->sisa_hari == 0)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full">
                                    Hari ini!
                                </span>
                            @elseif($item->sisa_hari <= 2)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-orange-50 text-orange-600 px-2.5 py-1 rounded-full">
                                    {{ $item->sisa_hari }} hari lagi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-yellow-50 text-yellow-600 px-2.5 py-1 rounded-full">
                                    {{ $item->sisa_hari }} hari lagi
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            @php $st = strtolower($item->keterangan ?? ''); @endphp
                            @if($st == 'open')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-600 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>OPEN
                                </span>
                            @elseif($st == 'process')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>PROCESS
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                class="inline-flex items-center gap-1 text-xs font-medium bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const ctxTren = document.getElementById('chartTren').getContext('2d');
    new Chart(ctxTren, {
        type: 'bar',
        data: {
            labels: @json($bulanLabel),
            datasets: [
                {
                    label: 'Open',
                    data: @json($dataOpen),
                    backgroundColor: 'rgba(248, 113, 113, 0.8)',
                    borderRadius: 4,
                },
                {
                    label: 'Process',
                    data: @json($dataProses),
                    backgroundColor: 'rgba(251, 191, 36, 0.8)',
                    borderRadius: 4,
                },
                {
                    label: 'Close',
                    data: @json($dataClose),
                    backgroundColor: 'rgba(74, 222, 128, 0.8)',
                    borderRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false },
            },
            scales: {
                x: {
                    stacked: false,
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#9ca3af' },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { size: 11 },
                        color: '#9ca3af',
                        stepSize: 1,
                        precision: 0,
                    },
                },
            },
        },
    });

    const ctxDonut = document.getElementById('chartDonut').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'Process', 'Close'],
            datasets: [{
                data: [{{ $totalOpen }}, {{ $totalProses }}, {{ $totalClose }}],
                backgroundColor: [
                    'rgba(248, 113, 113, 0.85)',
                    'rgba(251, 191, 36, 0.85)',
                    'rgba(74, 222, 128, 0.85)',
                ],
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? Math.round(ctx.raw / total * 100) : 0;
                            return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                        }
                    }
                },
            },
        },
    });
});
</script>
@endsection
