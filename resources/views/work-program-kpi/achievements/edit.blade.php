@extends('layouts.app')

@section('header')
    Input Capaian Program Kerja & KPI
@endsection

@section('content_width', 'w-full')

@section('content')
@php
    $isProgramKerja =
        $selectedType === \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA;

    $typeLabel = $isProgramKerja
        ? 'Program Kerja'
        : 'KPI';

    $indicatorColumnWidth = 24;

    $periodColumnWidth = count($periodOptions) > 0
        ? (100 - $indicatorColumnWidth) / count($periodOptions)
        : 0;
@endphp

<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-[1800px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Administrasi Capaian
                    </p>

                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Input Capaian {{ $typeLabel }}
                    </h1>

                    <p class="mt-1 max-w-3xl text-sm text-gray-500 dark:text-gray-400">
                        Masukkan persentase capaian masing-masing indikator pada setiap periode.
                    </p>
                </div>

                <a
                    href="{{ route('work-program-kpi.index', [
                        'tab' => $selectedType,
                        'year' => $selectedYear,
                    ]) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Kembali ke Monitoring
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                <i class="fa-solid fa-circle-check mt-0.5"></i>

                <span class="font-semibold">
                    {{ session('success') }}
                </span>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 dark:border-red-900/40 dark:bg-red-900/20">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>

                    <div>
                        <p class="text-sm font-semibold text-red-700 dark:text-red-300">
                            Data belum dapat disimpan.
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tab --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-2 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="grid grid-cols-2 gap-2 sm:inline-grid">
                <a
                    href="{{ route('work-program-kpi.achievements.edit', [
                        'type' => \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA,
                        'year' => $selectedYear,
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
                    href="{{ route('work-program-kpi.achievements.edit', [
                        'type' => \App\Models\WorkIndicator::TYPE_KPI,
                        'year' => $selectedYear,
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

        {{-- Filter Tahun --}}
        <div class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <form
                method="GET"
                action="{{ route('work-program-kpi.achievements.edit') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-end"
            >
                <input
                    type="hidden"
                    name="type"
                    value="{{ $selectedType }}"
                >

                <div>
                    <label
                        for="year"
                        class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300"
                    >
                        Tahun Capaian
                    </label>

                    <input
                        id="year"
                        type="number"
                        name="year"
                        value="{{ $selectedYear }}"
                        min="2000"
                        max="2100"
                        class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 sm:w-40"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <i class="fa-solid fa-calendar-check mr-2"></i>
                    Pilih Tahun
                </button>
            </form>
        </div>

        {{-- Petunjuk Pengisian --}}
        <div class="flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
            <i class="fa-solid fa-circle-info mt-0.5"></i>

            <div>
                <p class="font-semibold">
                    Petunjuk pengisian
                </p>

                <ul class="mt-1 list-inside list-disc space-y-1">
                    <li>Masukkan angka persentase tanpa tanda persen.</li>
                    <li>Gunakan tanda titik untuk angka desimal, misalnya 99.50.</li>
                    <li>Nilai yang dikosongkan akan dihapus saat data disimpan.</li>
                    <li>Progress yang belum tersedia dapat dibiarkan kosong.</li>
                </ul>
            </div>
        </div>

        {{-- Form Input Capaian --}}
        <form
            method="POST"
            action="{{ route('work-program-kpi.achievements.update') }}"
        >
            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="type"
                value="{{ $selectedType }}"
            >

            <input
                type="hidden"
                name="year"
                value="{{ $selectedYear }}"
            >

            <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                {{-- Card Header --}}
                <div class="border-b border-gray-100 px-5 py-5 dark:border-gray-800">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                Capaian {{ $typeLabel }} Tahun {{ $selectedYear }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Input capaian {{ $typeLabel }} dilakukan setiap bulan.
                            </p>
                        </div>

                        <span class="inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300">
                            {{ $indicators->count() }} indikator
                        </span>
                    </div>
                </div>

                {{-- Tabel Input --}}
                <div class="w-full overflow-hidden">
                    <table class="w-full table-fixed text-xs">
                        <colgroup>
                            {{-- Kolom indikator --}}
                            <col style="width: {{ $indicatorColumnWidth }}%">

                            {{-- Kolom periode --}}
                            @foreach ($periodOptions as $periodLabel)
                                <col style="width: {{ $periodColumnWidth }}%">
                            @endforeach
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

                                        @if ($indicator->description)
                                            <p
                                                class="mt-1 line-clamp-2 text-[10px] leading-relaxed text-gray-500 dark:text-gray-400"
                                                title="{{ $indicator->description }}"
                                            >
                                                {{ $indicator->description }}
                                            </p>
                                        @endif
                                    </td>

                                    {{-- Input Setiap Periode --}}
                                    @foreach ($periodOptions as $periodNumber => $periodLabel)
                                        @php
                                            $achievement = $achievementMap->get(
                                                $periodNumber
                                            );

                                            $fieldName =
                                                "achievements.{$indicator->id}.{$periodNumber}";

                                            $fieldValue = old(
                                                $fieldName,
                                                $achievement?->percentage
                                            );
                                        @endphp

                                        <td class="px-1.5 py-4 align-middle">
                                            <div class="relative">
                                                <input
                                                    type="number"
                                                    name="achievements[{{ $indicator->id }}][{{ $periodNumber }}]"
                                                    value="{{ $fieldValue }}"
                                                    min="0"
                                                    max="99999.99"
                                                    step="0.01"
                                                    placeholder="-"
                                                    aria-label="{{ $indicator->name }} - {{ $periodLabel }}"
                                                    class="w-full min-w-0 rounded-lg border-gray-200 py-2 pl-1 pr-5 text-center text-[11px] font-semibold text-gray-800 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                                >

                                                <span class="pointer-events-none absolute inset-y-0 right-1.5 flex items-center text-[9px] font-bold text-gray-400">
                                                    %
                                                </span>
                                            </div>

                                            @error($fieldName)
                                                <p class="mt-1 text-[9px] leading-tight text-red-500">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="{{ count($periodOptions) + 1 }}"
                                        class="px-5 py-16 text-center"
                                    >
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="fa-solid fa-chart-simple text-4xl text-gray-300 dark:text-gray-700"></i>

                                            <p class="font-semibold text-gray-500 dark:text-gray-400">
                                                Belum ada indikator aktif.
                                            </p>

                                            <a
                                                href="{{ route('work-program-kpi.indicators.create', [
                                                    'type' => $selectedType,
                                                ]) }}"
                                                class="text-sm font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                                            >
                                                Tambahkan indikator
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Form Footer --}}
                @if ($indicators->isNotEmpty())
                    <div class="flex flex-col gap-3 border-t border-gray-100 px-5 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Pastikan seluruh data telah diperiksa sebelum disimpan.
                        </p>

                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('work-program-kpi.index', [
                                    'tab' => $selectedType,
                                    'year' => $selectedYear,
                                ]) }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                            >
                                <i class="fa-solid fa-floppy-disk mr-2"></i>
                                Simpan Capaian
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </form>

    </div>
</div>
@endsection
