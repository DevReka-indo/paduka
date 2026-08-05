@extends('layouts.app')

@section('header')
    Detail Fasilitas Quality Control
@endsection

@section('content_width', 'w-full')

@section('content')
    @php
        $level = strtolower(Auth::user()->level ?? '');
        $canManage = in_array($level, ['admin', 'superadmin'], true);

        $specificationLines = collect(
            preg_split(
                '/\r\n|\r|\n/',
                trim((string) $qcFacility->technical_specifications),
            ),
        )
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $conditionBadgeClasses = [
            'baik' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
            'perlu_perbaikan' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',
            'dalam_perbaikan' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-400/20',
            'tidak_layak' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
        ];

        $calibrationBadgeClasses = [
            'valid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',
            'expiring' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',
            'expired' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',
            'not_available' => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
        ];

        $calibrationIcons = [
            'valid' => 'fa-circle-check',
            'expiring' => 'fa-clock',
            'expired' => 'fa-triangle-exclamation',
            'not_available' => 'fa-circle-minus',
        ];

        $creatorName = $qcFacility->creator?->name
            ?? $qcFacility->creator?->nama
            ?? '-';

        $updaterName = $qcFacility->updater?->name
            ?? $qcFacility->updater?->nama
            ?? '-';
    @endphp

    <div
        x-data="{ openDeleteModal: false }"
        class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-[1600px] space-y-6">

            {{-- Success Alert --}}
            @if (session('success'))
                <div
                    class="flex items-start gap-3 rounded-2xl border border-emerald-200
                        bg-emerald-50 px-5 py-4 text-sm text-emerald-700
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
                class="relative overflow-hidden rounded-3xl border border-white/70
                    bg-white p-6 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="absolute -right-20 -top-20 h-56 w-56 rounded-full
                        bg-blue-500/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-24 left-10 h-56 w-56 rounded-full
                        bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-5
                        lg:flex-row lg:items-end lg:justify-between"
                >
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full
                                    bg-blue-50 px-3 py-1 text-xs font-bold
                                    text-blue-700 ring-1 ring-blue-600/10
                                    dark:bg-blue-500/15 dark:text-blue-300
                                    dark:ring-blue-400/20"
                            >
                                <i class="fa-solid fa-layer-group mr-1.5"></i>

                                {{ $qcFacility->category?->name ?? 'Tanpa Kategori' }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full
                                    px-3 py-1 text-xs font-semibold ring-1 ring-inset
                                    {{ $conditionBadgeClasses[$qcFacility->condition]
                                        ?? $conditionBadgeClasses['baik'] }}"
                            >
                                <i class="fa-solid fa-circle mr-1.5 text-[6px]"></i>

                                {{ $qcFacility->condition_label }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full
                                    px-3 py-1 text-xs font-semibold ring-1 ring-inset
                                    {{ $calibrationBadgeClasses[$qcFacility->calibration_status]
                                        ?? $calibrationBadgeClasses['not_available'] }}"
                            >
                                <i
                                    class="fa-solid
                                        {{ $calibrationIcons[$qcFacility->calibration_status]
                                            ?? $calibrationIcons['not_available'] }}
                                        mr-1.5"
                                ></i>

                                {{ $qcFacility->calibration_status_label }}
                            </span>
                        </div>

                        <h1
                            class="mt-4 text-2xl font-extrabold tracking-tight
                                text-gray-900 dark:text-white sm:text-3xl"
                        >
                            {{ $qcFacility->name }}
                        </h1>

                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ collect([$qcFacility->brand, $qcFacility->model])
                                ->filter()
                                ->join(' · ') ?: 'Merk dan model belum tersedia' }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route('qc-facilities.index') }}"
                            class="inline-flex items-center justify-center rounded-xl
                                border border-gray-200 bg-white px-4 py-2.5
                                text-sm font-semibold text-gray-700 shadow-sm transition
                                hover:bg-gray-50
                                dark:border-gray-700 dark:bg-gray-800
                                dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-arrow-left mr-2"></i>

                            Kembali
                        </a>

                        @if ($canManage)
                            <a
                                href="{{ route('qc-facilities.edit', $qcFacility) }}"
                                class="inline-flex items-center justify-center rounded-xl
                                    bg-blue-600 px-4 py-2.5 text-sm font-semibold
                                    text-white shadow-sm transition hover:bg-blue-700"
                            >
                                <i class="fa-solid fa-pen-to-square mr-2"></i>

                                Edit Fasilitas
                            </a>

                            <button
                                type="button"
                                @click="openDeleteModal = true"
                                class="inline-flex items-center justify-center rounded-xl
                                    border border-red-200 bg-red-50 px-4 py-2.5
                                    text-sm font-semibold text-red-600 shadow-sm transition
                                    hover:bg-red-100 hover:text-red-700
                                    dark:border-red-900/40 dark:bg-red-500/10
                                    dark:text-red-300 dark:hover:bg-red-500/20"
                            >
                                <i class="fa-solid fa-trash mr-2"></i>

                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="grid gap-6 xl:grid-cols-[minmax(0,5fr)_minmax(380px,3fr)]">

                {{-- Left Column --}}
                <div class="space-y-6">

                    {{-- Photo --}}
                    <div
                        class="overflow-hidden rounded-3xl border border-white/70
                            bg-white shadow-sm
                            dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div
                            class="flex items-center justify-between border-b
                                border-gray-100 px-6 py-4
                                dark:border-gray-800"
                        >
                            <div>
                                <h2 class="font-bold text-gray-900 dark:text-white">
                                    Foto Fasilitas
                                </h2>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Dokumentasi visual fasilitas Quality Control.
                                </p>
                            </div>

                            <span
                                class="flex h-10 w-10 items-center justify-center
                                    rounded-2xl bg-blue-50 text-blue-600
                                    dark:bg-blue-500/15 dark:text-blue-300"
                            >
                                <i class="fa-solid fa-image"></i>
                            </span>
                        </div>

                        <div
                            class="relative aspect-[16/10] overflow-hidden
                                bg-slate-100 dark:bg-gray-800"
                        >
                            @if ($qcFacility->photo_url)
                                <img
                                    src="{{ $qcFacility->photo_url }}"
                                    alt="{{ $qcFacility->name }}"
                                    class="h-full w-full object-contain"
                                >
                            @else
                                <div
                                    class="flex h-full w-full flex-col items-center
                                        justify-center bg-gradient-to-br
                                        from-slate-100 via-blue-50 to-cyan-50
                                        px-6 text-center text-slate-400
                                        dark:from-gray-800 dark:via-gray-800
                                        dark:to-slate-900 dark:text-gray-500"
                                >
                                    <span
                                        class="flex h-24 w-24 items-center justify-center
                                            rounded-[2rem] bg-white/80 text-4xl shadow-sm
                                            ring-1 ring-slate-200
                                            dark:bg-white/10 dark:ring-white/10"
                                    >
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                    </span>

                                    <p class="mt-4 text-sm font-semibold">
                                        Foto fasilitas belum tersedia
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Technical Specifications --}}
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Spesifikasi Teknis
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Informasi teknis lengkap mengenai kemampuan dan
                                    karakteristik alat.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 flex-shrink-0 items-center
                                    justify-center rounded-2xl bg-violet-50
                                    text-violet-600
                                    dark:bg-violet-500/15 dark:text-violet-300"
                            >
                                <i class="fa-solid fa-list-check"></i>
                            </span>
                        </div>

                        @if ($specificationLines->isNotEmpty())
                            <dl
                                class="mt-6 divide-y divide-gray-100 overflow-hidden
                                    rounded-2xl border border-gray-100
                                    dark:divide-gray-800 dark:border-gray-800"
                            >
                                @foreach ($specificationLines as $specificationLine)
                                    @php
                                        $specificationParts = explode(
                                            ':',
                                            $specificationLine,
                                            2,
                                        );

                                        $specificationLabel = trim(
                                            $specificationParts[0] ?? '',
                                        );

                                        $specificationValue = trim(
                                            $specificationParts[1] ?? '',
                                        );
                                    @endphp

                                    @if ($specificationValue !== '')
                                        <div
                                            class="grid gap-2 px-5 py-4
                                                sm:grid-cols-[minmax(180px,0.8fr)_minmax(0,1.2fr)]
                                                sm:gap-6"
                                        >
                                            <dt
                                                class="text-sm font-semibold text-gray-500
                                                    dark:text-gray-400"
                                            >
                                                {{ $specificationLabel }}
                                            </dt>

                                            <dd
                                                class="break-words text-sm font-medium
                                                    text-gray-900 dark:text-gray-100"
                                            >
                                                {{ $specificationValue }}
                                            </dd>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-3 px-5 py-4">
                                            <span
                                                class="mt-2 h-1.5 w-1.5 flex-shrink-0
                                                    rounded-full bg-blue-500"
                                            ></span>

                                            <p
                                                class="text-sm leading-6 text-gray-700
                                                    dark:text-gray-300"
                                            >
                                                {{ $specificationLine }}
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                        @else
                            <div
                                class="mt-6 rounded-2xl border border-dashed
                                    border-gray-300 px-6 py-12 text-center
                                    dark:border-gray-700"
                            >
                                <span
                                    class="mx-auto flex h-16 w-16 items-center
                                        justify-center rounded-3xl bg-slate-100
                                        text-2xl text-slate-400
                                        dark:bg-white/10 dark:text-gray-500"
                                >
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </span>

                                <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Spesifikasi teknis belum tersedia
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Keterangan Tambahan
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Informasi penggunaan, fungsi, atau catatan mengenai alat.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 flex-shrink-0 items-center
                                    justify-center rounded-2xl bg-cyan-50 text-cyan-600
                                    dark:bg-cyan-500/15 dark:text-cyan-300"
                            >
                                <i class="fa-solid fa-align-left"></i>
                            </span>
                        </div>

                        @if ($qcFacility->description)
                            <div
                                class="mt-6 rounded-2xl bg-slate-50 px-5 py-4
                                    text-sm leading-7 text-gray-700
                                    dark:bg-white/[0.04] dark:text-gray-300"
                            >
                                {!! nl2br(e($qcFacility->description)) !!}
                            </div>
                        @else
                            <div
                                class="mt-6 rounded-2xl border border-dashed
                                    border-gray-300 px-5 py-8 text-center
                                    text-sm text-gray-400
                                    dark:border-gray-700 dark:text-gray-500"
                            >
                                Keterangan tambahan belum tersedia.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">

                    {{-- Identity Information --}}
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Informasi Fasilitas
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Identitas dan lokasi fasilitas.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 flex-shrink-0 items-center
                                    justify-center rounded-2xl bg-blue-50 text-blue-600
                                    dark:bg-blue-500/15 dark:text-blue-300"
                            >
                                <i class="fa-solid fa-toolbox"></i>
                            </span>
                        </div>

                        <dl class="mt-6 space-y-5">

                            {{-- Category --}}
                            <div class="flex items-start gap-4">
                                <dt
                                    class="flex h-10 w-10 flex-shrink-0 items-center
                                        justify-center rounded-2xl bg-slate-100
                                        text-slate-500
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    <i class="fa-solid fa-layer-group"></i>
                                </dt>

                                <dd class="min-w-0">
                                    <p
                                        class="text-xs font-bold uppercase tracking-wide
                                            text-gray-400 dark:text-gray-500"
                                    >
                                        Kategori
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900
                                            dark:text-white"
                                    >
                                        {{ $qcFacility->category?->name ?? '-' }}
                                    </p>
                                </dd>
                            </div>

                            {{-- Brand --}}
                            <div class="flex items-start gap-4">
                                <dt
                                    class="flex h-10 w-10 flex-shrink-0 items-center
                                        justify-center rounded-2xl bg-slate-100
                                        text-slate-500
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    <i class="fa-solid fa-tag"></i>
                                </dt>

                                <dd class="min-w-0">
                                    <p
                                        class="text-xs font-bold uppercase tracking-wide
                                            text-gray-400 dark:text-gray-500"
                                    >
                                        Merk
                                    </p>

                                    <p
                                        class="mt-1 break-words font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{ $qcFacility->brand ?: '-' }}
                                    </p>
                                </dd>
                            </div>

                            {{-- Model --}}
                            <div class="flex items-start gap-4">
                                <dt
                                    class="flex h-10 w-10 flex-shrink-0 items-center
                                        justify-center rounded-2xl bg-slate-100
                                        text-slate-500
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    <i class="fa-solid fa-cube"></i>
                                </dt>

                                <dd class="min-w-0">
                                    <p
                                        class="text-xs font-bold uppercase tracking-wide
                                            text-gray-400 dark:text-gray-500"
                                    >
                                        Tipe/Model
                                    </p>

                                    <p
                                        class="mt-1 break-words font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{ $qcFacility->model ?: '-' }}
                                    </p>
                                </dd>
                            </div>

                            {{-- Inventory Number --}}
                            <div class="flex items-start gap-4">
                                <dt
                                    class="flex h-10 w-10 flex-shrink-0 items-center
                                        justify-center rounded-2xl bg-slate-100
                                        text-slate-500
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    <i class="fa-solid fa-barcode"></i>
                                </dt>

                                <dd class="min-w-0">
                                    <p
                                        class="text-xs font-bold uppercase tracking-wide
                                            text-gray-400 dark:text-gray-500"
                                    >
                                        Nomor Inventaris
                                    </p>

                                    <p
                                        class="mt-1 break-all font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{ $qcFacility->inventory_number ?: '-' }}
                                    </p>
                                </dd>
                            </div>

                            {{-- Serial Number --}}
                            <div class="flex items-start gap-4">
                                <dt
                                    class="flex h-10 w-10 flex-shrink-0 items-center
                                        justify-center rounded-2xl bg-slate-100
                                        text-slate-500
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    <i class="fa-solid fa-hashtag"></i>
                                </dt>

                                <dd class="min-w-0">
                                    <p
                                        class="text-xs font-bold uppercase tracking-wide
                                            text-gray-400 dark:text-gray-500"
                                    >
                                        Serial Number
                                    </p>

                                    <p
                                        class="mt-1 break-all font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{ $qcFacility->serial_number ?: '-' }}
                                    </p>
                                </dd>
                            </div>

                            {{-- Location --}}
                            <div class="flex items-start gap-4">
                                <dt
                                    class="flex h-10 w-10 flex-shrink-0 items-center
                                        justify-center rounded-2xl bg-slate-100
                                        text-slate-500
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    <i class="fa-solid fa-location-dot"></i>
                                </dt>

                                <dd class="min-w-0">
                                    <p
                                        class="text-xs font-bold uppercase tracking-wide
                                            text-gray-400 dark:text-gray-500"
                                    >
                                        Lokasi
                                    </p>

                                    <p
                                        class="mt-1 break-words font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{ $qcFacility->location ?: '-' }}
                                    </p>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Calibration --}}
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Informasi Kalibrasi
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Periode dan status kalibrasi alat.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 flex-shrink-0 items-center
                                    justify-center rounded-2xl bg-emerald-50
                                    text-emerald-600
                                    dark:bg-emerald-500/15 dark:text-emerald-300"
                            >
                                <i class="fa-solid fa-calendar-check"></i>
                            </span>
                        </div>

                        <div class="mt-6">
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1.5
                                    text-xs font-semibold ring-1 ring-inset
                                    {{ $calibrationBadgeClasses[$qcFacility->calibration_status]
                                        ?? $calibrationBadgeClasses['not_available'] }}"
                            >
                                <i
                                    class="fa-solid
                                        {{ $calibrationIcons[$qcFacility->calibration_status]
                                            ?? $calibrationIcons['not_available'] }}
                                        mr-1.5"
                                ></i>

                                {{ $qcFacility->calibration_status_label }}
                            </span>
                        </div>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-4
                                    dark:bg-white/[0.04]"
                            >
                                <p
                                    class="text-xs font-bold uppercase tracking-wide
                                        text-gray-400 dark:text-gray-500"
                                >
                                    Tanggal Kalibrasi
                                </p>

                                <p
                                    class="mt-2 font-semibold text-gray-900
                                        dark:text-white"
                                >
                                    {{ $qcFacility->calibration_date?->translatedFormat('d F Y') ?? '-' }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-4
                                    dark:bg-white/[0.04]"
                            >
                                <p
                                    class="text-xs font-bold uppercase tracking-wide
                                        text-gray-400 dark:text-gray-500"
                                >
                                    Masa Berlaku
                                </p>

                                <p
                                    class="mt-2 font-semibold text-gray-900
                                        dark:text-white"
                                >
                                    {{ $qcFacility->calibration_valid_until?->translatedFormat('d F Y') ?? '-' }}
                                </p>
                            </div>
                        </div>

                        @if (
                            $qcFacility->calibration_valid_until &&
                            $qcFacility->calibration_status !== 'expired'
                        )
                            @php
                                $remainingDays = now()
                                    ->startOfDay()
                                    ->diffInDays(
                                        $qcFacility->calibration_valid_until->startOfDay(),
                                        false,
                                    );
                            @endphp

                            <div
                                class="mt-4 rounded-2xl border border-blue-100
                                    bg-blue-50 px-4 py-3 text-sm text-blue-700
                                    dark:border-blue-900/40 dark:bg-blue-900/20
                                    dark:text-blue-300"
                            >
                                <i class="fa-solid fa-clock mr-1.5"></i>

                                Masa berlaku tersisa
                                <strong>{{ max($remainingDays, 0) }} hari</strong>.
                            </div>
                        @endif
                    </div>

                    {{-- Record Information --}}
                    <div
                        class="rounded-3xl border border-white/70 bg-white p-6
                            shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Informasi Data
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Catatan pembuatan dan pembaruan data.
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 flex-shrink-0 items-center
                                    justify-center rounded-2xl bg-slate-100
                                    text-slate-600
                                    dark:bg-white/10 dark:text-slate-300"
                            >
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </span>
                        </div>

                        <dl class="mt-6 space-y-4">
                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase tracking-wide
                                        text-gray-400 dark:text-gray-500"
                                >
                                    Dibuat Oleh
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold text-gray-800
                                        dark:text-gray-200"
                                >
                                    {{ $creatorName }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase tracking-wide
                                        text-gray-400 dark:text-gray-500"
                                >
                                    Tanggal Dibuat
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold text-gray-800
                                        dark:text-gray-200"
                                >
                                    {{ $qcFacility->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase tracking-wide
                                        text-gray-400 dark:text-gray-500"
                                >
                                    Terakhir Diperbarui Oleh
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold text-gray-800
                                        dark:text-gray-200"
                                >
                                    {{ $updaterName }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 px-4 py-3
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase tracking-wide
                                        text-gray-400 dark:text-gray-500"
                                >
                                    Terakhir Diperbarui
                                </dt>

                                <dd
                                    class="mt-1 text-sm font-semibold text-gray-800
                                        dark:text-gray-200"
                                >
                                    {{ $qcFacility->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        @if ($canManage)
            <div
                x-show="openDeleteModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center
                    bg-slate-950/60 px-4 backdrop-blur-sm"
                style="display: none;"
            >
                <div
                    @click.away="openDeleteModal = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="scale-95 opacity-0 translate-y-2"
                    x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="scale-100 opacity-100 translate-y-0"
                    x-transition:leave-end="scale-95 opacity-0 translate-y-2"
                    class="w-full max-w-md rounded-3xl border border-slate-200
                        bg-white p-6 shadow-2xl shadow-slate-950/20
                        dark:border-white/10 dark:bg-slate-900
                        dark:shadow-black/40"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 flex-shrink-0 items-center
                                justify-center rounded-2xl bg-red-50 text-red-600
                                dark:bg-red-500/15 dark:text-red-300"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </div>

                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">
                                Hapus Fasilitas
                            </h2>

                            <p
                                class="mt-1 text-sm leading-6 text-slate-500
                                    dark:text-slate-400"
                            >
                                Apakah Anda yakin ingin menghapus
                                <strong>{{ $qcFacility->name }}</strong>?
                                Foto fasilitas juga akan dihapus dan tindakan ini
                                tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="openDeleteModal = false"
                            class="rounded-2xl border border-slate-200 px-4 py-2
                                text-sm font-semibold text-slate-600 transition
                                hover:bg-slate-100 hover:text-slate-900
                                dark:border-white/10 dark:text-slate-300
                                dark:hover:bg-white/10 dark:hover:text-white"
                        >
                            Batal
                        </button>

                        <form
                            method="POST"
                            action="{{ route('qc-facilities.destroy', $qcFacility) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-2xl bg-red-600 px-4 py-2
                                    text-sm font-bold text-white shadow-lg
                                    shadow-red-500/20 transition
                                    hover:-translate-y-0.5 hover:bg-red-700"
                            >
                                Hapus Fasilitas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
