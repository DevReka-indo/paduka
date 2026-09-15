@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                    QC Product & Final Mekanik
                </div>

                <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">
                    {{ $dailyCheck->final_assembly_product_name }}
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Check Date:
                    {{ $dailyCheck->check_date->format('d/m/Y') }}
                </p>
            </div>

            <div class="flex gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.daily-check.index'
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
                        'monitoring-qc.product-final-mechanical.daily-check.edit',
                        $dailyCheck
                    ) }}"
                    class="inline-flex items-center gap-2 rounded-lg
                        bg-indigo-600 px-4 py-2 text-sm font-semibold
                        text-white hover:bg-indigo-700"
                >
                    <i class="fa-solid fa-pen"></i>
                    Edit
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.product-final-mechanical.ncr.create',
                        [
                            'daily_check_id' => $dailyCheck->id,
                        ]
                    ) }}"
                    class="inline-flex items-center gap-2 rounded-lg
                        border border-indigo-300 bg-indigo-50 px-4 py-2
                        text-sm font-semibold text-indigo-700
                        hover:bg-indigo-100
                        dark:border-indigo-800 dark:bg-indigo-950/30
                        dark:text-indigo-300"
                >
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Buat NCR
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Total Temuan
                </div>

                <div class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">
                    {{ $dailyCheck->total_findings }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Qty OK
                </div>

                <div class="mt-2 text-2xl font-bold text-emerald-600">
                    {{ $dailyCheck->qty_ok }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Qty NOK
                </div>

                <div class="mt-2 text-2xl font-bold text-rose-600">
                    {{ $dailyCheck->qty_nok }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="text-xs uppercase text-slate-500">
                    Status
                </div>

                <div class="mt-2 text-lg font-bold text-slate-900 dark:text-white">
                    {{ $dailyCheck->assembly_status_label }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
            dark:border-slate-800 dark:bg-slate-900"
        >
            <h2 class="mb-5 text-lg font-semibold text-slate-900 dark:text-white">
                Informasi Inspection
            </h2>

            <dl class="grid grid-cols-1 gap-x-8 gap-y-5 md:grid-cols-2">
                @php
                    $information = [
                        'Periode' =>
                            ($dailyCheck->reporting_month ?? '-')
                            . '/'
                            . $dailyCheck->reporting_year,

                        'Project' =>
                            $dailyCheck->project_name ?? '-',

                        'Doc. Check' =>
                            $dailyCheck->document_check ?? '-',

                        'Inspection Gate' =>
                            $dailyCheck->inspection_gate ?? '-',

                        'Final Assy Product' =>
                            $dailyCheck->final_assembly_product_name,

                        'TS / Batch' =>
                            $dailyCheck->batch_reference ?? '-',

                        'Inspector' =>
                            $dailyCheck->inspector ?? '-',

                        'Status IS' =>
                            $dailyCheck->status_is ?? '-',

                        'No. NCR' =>
                            $dailyCheck->ncr_number ?? '-',

                        'Cycle Time' =>
                            $dailyCheck->cycle_time_minutes
                                ? $dailyCheck->cycle_time_minutes . ' menit'
                                : '-',
                    ];
                @endphp

                @foreach ($information as $label => $value)
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide
                            text-slate-500 dark:text-slate-400"
                        >
                            {{ $label }}
                        </dt>

                        <dd class="mt-1 text-sm font-medium text-slate-900
                            dark:text-slate-100"
                        >
                            {{ $value }}
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
            dark:border-slate-800 dark:bg-slate-900"
        >
            <h2 class="mb-5 text-lg font-semibold text-slate-900 dark:text-white">
                Temuan Berdasarkan Metode Pengecekan
            </h2>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
                @foreach (
                    [
                        'VT' => $dailyCheck->vt_qty,
                        'DM' => $dailyCheck->dm_qty,
                        'WG' => $dailyCheck->wg_qty,
                        'PT' => $dailyCheck->pt_qty,
                        'CP' => $dailyCheck->cp_qty,
                        'FT' => $dailyCheck->ft_qty,
                    ]
                    as $label => $value
                )
                    <div class="rounded-lg bg-slate-50 p-4 text-center
                        dark:bg-slate-800/60"
                    >
                        <div class="text-xs font-semibold text-slate-500">
                            {{ $label }}
                        </div>

                        <div class="mt-1 text-xl font-bold text-slate-900
                            dark:text-white"
                        >
                            {{ $value }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">
                    Sub Part Assy
                </h2>

                <div class="whitespace-pre-line text-sm leading-6 text-slate-700
                    dark:text-slate-300"
                >{{ $dailyCheck->sub_part_assembly_name ?? '-' }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
                dark:border-slate-800 dark:bg-slate-900"
            >
                <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">
                    OIL / Temuan
                </h2>

                <div class="whitespace-pre-line text-sm leading-6 text-slate-700
                    dark:text-slate-300"
                >{{ $dailyCheck->oil_description ?? '-' }}</div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm
            dark:border-slate-800 dark:bg-slate-900"
        >
            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">
                Remarks
            </h2>

            <div class="whitespace-pre-line text-sm leading-6 text-slate-700
                dark:text-slate-300"
            >{{ $dailyCheck->remarks ?? '-' }}</div>
        </div>

        <div class="flex justify-end">
            <form
                method="POST"
                action="{{ route(
                    'monitoring-qc.product-final-mechanical.daily-check.destroy',
                    $dailyCheck
                ) }}"
                onsubmit="return confirm(
                    'Yakin ingin menghapus Daily Check ini?'
                )"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg
                        border border-rose-300 px-4 py-2 text-sm font-semibold
                        text-rose-600 hover:bg-rose-50
                        dark:border-rose-900 dark:hover:bg-rose-950/30"
                >
                    <i class="fa-solid fa-trash"></i>
                    Hapus Daily Check
                </button>
            </form>
        </div>
    </div>
@endsection
