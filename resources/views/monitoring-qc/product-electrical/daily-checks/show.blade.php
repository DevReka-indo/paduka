@extends('layouts.app')

@section('header')
    Detail Daily Check QC Product Elektrik
@endsection

@section('content_width', 'w-full')

@section('content')
    @php
        $statusClasses = [
            'close' => [
                'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
                'icon' => 'fa-circle-check',
            ],
            'open' => [
                'badge' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
                'icon' => 'fa-circle-xmark',
            ],
            'pending' => [
                'badge' => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
                'icon' => 'fa-clock',
            ],
        ];

        $resultLabels = [
            'pending' => 'Belum Ditentukan',
            'ok' => 'OK',
            'nok' => 'NOK',
        ];

        $resultClasses = [
            'pending' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300',
            'ok' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
            'nok' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-300',
        ];

        $findingCategories = [
            [
                'label' => 'Visual',
                'short' => 'VT',
                'value' => $dailyCheck->visual_qty,
                'icon' => 'fa-eye',
            ],
            [
                'label' => 'Skun',
                'short' => 'SK',
                'value' => $dailyCheck->skun_qty,
                'icon' => 'fa-plug',
            ],
            [
                'label' => 'Cramping',
                'short' => 'CR',
                'value' => $dailyCheck->cramping_qty,
                'icon' => 'fa-compress',
            ],
            [
                'label' => 'Marking',
                'short' => 'MK',
                'value' => $dailyCheck->marking_qty,
                'icon' => 'fa-tag',
            ],
            [
                'label' => 'Belltest',
                'short' => 'BT',
                'value' => $dailyCheck->belltest_qty,
                'icon' => 'fa-bell',
            ],
            [
                'label' => 'Function',
                'short' => 'FT',
                'value' => $dailyCheck->function_qty,
                'icon' => 'fa-gears',
            ],
        ];

        $ncrCategoryLabel = $dailyCheck->ncr_category
            ? (
                \App\Models\QcProductElectricalDailyCheck::ncrCategoryOptions()[
                    $dailyCheck->ncr_category
                ] ?? ucfirst($dailyCheck->ncr_category)
            )
            : '-';

        $creatorName = $dailyCheck->creator?->name
            ?? $dailyCheck->creator?->nama
            ?? '-';

        $updaterName = $dailyCheck->updater?->name
            ?? $dailyCheck->updater?->nama
            ?? '-';

        $statusConfig = $statusClasses[$dailyCheck->status_product]
            ?? $statusClasses['pending'];

        $sourceLabel = match ($dailyCheck->source) {
            'legacy_xlsx' => 'Import Excel',
            default => 'Input PADUKA',
        };
    @endphp

    <div
        x-data="{ openDeleteModal: false }"
        class="min-h-screen bg-slate-50 px-4 py-6
            dark:bg-gray-950 sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-[1600px] space-y-6">

            {{-- Success Alert --}}
            @if (session('success'))
                <div
                    class="flex items-start gap-3 rounded-2xl border
                        border-emerald-200 bg-emerald-50 px-5 py-4
                        text-sm text-emerald-700
                        dark:border-emerald-900/40 dark:bg-emerald-900/20
                        dark:text-emerald-300"
                >
                    <i class="fa-solid fa-circle-check mt-0.5"></i>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- Header --}}
            <div
                class="relative overflow-hidden rounded-3xl border
                    border-white/70 bg-white p-6 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="absolute -right-20 -top-20 h-56 w-56
                        rounded-full bg-indigo-500/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-24 left-10 h-56 w-56
                        rounded-full bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-6
                        lg:flex-row lg:items-end lg:justify-between"
                >
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Status Product --}}
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1
                                    text-xs font-bold ring-1 ring-inset
                                    {{ $statusConfig['badge'] }}"
                            >
                                <i
                                    class="fa-solid {{ $statusConfig['icon'] }} mr-1.5"
                                ></i>

                                {{ $dailyCheck->status_product_label }}
                            </span>

                            {{-- Result --}}
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1
                                    text-xs font-bold
                                    {{ $resultClasses[$dailyCheck->result]
                                        ?? $resultClasses['pending'] }}"
                            >
                                Result:
                                {{ $resultLabels[$dailyCheck->result]
                                    ?? ucfirst($dailyCheck->result) }}
                            </span>

                            {{-- Source --}}
                            <span
                                class="inline-flex items-center rounded-full
                                    bg-indigo-50 px-3 py-1 text-xs font-bold
                                    text-indigo-700 ring-1 ring-indigo-600/10
                                    dark:bg-indigo-500/15 dark:text-indigo-300
                                    dark:ring-indigo-400/20"
                            >
                                <i class="fa-solid fa-database mr-1.5"></i>

                                {{ $sourceLabel }}
                            </span>
                        </div>

                        <p
                            class="mt-5 text-xs font-extrabold uppercase
                                tracking-[0.18em] text-indigo-600
                                dark:text-indigo-400"
                        >
                            Monitoring QC · QC Product Elektrik
                        </p>

                        <h1
                            class="mt-2 break-words text-2xl font-extrabold
                                tracking-tight text-gray-900
                                dark:text-white sm:text-3xl"
                        >
                            {{ $dailyCheck->product_name }}
                        </h1>

                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ $dailyCheck->check_date->format('d/m/Y') }}
                            ·
                            {{ $dailyCheck->project_name }}
                            ·
                            {{ $dailyCheck->inspection_gate }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route(
                                'monitoring-qc.product-electrical.daily-check.index'
                            ) }}"
                            class="inline-flex items-center justify-center rounded-xl
                                border border-gray-200 bg-white px-4 py-2.5
                                text-sm font-semibold text-gray-700 shadow-sm
                                transition hover:bg-gray-50
                                dark:border-gray-700 dark:bg-gray-800
                                dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                        <a
                            href="{{ route(
                                'monitoring-qc.product-electrical.daily-check.edit',
                                $dailyCheck
                            ) }}"
                            class="inline-flex items-center justify-center rounded-xl
                                bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                                text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <i class="fa-solid fa-pen-to-square mr-2"></i>
                            Edit
                        </a>

                        <button
                            type="button"
                            @click="openDeleteModal = true"
                            class="inline-flex items-center justify-center rounded-xl
                                border border-red-200 bg-red-50 px-4 py-2.5
                                text-sm font-semibold text-red-600 shadow-sm
                                transition hover:bg-red-100 hover:text-red-700
                                dark:border-red-900/40 dark:bg-red-500/10
                                dark:text-red-300 dark:hover:bg-red-500/20"
                        >
                            <i class="fa-solid fa-trash mr-2"></i>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">

                {{-- Total Temuan --}}
                <div
                    class="rounded-3xl border border-white/70 bg-white p-5
                        shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="flex h-10 w-10 items-center justify-center
                                rounded-2xl bg-amber-50 text-amber-600
                                dark:bg-amber-500/15 dark:text-amber-300"
                        >
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>

                        <span class="text-xs font-bold text-gray-400">
                            DEFECT
                        </span>
                    </div>

                    <p class="mt-4 text-3xl font-black text-gray-900 dark:text-white">
                        {{ number_format($dailyCheck->total_findings) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Total Temuan
                    </p>
                </div>

                {{-- Produk OK --}}
                <div
                    class="rounded-3xl border border-white/70 bg-white p-5
                        shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="flex h-10 w-10 items-center justify-center
                                rounded-2xl bg-emerald-50 text-emerald-600
                                dark:bg-emerald-500/15 dark:text-emerald-300"
                        >
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </span>

                        <span class="text-xs font-bold text-emerald-500">
                            OK
                        </span>
                    </div>

                    <p class="mt-4 text-3xl font-black text-gray-900 dark:text-white">
                        {{ number_format($dailyCheck->product_ok_qty) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Produk OK
                    </p>
                </div>

                {{-- Produk NOK --}}
                <div
                    class="rounded-3xl border border-white/70 bg-white p-5
                        shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="flex h-10 w-10 items-center justify-center
                                rounded-2xl bg-red-50 text-red-600
                                dark:bg-red-500/15 dark:text-red-300"
                        >
                            <i class="fa-solid fa-box-open"></i>
                        </span>

                        <span class="text-xs font-bold text-red-500">
                            NOK
                        </span>
                    </div>

                    <p class="mt-4 text-3xl font-black text-gray-900 dark:text-white">
                        {{ number_format($dailyCheck->product_nok_qty) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Produk NOK
                    </p>
                </div>

                {{-- Kabel OK --}}
                <div
                    class="rounded-3xl border border-white/70 bg-white p-5
                        shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="flex h-10 w-10 items-center justify-center
                                rounded-2xl bg-emerald-50 text-emerald-600
                                dark:bg-emerald-500/15 dark:text-emerald-300"
                        >
                            <i class="fa-solid fa-circle-check"></i>
                        </span>

                        <span class="text-xs font-bold text-emerald-500">
                            OK
                        </span>
                    </div>

                    <p class="mt-4 text-3xl font-black text-gray-900 dark:text-white">
                        {{ number_format($dailyCheck->cable_ok_qty) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kabel OK
                    </p>
                </div>

                {{-- Kabel NOK --}}
                <div
                    class="rounded-3xl border border-white/70 bg-white p-5
                        shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="flex h-10 w-10 items-center justify-center
                                rounded-2xl bg-red-50 text-red-600
                                dark:bg-red-500/15 dark:text-red-300"
                        >
                            <i class="fa-solid fa-circle-xmark"></i>
                        </span>

                        <span class="text-xs font-bold text-red-500">
                            NOK
                        </span>
                    </div>

                    <p class="mt-4 text-3xl font-black text-gray-900 dark:text-white">
                        {{ number_format($dailyCheck->cable_nok_qty) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kabel NOK
                    </p>
                </div>
            </div>

            {{-- Main --}}
            <div
                class="grid gap-6
                    xl:grid-cols-[minmax(0,5fr)_minmax(360px,2fr)]"
            >
                {{-- Left --}}
                <div class="space-y-6">

                    {{-- Informasi Pemeriksaan --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Informasi Pemeriksaan
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Identitas utama pemeriksaan Daily Check.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 items-center justify-center
                                    rounded-2xl bg-indigo-50 text-indigo-600
                                    dark:bg-indigo-500/15 dark:text-indigo-300"
                            >
                                <i class="fa-solid fa-clipboard-check"></i>
                            </span>
                        </div>

                        <dl
                            class="mt-6 grid overflow-hidden rounded-2xl
                                border border-gray-100
                                dark:border-gray-800 md:grid-cols-2"
                        >
                            @foreach ([
                                'Tanggal Pemeriksaan' => $dailyCheck->check_date->format('d/m/Y'),
                                'Proyek' => $dailyCheck->project_name,
                                'Kode Proyek' => $dailyCheck->project?->kode_proyek ?? '-',
                                'Doc. Check' => $dailyCheck->document_check,
                                'Inspection Gate' => $dailyCheck->inspection_gate,
                                'Check Category' => $dailyCheck->check_category,
                                'Product Name' => $dailyCheck->product_name,
                                'TS / Batch / Car' => $dailyCheck->batch_reference ?: '-',
                                'Inspector' => $dailyCheck->inspector,
                                'Cycle Time Check' => $dailyCheck->cycle_time_minutes !== null
                                    ? number_format((float) $dailyCheck->cycle_time_minutes, 2) . ' menit'
                                    : '-',
                            ] as $label => $value)
                                <div
                                    class="border-b border-gray-100 px-5 py-4
                                        odd:md:border-r
                                        dark:border-gray-800"
                                >
                                    <dt
                                        class="text-xs font-bold uppercase
                                            tracking-wider text-gray-400"
                                    >
                                        {{ $label }}
                                    </dt>

                                    <dd
                                        class="mt-1 break-words text-sm font-semibold
                                            text-gray-900 dark:text-gray-100"
                                    >
                                        {{ $value }}
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>

                    {{-- Kategori Temuan --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div
                            class="flex flex-col gap-4
                                sm:flex-row sm:items-start sm:justify-between"
                        >
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Kategori Temuan
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Jumlah defect berdasarkan kategori.
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-amber-200
                                    bg-amber-50 px-5 py-3 text-center
                                    dark:border-amber-900/40 dark:bg-amber-900/20"
                            >
                                <p
                                    class="text-xs font-bold uppercase
                                        tracking-wider text-amber-600"
                                >
                                    Total
                                </p>

                                <p
                                    class="mt-1 text-2xl font-black
                                        text-amber-700 dark:text-amber-300"
                                >
                                    {{ number_format($dailyCheck->total_findings) }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-6 grid gap-4
                                sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6"
                        >
                            @foreach ($findingCategories as $category)
                                <div
                                    class="rounded-2xl border p-4
                                        {{ $category['value'] > 0
                                            ? 'border-amber-200 bg-amber-50 dark:border-amber-900/40 dark:bg-amber-900/20'
                                            : 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/70' }}"
                                >
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="flex h-9 w-9 items-center
                                                justify-center rounded-xl bg-white
                                                text-indigo-600 shadow-sm
                                                dark:bg-gray-900 dark:text-indigo-300"
                                        >
                                            <i class="fa-solid {{ $category['icon'] }}"></i>
                                        </span>

                                        <span
                                            class="rounded-lg bg-gray-200 px-2 py-1
                                                text-[10px] font-black tracking-wider
                                                text-gray-600
                                                dark:bg-gray-700 dark:text-gray-300"
                                        >
                                            {{ $category['short'] }}
                                        </span>
                                    </div>

                                    <p
                                        class="mt-4 text-sm font-semibold
                                            text-gray-500 dark:text-gray-400"
                                    >
                                        {{ $category['label'] }}
                                    </p>

                                    <p
                                        class="mt-1 text-2xl font-black
                                            text-gray-900 dark:text-white"
                                    >
                                        {{ number_format($category['value']) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- OIL --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    OIL / Uraian Temuan
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Detail temuan hasil pemeriksaan.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 items-center justify-center
                                    rounded-2xl bg-amber-50 text-amber-600
                                    dark:bg-amber-500/15 dark:text-amber-300"
                            >
                                <i class="fa-solid fa-file-lines"></i>
                            </span>
                        </div>

                        @if ($dailyCheck->oil_description)
                            <div
                                class="mt-6 rounded-2xl bg-slate-50 px-5 py-4
                                    text-sm leading-7 text-gray-700
                                    dark:bg-white/[0.04] dark:text-gray-300"
                            >
                                {!! nl2br(e($dailyCheck->oil_description)) !!}
                            </div>
                        @else
                            <div
                                class="mt-6 rounded-2xl border border-dashed
                                    border-gray-300 px-5 py-8 text-center
                                    text-sm text-gray-400
                                    dark:border-gray-700 dark:text-gray-500"
                            >
                                Uraian temuan belum tersedia.
                            </div>
                        @endif

                        @if ($dailyCheck->oil_link)
                            <a
                                href="{{ $dailyCheck->oil_link }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex items-center rounded-xl
                                    bg-indigo-50 px-4 py-2.5 text-sm font-semibold
                                    text-indigo-700 transition hover:bg-indigo-100
                                    dark:bg-indigo-500/15 dark:text-indigo-300"
                            >
                                <i class="fa-solid fa-arrow-up-right-from-square mr-2"></i>

                                Buka Link OIL
                            </a>
                        @endif
                    </section>

                    {{-- Remarks --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Remarks
                        </h2>

                        @if ($dailyCheck->remarks)
                            <div
                                class="mt-5 rounded-2xl bg-slate-50 px-5 py-4
                                    text-sm leading-7 text-gray-700
                                    dark:bg-white/[0.04] dark:text-gray-300"
                            >
                                {!! nl2br(e($dailyCheck->remarks)) !!}
                            </div>
                        @else
                            <p class="mt-5 text-sm text-gray-400">
                                Tidak ada remarks.
                            </p>
                        @endif
                    </section>
                </div>

                {{-- Right --}}
                <aside class="space-y-6">

                    {{-- Status --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Status Pemeriksaan
                        </h2>

                        <dl class="mt-6 space-y-4">
                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Result
                                </dt>

                                <dd class="mt-2">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1
                                            text-xs font-bold
                                            {{ $resultClasses[$dailyCheck->result]
                                                ?? $resultClasses['pending'] }}"
                                    >
                                        {{ $resultLabels[$dailyCheck->result]
                                            ?? ucfirst($dailyCheck->result) }}
                                    </span>
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Status Product
                                </dt>

                                <dd class="mt-2">
                                    <span
                                        class="inline-flex items-center rounded-full
                                            px-3 py-1 text-xs font-bold ring-1
                                            ring-inset {{ $statusConfig['badge'] }}"
                                    >
                                        <i
                                            class="fa-solid
                                                {{ $statusConfig['icon'] }} mr-1.5"
                                        ></i>

                                        {{ $dailyCheck->status_product_label }}
                                    </span>
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Status IS
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold
                                        text-gray-900 dark:text-gray-100"
                                >
                                    {{ $dailyCheck->status_is ?: '-' }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Closing OIL Date
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold
                                        text-gray-900 dark:text-gray-100"
                                >
                                    {{ $dailyCheck->closing_oil_date
                                        ? $dailyCheck->closing_oil_date->format('d/m/Y')
                                        : '-' }}
                                </dd>
                            </div>
                        </dl>
                    </section>

                    {{-- NCR --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Informasi NCR
                        </h2>

                        @if ($dailyCheck->ncr_number)
                            <dl class="mt-6 space-y-4">
                                <div
                                    class="rounded-2xl bg-red-50 px-4 py-3
                                        dark:bg-red-500/10"
                                >
                                    <dt class="text-xs font-bold uppercase text-red-400">
                                        Nomor NCR
                                    </dt>

                                    <dd
                                        class="mt-1 break-words text-sm font-bold
                                            text-red-700 dark:text-red-300"
                                    >
                                        {{ $dailyCheck->ncr_number }}
                                    </dd>
                                </div>

                                <div
                                    class="rounded-2xl bg-slate-50 px-4 py-3
                                        dark:bg-white/[0.04]"
                                >
                                    <dt class="text-xs font-bold uppercase text-gray-400">
                                        Kategori
                                    </dt>

                                    <dd
                                        class="mt-1 text-sm font-semibold
                                            text-gray-900 dark:text-gray-100"
                                    >
                                        {{ $ncrCategoryLabel }}
                                    </dd>
                                </div>
                            </dl>
                        @else
                            <div
                                class="mt-6 rounded-2xl border border-dashed
                                    border-gray-300 px-4 py-8 text-center
                                    dark:border-gray-700"
                            >
                                <i
                                    class="fa-solid fa-file-circle-check
                                        text-3xl text-gray-300 dark:text-gray-600"
                                ></i>

                                <p
                                    class="mt-3 text-sm font-semibold
                                        text-gray-500 dark:text-gray-400"
                                >
                                    Tidak ada NCR
                                </p>
                            </div>
                        @endif
                    </section>

                    {{-- Audit --}}
                    <section
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Riwayat Data
                        </h2>

                        <dl class="mt-6 space-y-4">
                            <div>
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Dibuat Oleh
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold
                                        text-gray-900 dark:text-gray-100"
                                >
                                    {{ $creatorName }}
                                </dd>

                                <dd class="mt-1 text-xs text-gray-400">
                                    {{ $dailyCheck->created_at
                                        ? $dailyCheck->created_at->format('d/m/Y H:i')
                                        : '-' }}
                                </dd>
                            </div>

                            <div
                                class="border-t border-gray-100 pt-4
                                    dark:border-gray-800"
                            >
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Terakhir Diperbarui
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold
                                        text-gray-900 dark:text-gray-100"
                                >
                                    {{ $updaterName }}
                                </dd>

                                <dd class="mt-1 text-xs text-gray-400">
                                    {{ $dailyCheck->updated_at
                                        ? $dailyCheck->updated_at->format('d/m/Y H:i')
                                        : '-' }}
                                </dd>
                            </div>

                            <div
                                class="border-t border-gray-100 pt-4
                                    dark:border-gray-800"
                            >
                                <dt class="text-xs font-bold uppercase text-gray-400">
                                    Sumber Data
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold
                                        text-gray-900 dark:text-gray-100"
                                >
                                    {{ $sourceLabel }}
                                </dd>
                            </div>
                        </dl>
                    </section>
                </aside>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div
            x-cloak
            x-show="openDeleteModal"
            x-transition.opacity
            @keydown.escape.window="openDeleteModal = false"
            class="fixed inset-0 z-[100] flex items-center justify-center
                bg-gray-950/60 px-4 backdrop-blur-sm"
        >
            <div
                x-show="openDeleteModal"
                x-transition
                @click.outside="openDeleteModal = false"
                class="w-full max-w-md rounded-3xl border border-white/70
                    bg-white p-6 shadow-2xl
                    dark:border-gray-700 dark:bg-gray-900"
            >
                <span
                    class="flex h-14 w-14 items-center justify-center
                        rounded-2xl bg-red-50 text-xl text-red-600
                        dark:bg-red-500/15 dark:text-red-300"
                >
                    <i class="fa-solid fa-trash"></i>
                </span>

                <h2
                    class="mt-5 text-xl font-bold text-gray-900 dark:text-white"
                >
                    Hapus Daily Check?
                </h2>

                <p
                    class="mt-2 text-sm leading-6 text-gray-500
                        dark:text-gray-400"
                >
                    Data pemeriksaan
                    <strong class="text-gray-700 dark:text-gray-200">
                        {{ $dailyCheck->product_name }}
                    </strong>
                    tanggal
                    <strong class="text-gray-700 dark:text-gray-200">
                        {{ $dailyCheck->check_date->format('d/m/Y') }}
                    </strong>
                    akan dihapus.
                </p>

                <div
                    class="mt-6 flex flex-col-reverse gap-3
                        sm:flex-row sm:justify-end"
                >
                    <button
                        type="button"
                        @click="openDeleteModal = false"
                        class="inline-flex items-center justify-center rounded-xl
                            border border-gray-200 bg-white px-4 py-2.5
                            text-sm font-semibold text-gray-700
                            transition hover:bg-gray-50
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Batal
                    </button>

                    <form
                        method="POST"
                        action="{{ route(
                            'monitoring-qc.product-electrical.daily-check.destroy',
                            $dailyCheck
                        ) }}"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center
                                rounded-xl bg-red-600 px-4 py-2.5
                                text-sm font-bold text-white
                                transition hover:bg-red-700"
                        >
                            <i class="fa-solid fa-trash mr-2"></i>
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
