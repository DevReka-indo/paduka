@extends('layouts.app')

@section('header')
    Database Komponen
@endsection

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        Database Komponen
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Total Penggantian Komponen
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Data seluruh komponen yang mengalami penggantian berdasarkan periode dan filter yang dipilih.
                    </p>
                </div>

                <form method="GET" action="{{ route('durability.penggantian-komponen') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-6">
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Dari
                        </label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Sampai
                        </label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Produk
                        </label>
                        <select name="produk_id"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($produkList as $produk)
                                <option value="{{ $produk->id }}" @selected((string) $produkId === (string) $produk->id)>
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Trainset
                        </label>
                        <select name="trainset_id"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($trainsetList as $trainset)
                                <option value="{{ $trainset->id }}" @selected((string) $trainsetId === (string) $trainset->id)>
                                    {{ $trainset->nomor_trainset ? 'TS-' . $trainset->nomor_trainset : '-' }}
                                    {{ $trainset->tipe_car ? ' / ' . $trainset->tipe_car : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Lokasi
                        </label>
                        <select name="lokasi_id"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($lokasiList as $lokasi)
                                <option value="{{ $lokasi->id }}" @selected((string) $lokasiId === (string) $lokasi->id)>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="fa-solid fa-filter mr-2"></i>
                            Filter
                        </button>

                        <a href="{{ route('durability.penggantian-komponen') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($periodeLabel || $produkId || $trainsetId || $lokasiId)
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                Menampilkan data penggantian komponen

                @if($periodeLabel)
                    periode <strong>{{ $periodeLabel }}</strong>
                @endif

                @if($produkId)
                    @php $selectedProduk = $produkList->firstWhere('id', (int) $produkId); @endphp
                    @if($selectedProduk)
                        produk <strong>{{ $selectedProduk->nama_produk }}</strong>
                    @endif
                @endif

                @if($trainsetId)
                    @php $selectedTrainset = $trainsetList->firstWhere('id', (int) $trainsetId); @endphp
                    @if($selectedTrainset)
                        trainset
                        <strong>
                            {{ $selectedTrainset->nomor_trainset ? 'TS-' . $selectedTrainset->nomor_trainset : '-' }}
                            {{ $selectedTrainset->tipe_car ? ' / ' . $selectedTrainset->tipe_car : '' }}
                        </strong>
                    @endif
                @endif

                @if($lokasiId)
                    @php $selectedLokasi = $lokasiList->firstWhere('id', (int) $lokasiId); @endphp
                    @if($selectedLokasi)
                        lokasi <strong>{{ $selectedLokasi->nama_lokasi }}</strong>
                    @endif
                @endif
            </div>
        @endif

        {{-- Chart --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Total Penggantian Setiap Komponen
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Jumlah penggantian seluruh komponen pada periode yang dipilih.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('durability.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="h-[520px]">
                    <canvas id="chartPenggantianKomponen"></canvas>
                </div>

                <div class="mt-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <strong>{{ number_format($chartFromItem, 0, ',', '.') }}</strong>
                        -
                        <strong>{{ number_format($chartToItem, 0, ',', '.') }}</strong>
                        dari
                        <strong>{{ number_format($chartTotalItems, 0, ',', '.') }}</strong>
                        komponen
                    </p>

                    @if($chartTotalPages > 1)
                        <div class="flex flex-wrap items-center gap-2">
                            @php
                                $baseChartQuery = request()->except(['chart_page', 'page']);
                                $prevChartPage = max($chartPage - 1, 1);
                                $nextChartPage = min($chartPage + 1, $chartTotalPages);

                                $startPage = max($chartPage - 2, 1);
                                $endPage = min($chartPage + 2, $chartTotalPages);
                            @endphp

                            <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $prevChartPage])) }}"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700
                                {{ $chartPage <= 1 ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>

                            @if($startPage > 1)
                                <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => 1])) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    1
                                </a>

                                @if($startPage > 2)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                            @endif

                            @for($page = $startPage; $page <= $endPage; $page++)
                                <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $page])) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border text-sm font-semibold shadow-sm transition
                                    {{ $chartPage == $page
                                        ? 'border-blue-600 bg-blue-600 text-white'
                                        : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    {{ $page }}
                                </a>
                            @endfor

                            @if($endPage < $chartTotalPages)
                                @if($endPage < $chartTotalPages - 1)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif

                                <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $chartTotalPages])) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    {{ $chartTotalPages }}
                                </a>
                            @endif

                            <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $nextChartPage])) }}"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700
                                {{ $chartPage >= $chartTotalPages ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-5 flex items-start gap-2 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p>
                        Grafik menampilkan jumlah total penggantian untuk setiap komponen selama periode yang dipilih.
                    </p>
                </div>
            </div>
        </div>

        {{-- Detail Table --}}
        {{-- <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Tabel Detail Penggantian Komponen
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ringkasan penggantian per komponen, produk, jumlah record, dan rata-rata rentang penggantian.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-4 text-left font-bold">#</th>
                            <th class="px-5 py-4 text-left font-bold">Komponen</th>
                            <th class="px-5 py-4 text-left font-bold">Produk</th>
                            <th class="px-5 py-4 text-right font-bold">Total Penggantian</th>
                            <th class="px-5 py-4 text-right font-bold">Total Record</th>
                            <th class="px-5 py-4 text-right font-bold">Rata-rata Rentang</th>
                            <th class="px-5 py-4 text-left font-bold">Periode Data</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($detailKomponen as $index => $item)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-4 text-gray-400">
                                    {{ $detailKomponen->firstItem() + $index }}
                                </td>

                                <td class="max-w-[360px] px-5 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item->nama_komponen }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $item->nama_produk ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                        {{ number_format($item->total_penggantian ?? 0, 0, ',', '.') }} Kali
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($item->total_record ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
                                        {{ round($item->rata_rentang ?? 0) }} Hari
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    @if($item->tanggal_awal && $item->tanggal_akhir)
                                        {{ \Carbon\Carbon::parse($item->tanggal_awal)->format('Y-m-d') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('Y-m-d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <img src="{{ asset('img/data-not-found.png') }}" alt="Data tidak ditemukan" class="h-28 w-auto opacity-90">
                                        <p class="font-semibold text-gray-500 dark:text-gray-400">
                                            Belum ada data penggantian komponen.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> --}}

            {{-- @if($detailKomponen->hasPages())
                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                    {{ $detailKomponen->appends(request()->query())->links() }}
                </div>
            @endif
        </div> --}}

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    const labels = @json($chartLabels);
    const fullLabels = @json($chartLabelsFull);
    const values = @json($chartValues);

    let chartPenggantianKomponen = null;
    let rebuildTimer = null;

    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        return {
            gridColor: isDark ? 'rgba(148, 163, 184, 0.16)' : 'rgba(148, 163, 184, 0.22)',
            tickColor: isDark ? '#94a3b8' : '#64748b',
            labelColor: isDark ? '#f8fafc' : '#0f172a',
            tooltipBg: isDark ? 'rgba(15, 23, 42, 0.96)' : 'rgba(255, 255, 255, 0.98)',
            tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
            tooltipBody: isDark ? '#cbd5e1' : '#334155',
        };
    }

    function destroyChart() {
        if (chartPenggantianKomponen) {
            chartPenggantianKomponen.destroy();
            chartPenggantianKomponen = null;
        }
    }

    function createChart(animated = true) {
        const canvas = document.getElementById('chartPenggantianKomponen');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const theme = getChartTheme();

        destroyChart();

        chartPenggantianKomponen = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penggantian',
                    data: values,
                    backgroundColor: 'rgba(37, 99, 235, 0.95)',
                    borderRadius: 6,
                    maxBarThickness: 30,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                layout: {
                    padding: {
                        top: 24,
                        right: 12,
                        bottom: 0,
                        left: 0
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: theme.tickColor,
                            boxWidth: 10,
                            boxHeight: 10,
                            padding: 18,
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 4,
                        clamp: true,
                        color: theme.labelColor,
                        font: {
                            size: 10,
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return Number(value).toLocaleString('id-ID');
                        }
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return fullLabels[index] ?? context[0].label;
                            },
                            label: function(context) {
                                return 'Jumlah: ' + Number(context.raw).toLocaleString('id-ID') + ' Kali';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...values, 0) * 1.18,
                        title: {
                            display: true,
                            text: 'Jumlah Penggantian',
                            color: theme.tickColor,
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: theme.gridColor
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: theme.tickColor,
                            precision: 0,
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: theme.tickColor,
                            maxRotation: 50,
                            minRotation: 50,
                            autoSkip: false,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }

    createChart(true);

    window.rebuildPenggantianKomponenChart = function () {
        clearTimeout(rebuildTimer);

        rebuildTimer = setTimeout(function () {
            createChart(false);
        }, 120);
    };

    const observer = new MutationObserver(function (mutations) {
        const classChanged = mutations.some(function (mutation) {
            return mutation.type === 'attributes' && mutation.attributeName === 'class';
        });

        if (!classChanged) {
            return;
        }

        window.rebuildPenggantianKomponenChart();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
@endpush
