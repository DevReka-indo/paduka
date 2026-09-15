@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row
            sm:items-start sm:justify-between"
        >
            <div>
                <div class="text-sm font-medium text-indigo-600
                    dark:text-indigo-400"
                >
                    Detail NCR QC Product & Final Mekanik
                </div>

                <h1 class="mt-1 text-2xl font-bold
                    text-slate-900 dark:text-white"
                >
                    NCR {{ $ncr->ncr_number }}
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ $ncr->product_name }}
                </p>
            </div>

            <div class="flex gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.ncr.index'
                    ) }}"
                    class="inline-flex items-center gap-2 rounded-lg border
                        border-slate-300 px-4 py-2 text-sm font-medium
                        text-slate-700 hover:bg-slate-50
                        dark:border-slate-700 dark:text-slate-200
                        dark:hover:bg-slate-800"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.ncr.edit',
                        $ncr
                    ) }}"
                    class="inline-flex items-center gap-2 rounded-lg
                        bg-indigo-600 px-4 py-2 text-sm font-semibold
                        text-white hover:bg-indigo-700"
                >
                    <i class="fa-solid fa-pen"></i>
                    Edit
                </a>
            </div>
        </div>

        {{-- KPI --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-slate-200
                bg-white p-4 shadow-sm dark:border-slate-800
                dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Total Temuan
                </div>

                <div class="mt-2 text-2xl font-bold
                    text-slate-900 dark:text-white"
                >
                    {{ $ncr->total_findings }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200
                bg-white p-4 shadow-sm dark:border-slate-800
                dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Visual
                </div>

                <div class="mt-2 text-2xl font-bold
                    text-slate-900 dark:text-white"
                >
                    {{ $ncr->visual_qty }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200
                bg-white p-4 shadow-sm dark:border-slate-800
                dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Dimensi
                </div>

                <div class="mt-2 text-2xl font-bold
                    text-slate-900 dark:text-white"
                >
                    {{ $ncr->dimension_qty }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200
                bg-white p-4 shadow-sm dark:border-slate-800
                dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Fungsi
                </div>

                <div class="mt-2 text-2xl font-bold
                    text-slate-900 dark:text-white"
                >
                    {{ $ncr->function_qty }}
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="rounded-xl border border-slate-200 bg-white
            p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="flex flex-col gap-4 sm:flex-row
                sm:items-center sm:justify-between"
            >
                <div>
                    <div class="text-xs font-medium uppercase tracking-wide
                        text-slate-500 dark:text-slate-400"
                    >
                        Status NCR
                    </div>

                    <div class="mt-2">
                        @if ($ncr->ncr_status === 'close')
                            <span class="inline-flex rounded-full
                                bg-emerald-100 px-3 py-1.5 text-sm
                                font-semibold text-emerald-700
                                dark:bg-emerald-900/30
                                dark:text-emerald-300"
                            >
                                Close
                            </span>
                        @elseif ($ncr->ncr_status === 'open')
                            <span class="inline-flex rounded-full
                                bg-rose-100 px-3 py-1.5 text-sm
                                font-semibold text-rose-700
                                dark:bg-rose-900/30
                                dark:text-rose-300"
                            >
                                Open
                            </span>
                        @else
                            <span class="inline-flex rounded-full
                                bg-slate-100 px-3 py-1.5 text-sm
                                font-semibold text-slate-600
                                dark:bg-slate-800 dark:text-slate-300"
                            >
                                Belum Ditentukan
                            </span>
                        @endif
                    </div>
                </div>

                <div class="text-left sm:text-right">
                    <div class="text-xs font-medium uppercase tracking-wide
                        text-slate-500 dark:text-slate-400"
                    >
                        Status Komponen
                    </div>

                    <div class="mt-1 text-lg font-semibold
                        text-slate-900 dark:text-white"
                    >
                        {{ strtoupper($ncr->component_status) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi --}}
        <div class="rounded-xl border border-slate-200 bg-white
            p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"
        >
            <h2 class="mb-5 text-lg font-semibold
                text-slate-900 dark:text-white"
            >
                Informasi NCR
            </h2>

            <dl class="grid grid-cols-1 gap-x-8 gap-y-5 md:grid-cols-2">
                @php
                    $monthNames = \App\Models\QcProductFinalMechanicalNcr::monthOptions();

                    $information = [
                        'Nomor NCR' =>
                            $ncr->ncr_number,

                        'Periode Pelaporan' =>
                            ($monthNames[$ncr->reporting_month] ?? '-')
                            . ' '
                            . $ncr->reporting_year,

                        'Tanggal Terbit' =>
                            $ncr->issued_date?->format('d/m/Y') ?? '-',

                        'Project' =>
                            $ncr->project_name ?? '-',

                        'Produk / Proses' =>
                            $ncr->product_name,

                        'Lokasi Ketidaksesuaian' =>
                            $ncr->nonconformity_location ?? '-',

                        'Unit yang Dituju' =>
                            $ncr->target_unit ?? '-',

                        'Inspector' =>
                            $ncr->inspector ?? '-',

                        'Cycle Time' =>
                            $ncr->cycle_time_minutes !== null
                                ? $ncr->cycle_time_minutes . ' menit'
                                : '-',

                        'Sumber Data' =>
                            $ncr->source === 'legacy_xlsx'
                                ? 'Legacy Excel'
                                : 'PADUKA',
                    ];
                @endphp

                @foreach ($information as $label => $value)
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide
                            text-slate-500 dark:text-slate-400"
                        >
                            {{ $label }}
                        </dt>

                        <dd class="mt-1 text-sm font-medium
                            text-slate-900 dark:text-slate-100"
                        >
                            {{ $value }}
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>

        {{-- Uraian --}}
        <div class="rounded-xl border border-slate-200 bg-white
            p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"
        >
            <h2 class="mb-4 text-lg font-semibold
                text-slate-900 dark:text-white"
            >
                Uraian Ketidaksesuaian
            </h2>

            <div class="whitespace-pre-line text-sm leading-6
                text-slate-700 dark:text-slate-300"
            >{{ $ncr->nonconformity_description }}</div>
        </div>

        {{-- Daily Check --}}
        @if ($ncr->dailyCheck)
            <div class="rounded-xl border border-indigo-200
                bg-indigo-50/50 p-6
                dark:border-indigo-900/50 dark:bg-indigo-950/20"
            >
                <div class="flex flex-col gap-4 sm:flex-row
                    sm:items-center sm:justify-between"
                >
                    <div>
                        <div class="text-xs font-medium uppercase tracking-wide
                            text-indigo-600 dark:text-indigo-400"
                        >
                            Daily Check Terkait
                        </div>

                        <div class="mt-1 font-semibold
                            text-slate-900 dark:text-white"
                        >
                            {{ $ncr->dailyCheck->final_assembly_product_name }}
                        </div>

                        <div class="mt-1 text-sm
                            text-slate-500 dark:text-slate-400"
                        >
                            {{ $ncr->dailyCheck->check_date?->format('d/m/Y') }}
                            -
                            {{ $ncr->dailyCheck->project_name ?? '-' }}
                        </div>
                    </div>

                    <a
                        href="{{ route(
                            'monitoring-qc.product-final-mechanical.daily-check.show',
                            $ncr->dailyCheck
                        ) }}"
                        class="inline-flex items-center gap-2 rounded-lg
                            border border-indigo-300 bg-white
                            px-4 py-2 text-sm font-medium
                            text-indigo-700 hover:bg-indigo-50
                            dark:border-indigo-800 dark:bg-slate-900
                            dark:text-indigo-300"
                    >
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        Buka Daily Check
                    </a>
                </div>
            </div>
        @endif

        <div class="flex justify-end">
            <form
                method="POST"
                action="{{ route(
                    'monitoring-qc.product-final-mechanical.ncr.destroy',
                    $ncr
                ) }}"
                onsubmit="return confirm('Yakin ingin menghapus Detail NCR ini?')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg
                        border border-rose-300 px-4 py-2 text-sm
                        font-semibold text-rose-600 hover:bg-rose-50
                        dark:border-rose-900
                        dark:hover:bg-rose-950/30"
                >
                    <i class="fa-solid fa-trash"></i>
                    Hapus Detail NCR
                </button>
            </form>
        </div>
    </div>
@endsection
