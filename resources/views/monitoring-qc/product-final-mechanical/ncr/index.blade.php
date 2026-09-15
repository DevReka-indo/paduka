@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-[1600px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Detail NCR QC Product & Final Mekanik
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Monitoring ketidaksesuaian hasil inspeksi mekanik.
                </p>
            </div>

            <a
                href="{{ route(
                    'monitoring-qc.product-final-mechanical.ncr.create'
                ) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg
                    bg-indigo-600 px-4 py-2 text-sm font-semibold
                    text-white shadow-sm hover:bg-indigo-700"
            >
                <i class="fa-solid fa-plus"></i>
                Tambah Detail NCR
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200
                bg-emerald-50 px-4 py-3 text-sm text-emerald-700
                dark:border-emerald-900/50 dark:bg-emerald-950/40
                dark:text-emerald-300"
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- KPI --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
            @php
                $cards = [
                    ['Total NCR', $summary['total']],
                    ['Open', $summary['open']],
                    ['Close', $summary['close']],
                    ['Pending', $summary['pending']],
                    ['Total Temuan', $summary['total_findings']],
                ];
            @endphp

            @foreach ($cards as [$label, $value])
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

        {{-- Filter --}}
        <form
            method="GET"
            class="rounded-xl border border-slate-200 bg-white
                p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="xl:col-span-2">
                    <x-input-label
                        for="search"
                        value="Pencarian"
                    />

                    <x-text-input
                        id="search"
                        name="search"
                        type="text"
                        class="mt-1 block w-full"
                        :value="request('search')"
                        placeholder="Nomor NCR, project, produk, uraian, inspector..."
                    />
                </div>

                <div>
                    <x-input-label
                        for="year"
                        value="Tahun Pelaporan"
                    />

                    <select
                        id="year"
                        name="year"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900
                            dark:text-white"
                    >
                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach ($years as $year)
                            <option
                                value="{{ $year }}"
                                @selected(
                                    (string) request('year')
                                    === (string) $year
                                )
                            >
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label
                        for="month"
                        value="Bulan"
                    />

                    <select
                        id="month"
                        name="month"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900
                            dark:text-white"
                    >
                        <option value="">
                            Semua Bulan
                        </option>

                        @foreach ($monthOptions as $month => $label)
                            <option
                                value="{{ $month }}"
                                @selected(
                                    (string) request('month')
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
                            dark:border-slate-700 dark:bg-slate-900
                            dark:text-white"
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
                        for="component_status"
                        value="Status Komponen"
                    />

                    <select
                        id="component_status"
                        name="component_status"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900
                            dark:text-white"
                    >
                        <option value="">
                            Semua Status
                        </option>

                        @foreach ($componentStatusOptions as $value => $label)
                            <option
                                value="{{ $value }}"
                                @selected(
                                    request('component_status') === $value
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label
                        for="inspector"
                        value="Inspector"
                    />

                    <select
                        id="inspector"
                        name="inspector"
                        class="mt-1 block w-full rounded-lg border-slate-300
                            dark:border-slate-700 dark:bg-slate-900
                            dark:text-white"
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

                <div>
                    <x-input-label
                        for="date_from"
                        value="Tanggal Terbit Dari"
                    />

                    <x-text-input
                        id="date_from"
                        name="date_from"
                        type="date"
                        class="mt-1 block w-full"
                        :value="request('date_from')"
                    />
                </div>

                <div>
                    <x-input-label
                        for="date_to"
                        value="Tanggal Terbit Sampai"
                    />

                    <x-text-input
                        id="date_to"
                        name="date_to"
                        type="date"
                        class="mt-1 block w-full"
                        :value="request('date_to')"
                    />
                </div>
            </div>

            <div class="mt-5 flex justify-end gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.ncr.index'
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
                    Filter
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-200
            bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200
                    dark:divide-slate-800"
                >
                    <thead class="bg-slate-50 dark:bg-slate-800/60">
                        <tr class="text-left text-xs font-semibold uppercase
                            tracking-wide text-slate-500 dark:text-slate-400"
                        >
                            <th class="px-4 py-3">Nomor NCR</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Project</th>
                            <th class="px-4 py-3">Produk / Proses</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3 text-center">Temuan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Inspector</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100
                        dark:divide-slate-800"
                    >
                        @forelse ($ncrs as $ncr)
                            <tr class="text-sm text-slate-700
                                hover:bg-slate-50/70 dark:text-slate-200
                                dark:hover:bg-slate-800/40"
                            >
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="font-semibold">
                                        {{ $ncr->ncr_number }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $monthOptions[$ncr->reporting_month] ?? '-' }}
                                        {{ $ncr->reporting_year }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    {{ $ncr->issued_date->format('d/m/Y') }}
                                </td>

                                <td class="min-w-[180px] px-4 py-3">
                                    {{ $ncr->project_name ?? '-' }}
                                </td>

                                <td class="min-w-[220px] px-4 py-3">
                                    {{ $ncr->product_name }}
                                </td>

                                <td class="min-w-[220px] px-4 py-3">
                                    {{ $ncr->nonconformity_location ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $ncr->total_findings }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($ncr->ncr_status === 'close')
                                        <span class="inline-flex rounded-full
                                            bg-emerald-100 px-2.5 py-1 text-xs
                                            font-semibold text-emerald-700
                                            dark:bg-emerald-900/30
                                            dark:text-emerald-300"
                                        >
                                            Close
                                        </span>
                                    @elseif ($ncr->ncr_status === 'open')
                                        <span class="inline-flex rounded-full
                                            bg-rose-100 px-2.5 py-1 text-xs
                                            font-semibold text-rose-700
                                            dark:bg-rose-900/30
                                            dark:text-rose-300"
                                        >
                                            Open
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full
                                            bg-slate-100 px-2.5 py-1 text-xs
                                            font-semibold text-slate-600
                                            dark:bg-slate-800
                                            dark:text-slate-300"
                                        >
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    {{ $ncr->inspector ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <a
                                        href="{{ route(
                                            'monitoring-qc.product-final-mechanical.ncr.show',
                                            $ncr
                                        ) }}"
                                        class="inline-flex h-8 w-8 items-center
                                            justify-center rounded-lg
                                            text-slate-500 hover:bg-slate-100
                                            hover:text-indigo-600
                                            dark:hover:bg-slate-800"
                                        title="Detail"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route(
                                            'monitoring-qc.product-final-mechanical.ncr.edit',
                                            $ncr
                                        ) }}"
                                        class="inline-flex h-8 w-8 items-center
                                            justify-center rounded-lg
                                            text-slate-500 hover:bg-slate-100
                                            hover:text-amber-600
                                            dark:hover:bg-slate-800"
                                        title="Edit"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="9"
                                    class="px-6 py-12 text-center text-sm
                                        text-slate-500 dark:text-slate-400"
                                >
                                    Belum ada data Detail NCR Mekanik.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ncrs->hasPages())
                <div class="border-t border-slate-200 px-4 py-4
                    dark:border-slate-800"
                >
                    {{ $ncrs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
