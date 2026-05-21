@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Customer Feedback
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Feedback Pelanggan
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola dan pantau hasil survei kepuasan pelanggan.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('feedback.form') }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Form Survey
                    </a>

                    <button type="button" onclick="copySurveyLink()"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copy Link
                    </button>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-2 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('feedback.index') }}"
                   class="rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                    Data Feedback
                </a>

                <a href="{{ route('feedback.project') }}"
                   class="rounded-2xl px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                    Master Project
                </a>

                <a href="{{ route('feedback.barang') }}"
                   class="rounded-2xl px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                    Master Barang
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <form method="GET" action="{{ route('feedback.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Tahun
                    </label>
                    <select name="year" class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Caturwulan
                    </label>
                    <select name="cw" class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Caturwulan</option>
                        <option value="1" {{ $selectedCw == '1' ? 'selected' : '' }}>CW-1 (Jan - Apr)</option>
                        <option value="2" {{ $selectedCw == '2' ? 'selected' : '' }}>CW-2 (Mei - Agu)</option>
                        <option value="3" {{ $selectedCw == '3' ? 'selected' : '' }}>CW-3 (Sep - Des)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Bulan
                    </label>
                    <select name="month" class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Bulan</option>
                        @foreach([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ] as $num => $name)
                            <option value="{{ $num }}" {{ (string)$selectedMonth === (string)$num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Dari Tanggal
                    </label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Sampai Tanggal
                    </label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Terapkan
                    </button>

                    <a href="{{ route('feedback.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if($selectedYear || $selectedCw || $selectedMonth || $dateFrom || $dateTo)
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                Menampilkan data kepuasan pelanggan
                @if($dateFrom && $dateTo)
                    dari <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong>
                    sampai <strong>{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong>
                @elseif($selectedMonth)
                    bulan <strong>{{ \Carbon\Carbon::create()->month((int)$selectedMonth)->translatedFormat('F') }}</strong>
                @elseif($selectedCw)
                    pada <strong>CW-{{ $selectedCw }}</strong>
                @endif

                @if($selectedYear)
                    tahun <strong>{{ $selectedYear }}</strong>
                @endif
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Feedback</p>
                <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white">{{ number_format($feedbacks->total()) }}</p>
            </div>

            <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/30 dark:bg-emerald-900/20">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Nilai Rata-rata</p>
                <p class="mt-3 text-4xl font-bold text-emerald-700 dark:text-emerald-300">
                    {{ $avgFilteredScore ? number_format($avgFilteredScore, 2) : '—' }}
                </p>
            </div>

            <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-5 shadow-sm dark:border-indigo-900/30 dark:bg-indigo-900/20">
                <p class="text-xs font-bold uppercase tracking-wide text-indigo-700 dark:text-indigo-300">Presentase</p>
                <p class="mt-3 text-4xl font-bold text-indigo-700 dark:text-indigo-300">
                    {{ $nilaiFinal ? number_format($nilaiFinal, 2) . '%' : '—' }}
                </p>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Range Nilai</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-emerald-50 p-3 dark:bg-emerald-900/20">
                        <p class="text-xs text-emerald-700 dark:text-emerald-300">Tertinggi</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-700 dark:text-emerald-300">
                            {{ $maxFilteredScore ? number_format($maxFilteredScore, 2) : '—' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-red-50 p-3 dark:bg-red-900/20">
                        <p class="text-xs text-red-700 dark:text-red-300">Terendah</p>
                        <p class="mt-1 text-2xl font-bold text-red-700 dark:text-red-300">
                            {{ $minFilteredScore ? number_format($minFilteredScore, 2) : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Distribusi Nilai Feedback per Proyek</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top 10 proyek berdasarkan nilai rata-rata survey.</p>
                <div class="mt-5 h-72">
                    <canvas id="chartProyek"></canvas>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Distribusi Nilai Feedback per Produk</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top 10 produk berdasarkan nilai rata-rata survey.</p>
                <div class="mt-5 h-72">
                    <canvas id="chartProduk"></canvas>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <form method="GET" action="{{ route('feedback.index') }}" class="flex flex-col gap-3 md:flex-row">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, perusahaan, proyek, atau produk..."
                        class="w-full rounded-xl border-gray-200 py-2.5 pl-11 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                </div>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Cari
                </button>

                @if(request('search'))
                    <a href="{{ route('feedback.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-4 text-left font-bold">#</th>
                            <th class="px-5 py-4 text-left font-bold">Pelanggan</th>
                            <th class="px-5 py-4 text-left font-bold">Perusahaan</th>
                            <th class="px-5 py-4 text-left font-bold">Proyek</th>
                            <th class="px-5 py-4 text-left font-bold">Skor</th>
                            <th class="px-5 py-4 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($feedbacks as $index => $f)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-4 text-gray-400">
                                    {{ $feedbacks->firstItem() + $index }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $f->nama_lengkap ?: '—' }}</p>
                                    @if($f->jabatan_unit_kerja)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $f->jabatan_unit_kerja }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $f->perusahaan ?: '—' }}</td>

                                <td class="max-w-[240px] truncate px-5 py-4 text-gray-600 dark:text-gray-300" title="{{ $f->proyek }}">
                                    {{ $f->proyek ?: '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $r = $f->rata_rata;
                                        $badgeClass = $r >= 3.5
                                            ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300'
                                            : ($r >= 2.5
                                                ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300'
                                                : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300');
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                                        {{ number_format($r, 2) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('feedback.show', $f->id) }}"
                                           class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-300">
                                            Detail
                                        </a>

                                        <a href="{{ route('feedback.pdf', $f->id) }}" target="_blank"
                                           class="rounded-xl bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-300">
                                            PDF
                                        </a>

                                        <form method="POST" action="{{ route('feedback.destroy', $f->id) }}"
                                              onsubmit="return confirm('Hapus feedback dari {{ addslashes($f->nama_lengkap ?: 'pelanggan ini') }}?')">
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
                                            @if(request('search'))
                                                Tidak ada hasil untuk "{{ request('search') }}"
                                            @else
                                                Belum ada data feedback
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">
                                            Data akan muncul setelah pelanggan mengisi form survei.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($feedbacks->hasPages())
                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                    {{ $feedbacks->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const notyf = new Notyf({
    duration: 2000,
    position: { x: 'right', y: 'top' }
});

function copySurveyLink() {
    const link = "{{ url('/survey-kepuasan') }}";

    navigator.clipboard.writeText(link).then(() => {
        notyf.success('Link survey berhasil disalin');
    }).catch(() => {
        notyf.error('Gagal menyalin link');
    });
}

const proyekLabels = @json($chartProyek->pluck('proyek'));
const proyekData = @json($chartProyek->pluck('rata_rata_skor'));

const produkLabels = @json($chartProduk->pluck('identitas_barang'));
const produkData = @json($chartProduk->pluck('rata_rata_skor'));

let chartProyek = null;
let chartProduk = null;
let rebuildTimer = null;

function getChartTheme() {
    const isDark = document.documentElement.classList.contains('dark');

    return {
        gridColor: isDark ? 'rgba(148, 163, 184, 0.16)' : 'rgba(148, 163, 184, 0.22)',
        tickColor: isDark ? '#94a3b8' : '#64748b',
        tooltipBg: isDark ? 'rgba(15, 23, 42, 0.96)' : 'rgba(255, 255, 255, 0.98)',
        tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
        tooltipBody: isDark ? '#cbd5e1' : '#334155',
        donutBorderColor: isDark ? '#111827' : '#ffffff',
    };
}

function destroyCharts() {
    if (chartProyek) {
        chartProyek.destroy();
        chartProyek = null;
    }

    if (chartProduk) {
        chartProduk.destroy();
        chartProduk = null;
    }
}

function createCharts(animated = true) {
    const theme = getChartTheme();

    destroyCharts();

    const chartProyekEl = document.getElementById('chartProyek');
    const chartProdukEl = document.getElementById('chartProduk');

    if (!chartProyekEl || !chartProdukEl || typeof Chart === 'undefined') {
        return;
    }

    chartProyek = new Chart(chartProyekEl.getContext('2d'), {
        type: 'bar',
        data: {
            labels: proyekLabels,
            datasets: [{
                label: 'Rata-rata Skor',
                data: proyekData,
                backgroundColor: 'rgba(79, 70, 229, 0.88)',
                borderRadius: 10,
                maxBarThickness: 44,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: theme.tooltipBg,
                    titleColor: theme.tooltipTitle,
                    bodyColor: theme.tooltipBody,
                    borderColor: theme.gridColor,
                    borderWidth: 1,
                    padding: 12,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4,
                    grid: { color: theme.gridColor },
                    border: { display: false },
                    ticks: { stepSize: 1, color: theme.tickColor },
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: theme.tickColor, maxRotation: 35 },
                },
            },
        },
    });

    chartProduk = new Chart(chartProdukEl.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: produkLabels,
            datasets: [{
                data: produkData,
                backgroundColor: [
                    'rgba(79, 70, 229, 0.9)',
                    'rgba(16, 185, 129, 0.9)',
                    'rgba(245, 158, 11, 0.9)',
                    'rgba(239, 68, 68, 0.9)',
                    'rgba(124, 58, 237, 0.9)',
                    'rgba(6, 182, 212, 0.9)',
                    'rgba(132, 204, 22, 0.9)',
                    'rgba(244, 63, 94, 0.9)',
                    'rgba(67, 56, 202, 0.9)',
                    'rgba(20, 184, 166, 0.9)',
                ],
                borderWidth: 4,
                borderColor: theme.donutBorderColor,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: theme.tickColor,
                        boxWidth: 12,
                        padding: 14,
                        font: { size: 11 },
                    },
                },
                tooltip: {
                    backgroundColor: theme.tooltipBg,
                    titleColor: theme.tooltipTitle,
                    bodyColor: theme.tooltipBody,
                    borderColor: theme.gridColor,
                    borderWidth: 1,
                    padding: 12,
                },
            },
        },
    });
}

document.addEventListener('DOMContentLoaded', function () {
    createCharts(true);

    const observer = new MutationObserver((mutations) => {
        const classChanged = mutations.some(
            mutation => mutation.type === 'attributes' && mutation.attributeName === 'class'
        );

        if (!classChanged) return;

        clearTimeout(rebuildTimer);
        rebuildTimer = setTimeout(() => createCharts(false), 120);
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});
</script>
@endsection
