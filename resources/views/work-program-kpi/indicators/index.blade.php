@extends('layouts.app')

@section('header')
    Kelola Indikator Program Kerja & KPI
@endsection

@section('content_width', 'w-full')

@section('content')
@php
    $isProgramKerja =
        $selectedType === \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA;

    $typeLabel = $isProgramKerja
        ? 'Program Kerja'
        : 'KPI';
@endphp

<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1400px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Administrasi Indikator
                    </p>

                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Kelola Indikator
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola daftar indikator Program Kerja dan KPI.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a
                        href="{{ route('work-program-kpi.index', [
                            'tab' => $selectedType,
                        ]) }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <a
                        href="{{ route('work-program-kpi.indicators.create', [
                            'type' => $selectedType,
                        ]) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah Indikator
                    </a>
                </div>
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

        {{-- Tab --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-2 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="grid grid-cols-2 gap-2 sm:inline-grid">
                <a
                    href="{{ route('work-program-kpi.indicators.index', [
                        'type' => \App\Models\WorkIndicator::TYPE_PROGRAM_KERJA,
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
                    href="{{ route('work-program-kpi.indicators.index', [
                        'type' => \App\Models\WorkIndicator::TYPE_KPI,
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

        {{-- Data Table --}}
        <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Indikator {{ $typeLabel }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Daftar seluruh indikator aktif dan nonaktif.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                        <tr>
                            <th class="w-20 px-5 py-4 text-center font-bold">
                                Urutan
                            </th>

                            <th class="px-5 py-4 text-left font-bold">
                                Nama Indikator
                            </th>

                            <th class="w-40 px-5 py-4 text-center font-bold">
                                Periode
                            </th>

                            <th class="w-32 px-5 py-4 text-center font-bold">
                                Status
                            </th>

                            <th class="w-24 px-5 py-4 text-right font-bold">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($indicators as $indicator)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-gray-100 px-2 text-xs font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $indicator->sort_order }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $indicator->name }}
                                    </p>

                                    @if ($indicator->description)
                                        <p class="mt-1 line-clamp-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $indicator->description }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                        {{ $indicator->isMonthly()
                                            ? 'Bulanan'
                                            : 'Triwulan' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if ($indicator->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end">
                                        <a
                                            href="{{ route(
                                                'work-program-kpi.indicators.edit',
                                                $indicator
                                            ) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 shadow-sm transition hover:bg-indigo-100 dark:border-indigo-900/40 dark:bg-indigo-900/20 dark:text-indigo-300 dark:hover:bg-indigo-900/30"
                                            title="Edit indikator"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-5 py-16 text-center"
                                >
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-list-check text-4xl text-gray-300 dark:text-gray-700"></i>

                                        <p class="font-semibold text-gray-500 dark:text-gray-400">
                                            Belum ada indikator {{ $typeLabel }}.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($indicators->hasPages())
                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                    {{ $indicators->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
