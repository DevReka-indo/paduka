@extends('layouts.app')

@section('header')
    Program Kerja & KPI
@endsection

@section('content_width', 'w-full')

@section('content')
@php
    $level = strtolower(auth()->user()->level ?? '');

    $canManage = in_array(
        $level,
        ['admin', 'superadmin'],
        true
    );

    $isProgramKerja =
        $selectedTab === \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA;

    $tabLabel = $isProgramKerja
        ? 'Program Kerja'
        : 'KPI';

    $averageDisplay = $averageAchievement !== null
        ? number_format($averageAchievement, 2, ',', '.') . '%'
        : '-';

    $averageProgress = $averageAchievement !== null
        ? min(max($averageAchievement, 0), 100)
        : 0;

    /*
     * Pembagian lebar tabel agar seluruh kolom
     * tetap berada di dalam lebar layar.
     */
    $indicatorColumnWidth = $isProgramKerja
        ? 24
        : 40;

    $latestColumnWidth = $isProgramKerja
        ? 10
        : 15;

    $availablePeriodWidth =
        100
        - $indicatorColumnWidth
        - $latestColumnWidth;

    $periodColumnWidth = count($periodOptions) > 0
        ? $availablePeriodWidth / count($periodOptions)
        : 0;
@endphp

<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Quality Control
                    </p>

                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Program Kerja & KPI
                    </h1>

                    <p class="mt-1 max-w-3xl text-sm text-gray-500 dark:text-gray-400">
                        Monitoring pencapaian Program Kerja dan Key Performance Indicator Unit Quality Control.
                    </p>
                </div>

                @if ($canManage)
                    <div class="flex flex-wrap items-center gap-2">
                        <a
                            href="{{ route('work-program-kpi.indicators.index', [
                                'type' => $selectedTab,
                            ]) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-list-check text-gray-400"></i>
                            Kelola Indikator
                        </a>

                        <a
                            href="{{ route('work-program-kpi.achievements.edit', [
                                'type' => $selectedTab,
                                'year' => $selectedYear,
                            ]) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                            Input Capaian
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-2 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="grid grid-cols-2 gap-2 sm:inline-grid">
                <a
                    href="{{ route('work-program-kpi.index', [
                        'tab' => \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA,
                        'year' => $selectedYear,
                        'month' => $selectedMonth,
                    ]) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition
                        {{ $isProgramKerja
                            ? 'bg-indigo-600 text-white shadow-sm'
                            : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}"
                >
                    <i class="fa-solid fa-clipboard-list"></i>
                    Program Kerja
                </a>

                <a
                    href="{{ route('work-program-kpi.index', [
                        'tab' => \App\Models\WorkIndicator::TYPE_KPI,
                        'year' => $selectedYear,
                        'month' => $selectedMonth,
                    ]) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition
                        {{ !$isProgramKerja
                            ? 'bg-indigo-600 text-white shadow-sm'
                            : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}"
                >
                    <i class="fa-solid fa-bullseye"></i>
                    KPI
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <div class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <form
                method="GET"
                action="{{ route('work-program-kpi.index') }}"
                class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between"
            >
                <input
                    type="hidden"
                    name="tab"
                    value="{{ $selectedTab }}"
                >

                <div class="flex flex-col gap-3 sm:flex-row">
                    <div>
                        <label
                            for="month"
                            class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                        >
                            Bulan
                        </label>

                        <select
                            id="month"
                            name="month"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 sm:w-48"
                        >
                            @foreach ($monthOptions as $month => $monthName)
                                <option
                                    value="{{ $month }}"
                                    @selected($selectedMonth === $month)
                                >
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            for="year"
                            class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                        >
                            Tahun
                        </label>

                        <select
                            id="year"
                            name="year"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 sm:w-36"
                        >
                            @foreach ($availableYears as $year)
                                <option
                                    value="{{ $year }}"
                                    @selected($selectedYear === (int) $year)
                                >
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <i class="fa-solid fa-filter mr-2"></i>
                            Terapkan
                        </button>

                        <a
                            href="{{ route('work-program-kpi.index', [
                                'tab' => $selectedTab,
                            ]) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-rotate-left mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan capaian hingga
                    <strong class="text-gray-700 dark:text-gray-200">
                        {{ $monthOptions[$selectedMonth] }} {{ $selectedYear }}
                    </strong>
                </div>
            </form>
        </div>

        {{-- Summary --}}
        <div class="grid gap-5 lg:grid-cols-3">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-lg shadow-indigo-500/20 lg:col-span-2">
                <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-100">
                                Rata-rata capaian {{ $tabLabel }}
                            </p>

                            <p class="mt-3 text-4xl font-black tracking-tight">
                                {{ $averageDisplay }}
                            </p>

                            <p class="mt-2 text-sm text-indigo-100">
                                Berdasarkan capaian terakhir setiap indikator sampai periode yang dipilih.
                            </p>
                        </div>

                        <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/15 ring-1 ring-white/20">
                            <i class="fa-solid fa-chart-line text-3xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 h-3 overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full rounded-full bg-white transition-all duration-500"
                            style="width: {{ $averageProgress }}%"
                        ></div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-400">
                            Indikator Aktif
                        </p>

                        <p class="mt-3 text-3xl font-black text-gray-900 dark:text-white">
                            {{ $indicators->count() }}
                        </p>
                    </div>

                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-300">
                        <i class="fa-solid fa-list-check text-lg"></i>
                    </span>
                </div>

                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Total indikator {{ $tabLabel }} yang aktif dan ditampilkan.
                </p>
            </div>
        </div>

        {{-- Progress per Indicator --}}
        <div class="rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Capaian {{ $tabLabel }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Progress masing-masing indikator hingga periode terakhir yang dipilih.
                </p>
            </div>

            <div class="grid gap-4 p-6 lg:grid-cols-2">
                @forelse ($indicators as $indicator)
                    @php
                        $latestPercentage = $indicator->latest_percentage;

                        $progressWidth = $latestPercentage !== null
                            ? min(max($latestPercentage, 0), 100)
                            : 0;

                        $latestPeriod = $indicator->latest_achievement
                            ? $indicator->latest_achievement->periodLabel()
                            : null;
                    @endphp

                    <div class="rounded-2xl border border-gray-100 p-5 transition hover:border-indigo-200 hover:shadow-sm dark:border-gray-800 dark:hover:border-indigo-900/50">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-semibold leading-relaxed text-gray-900 dark:text-white">
                                    {{ $indicator->name }}
                                </p>

                                @if ($indicator->description)
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $indicator->description }}
                                    </p>
                                @endif
                            </div>

                            <span class="shrink-0 rounded-xl bg-indigo-50 px-3 py-1.5 text-sm font-bold text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300">
                                {{ $latestPercentage !== null
                                    ? number_format($latestPercentage, 2, ',', '.') . '%'
                                    : '-' }}
                            </span>
                        </div>

                        <div class="mt-5 h-2.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500"
                                style="width: {{ $progressWidth }}%"
                            ></div>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                            <span>
                                {{ $latestPeriod
                                    ? 'Data terakhir: ' . $latestPeriod
                                    : 'Belum ada data capaian' }}
                            </span>

                            <span>{{ $selectedYear }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <i class="fa-solid fa-chart-simple text-4xl text-gray-300 dark:text-gray-700"></i>

                        <p class="mt-4 font-semibold text-gray-500 dark:text-gray-400">
                            Belum ada indikator aktif.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Detail Table --}}
        <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Detail Capaian {{ $tabLabel }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Persentase capaian sampai
                    {{ $monthOptions[$selectedMonth] }}
                    {{ $selectedYear }}.
                </p>
            </div>

            <div class="w-full overflow-hidden">
                <table class="w-full table-fixed text-xs">
                    <colgroup>
                        {{-- Kolom indikator --}}
                        <col style="width: {{ $indicatorColumnWidth }}%">

                        {{-- Kolom periode --}}
                        @foreach ($periodOptions as $periodLabel)
                            <col style="width: {{ $periodColumnWidth }}%">
                        @endforeach

                        {{-- Kolom capaian terakhir --}}
                        <col style="width: {{ $latestColumnWidth }}%">
                    </colgroup>

                    <thead class="bg-gray-50 uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                        <tr>
                            <th class="px-3 py-4 text-left font-bold">
                                Indikator
                            </th>

                            @foreach ($periodOptions as $periodLabel)
                                <th class="px-1 py-4 text-center text-[10px] font-bold leading-tight">
                                    {{ $periodLabel }}
                                </th>
                            @endforeach

                            <th class="px-1 py-4 text-center text-[10px] font-bold leading-tight">
                                Capaian Terakhir
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($indicators as $indicator)
                            @php
                                $achievementMap = $indicator
                                    ->achievements
                                    ->keyBy('period_number');
                            @endphp

                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">

                                {{-- Nama Indikator --}}
                                <td class="px-3 py-4 align-middle">
                                    <p
                                        class="text-xs font-semibold leading-relaxed text-gray-900 dark:text-white"
                                        title="{{ $indicator->name }}"
                                    >
                                        {{ $indicator->name }}
                                    </p>
                                </td>

                                {{-- Nilai Setiap Periode --}}
                                @foreach ($periodOptions as $periodNumber => $periodLabel)
                                    @php
                                        $achievement = $achievementMap->get(
                                            $periodNumber
                                        );
                                    @endphp

                                    <td class="px-1 py-4 text-center align-middle">
                                        @if ($achievement)
                                            <span
                                                class="inline-flex max-w-full justify-center rounded-lg bg-indigo-50 px-1.5 py-1 text-[10px] font-bold text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300"
                                                title="{{
                                                    number_format(
                                                        (float) $achievement->percentage,
                                                        2,
                                                        ',',
                                                        '.'
                                                    )
                                                }}%"
                                            >
                                                {{
                                                    number_format(
                                                        (float) $achievement->percentage,
                                                        2,
                                                        ',',
                                                        '.'
                                                    )
                                                }}%
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-700">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                @endforeach

                                {{-- Capaian Terakhir --}}
                                <td class="px-1 py-4 text-center align-middle">
                                    @if ($indicator->latest_percentage !== null)
                                        <span
                                            class="inline-flex max-w-full justify-center rounded-lg bg-emerald-50 px-1.5 py-1 text-[10px] font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300"
                                            title="{{
                                                number_format(
                                                    $indicator->latest_percentage,
                                                    2,
                                                    ',',
                                                    '.'
                                                )
                                            }}%"
                                        >
                                            {{
                                                number_format(
                                                    $indicator->latest_percentage,
                                                    2,
                                                    ',',
                                                    '.'
                                                )
                                            }}%
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-700">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="{{ count($periodOptions) + 2 }}"
                                    class="px-5 py-14 text-center text-gray-500 dark:text-gray-400"
                                >
                                    Belum ada data indikator.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
