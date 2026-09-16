@extends('layouts.app')

@section('header')
    Detail Fasilitas Quality Control
@endsection

@section('content_width', 'w-full')

@section('content')
    @php
        $level = strtolower(Auth::user()->level ?? '');

        $canManage = in_array(
            $level,
            ['admin', 'superadmin'],
            true
        );

        $specificationLines = collect(
            preg_split(
                '/\r\n|\r|\n/',
                trim((string) $qcFacility->technical_specifications)
            )
        )
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $units = $qcFacility->units ?? collect();

        $totalUnits = $units->count();

        $goodUnits = $units
            ->where('condition', 'baik')
            ->count();

        $attentionUnits = $units
            ->where('condition', '!=', 'baik')
            ->count();

        $validCalibrations = $units
            ->filter(
                fn ($unit) =>
                    $unit->latestCalibration
                    && $unit->latestCalibration
                        ->calibration_status === 'valid'
            )
            ->count();

        $expiringCalibrations = $units
            ->filter(
                fn ($unit) =>
                    $unit->latestCalibration
                    && $unit->latestCalibration
                        ->calibration_status === 'expiring'
            )
            ->count();

        $expiredCalibrations = $units
            ->filter(
                fn ($unit) =>
                    $unit->latestCalibration
                    && $unit->latestCalibration
                        ->calibration_status === 'expired'
            )
            ->count();

        $noCalibrations = $units
            ->filter(
                fn ($unit) =>
                    !$unit->latestCalibration
                    || $unit->latestCalibration
                        ->calibration_status === 'not_available'
            )
            ->count();

        $conditionClasses = [
            'baik' =>
                'bg-emerald-50 text-emerald-700 ring-emerald-600/20
                dark:bg-emerald-500/15 dark:text-emerald-300
                dark:ring-emerald-400/20',

            'perlu_perbaikan' =>
                'bg-amber-50 text-amber-700 ring-amber-600/20
                dark:bg-amber-500/15 dark:text-amber-300
                dark:ring-amber-400/20',

            'dalam_perbaikan' =>
                'bg-blue-50 text-blue-700 ring-blue-600/20
                dark:bg-blue-500/15 dark:text-blue-300
                dark:ring-blue-400/20',

            'tidak_layak' =>
                'bg-red-50 text-red-700 ring-red-600/20
                dark:bg-red-500/15 dark:text-red-300
                dark:ring-red-400/20',
        ];

        $calibrationClasses = [
            'valid' =>
                'bg-emerald-50 text-emerald-700 ring-emerald-600/20
                dark:bg-emerald-500/15 dark:text-emerald-300',

            'expiring' =>
                'bg-amber-50 text-amber-700 ring-amber-600/20
                dark:bg-amber-500/15 dark:text-amber-300',

            'expired' =>
                'bg-red-50 text-red-700 ring-red-600/20
                dark:bg-red-500/15 dark:text-red-300',

            'not_available' =>
                'bg-slate-100 text-slate-600 ring-slate-500/20
                dark:bg-white/10 dark:text-slate-300',
        ];

        $creatorName =
            $qcFacility->creator?->name
            ?? $qcFacility->creator?->nama
            ?? '-';

        $updaterName =
            $qcFacility->updater?->name
            ?? $qcFacility->updater?->nama
            ?? '-';
    @endphp

    <div
        x-data="{ openDeleteModal: false }"
        class="min-h-screen bg-slate-50
            px-4 py-6 dark:bg-gray-950
            sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-[1600px] space-y-6">

            {{-- Success --}}
            @if (session('success'))
                <div
                    class="flex items-start gap-3
                        rounded-2xl border
                        border-emerald-200
                        bg-emerald-50 px-5 py-4
                        text-sm text-emerald-700
                        dark:border-emerald-900/40
                        dark:bg-emerald-900/20
                        dark:text-emerald-300"
                >
                    <i
                        class="fa-solid
                            fa-circle-check mt-0.5"
                    ></i>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- Header --}}
            <div
                class="relative overflow-hidden
                    rounded-3xl border
                    border-white/70 bg-white
                    p-6 shadow-sm
                    dark:border-gray-800
                    dark:bg-gray-900"
            >
                <div
                    class="absolute -right-20 -top-20
                        h-56 w-56 rounded-full
                        bg-blue-500/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-24 left-10
                        h-56 w-56 rounded-full
                        bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative flex flex-col gap-5
                        lg:flex-row lg:items-end
                        lg:justify-between"
                >
                    <div class="min-w-0">

                        <div
                            class="flex flex-wrap
                                items-center gap-2"
                        >
                            <span
                                class="inline-flex items-center
                                    rounded-full bg-blue-50
                                    px-3 py-1 text-xs
                                    font-bold text-blue-700
                                    ring-1 ring-blue-600/10
                                    dark:bg-blue-500/15
                                    dark:text-blue-300"
                            >
                                <i
                                    class="fa-solid
                                        fa-layer-group mr-1.5"
                                ></i>

                                {{
                                    $qcFacility
                                        ->category?->name
                                    ?? 'Tanpa Kategori'
                                }}
                            </span>

                            <span
                                class="inline-flex items-center
                                    rounded-full bg-slate-100
                                    px-3 py-1 text-xs
                                    font-semibold
                                    text-slate-600
                                    dark:bg-white/10
                                    dark:text-slate-300"
                            >
                                <i
                                    class="fa-solid
                                        fa-boxes-stacked mr-1.5"
                                ></i>

                                {{ $totalUnits }}
                                Unit
                            </span>
                        </div>

                        <h1
                            class="mt-4 text-2xl
                                font-extrabold tracking-tight
                                text-gray-900
                                dark:text-white sm:text-3xl"
                        >
                            {{ $qcFacility->name }}
                        </h1>

                        <p
                            class="mt-2 text-sm
                                text-gray-500
                                dark:text-gray-400"
                        >
                            {{
                                collect([
                                    $qcFacility->brand,
                                    $qcFacility->model,
                                ])
                                    ->filter()
                                    ->join(' · ')
                                ?: 'Merk dan model belum tersedia'
                            }}
                        </p>
                    </div>

                    <div
                        class="flex flex-col
                            gap-3 sm:flex-row"
                    >
                        <a
                            href="{{ route(
                                'qc-facilities.index'
                            ) }}"
                            class="inline-flex items-center
                                justify-center rounded-xl
                                border border-gray-200
                                bg-white px-4 py-2.5
                                text-sm font-semibold
                                text-gray-700 shadow-sm
                                hover:bg-gray-50
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-gray-200"
                        >
                            <i
                                class="fa-solid
                                    fa-arrow-left mr-2"
                            ></i>

                            Kembali
                        </a>

                        @if ($canManage)
                            <a
                                href="{{ route(
                                    'qc-facilities.edit',
                                    $qcFacility
                                ) }}"
                                class="inline-flex items-center
                                    justify-center rounded-xl
                                    bg-blue-600 px-4 py-2.5
                                    text-sm font-semibold
                                    text-white hover:bg-blue-700"
                            >
                                <i
                                    class="fa-solid
                                        fa-pen-to-square mr-2"
                                ></i>

                                Edit Master
                            </a>

                            <button
                                type="button"
                                @click="
                                    openDeleteModal = true
                                "
                                class="inline-flex items-center
                                    justify-center rounded-xl
                                    border border-red-200
                                    bg-red-50 px-4 py-2.5
                                    text-sm font-semibold
                                    text-red-600
                                    hover:bg-red-100
                                    dark:border-red-900/40
                                    dark:bg-red-500/10
                                    dark:text-red-300"
                            >
                                <i
                                    class="fa-solid
                                        fa-trash mr-2"
                                ></i>

                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div
                class="grid gap-4
                    sm:grid-cols-2 xl:grid-cols-5"
            >
                <div
                    class="rounded-3xl border
                        border-white/70 bg-white
                        p-5 shadow-sm
                        dark:border-gray-800
                        dark:bg-gray-900"
                >
                    <p
                        class="text-sm font-medium
                            text-gray-500
                            dark:text-gray-400"
                    >
                        Total Unit
                    </p>

                    <p
                        class="mt-2 text-3xl font-bold
                            text-gray-900 dark:text-white"
                    >
                        {{ $totalUnits }}
                    </p>
                </div>

                <div
                    class="rounded-3xl border
                        border-emerald-100
                        bg-emerald-50 p-5
                        dark:border-emerald-900/30
                        dark:bg-emerald-500/10"
                >
                    <p
                        class="text-sm font-medium
                            text-emerald-700
                            dark:text-emerald-300"
                    >
                        Kondisi Baik
                    </p>

                    <p
                        class="mt-2 text-3xl font-bold
                            text-emerald-700
                            dark:text-emerald-300"
                    >
                        {{ $goodUnits }}
                    </p>
                </div>

                <div
                    class="rounded-3xl border
                        border-amber-100
                        bg-amber-50 p-5
                        dark:border-amber-900/30
                        dark:bg-amber-500/10"
                >
                    <p
                        class="text-sm font-medium
                            text-amber-700
                            dark:text-amber-300"
                    >
                        Perlu Perhatian
                    </p>

                    <p
                        class="mt-2 text-3xl font-bold
                            text-amber-700
                            dark:text-amber-300"
                    >
                        {{ $attentionUnits }}
                    </p>
                </div>

                <div
                    class="rounded-3xl border
                        border-red-100
                        bg-red-50 p-5
                        dark:border-red-900/30
                        dark:bg-red-500/10"
                >
                    <p
                        class="text-sm font-medium
                            text-red-700
                            dark:text-red-300"
                    >
                        Kalibrasi Expired
                    </p>

                    <p
                        class="mt-2 text-3xl font-bold
                            text-red-700
                            dark:text-red-300"
                    >
                        {{ $expiredCalibrations }}
                    </p>
                </div>

                <div
                    class="rounded-3xl border
                        border-slate-200 bg-slate-100
                        p-5
                        dark:border-gray-700
                        dark:bg-white/[0.06]"
                >
                    <p
                        class="text-sm font-medium
                            text-slate-600
                            dark:text-slate-300"
                    >
                        Belum Kalibrasi
                    </p>

                    <p
                        class="mt-2 text-3xl font-bold
                            text-slate-800
                            dark:text-white"
                    >
                        {{ $noCalibrations }}
                    </p>
                </div>
            </div>

            {{-- Main Master Information --}}
            <div
                class="grid gap-6
                    xl:grid-cols-[minmax(0,5fr)_minmax(360px,2fr)]"
            >

                {{-- Left --}}
                <div class="space-y-6">

                    {{-- Photo --}}
                    <div
                        class="overflow-hidden
                            rounded-3xl border
                            border-white/70 bg-white
                            shadow-sm
                            dark:border-gray-800
                            dark:bg-gray-900"
                    >
                        <div
                            class="flex items-center
                                justify-between border-b
                                border-gray-100
                                px-6 py-4
                                dark:border-gray-800"
                        >
                            <div>
                                <h2
                                    class="font-bold
                                        text-gray-900
                                        dark:text-white"
                                >
                                    Foto Master Fasilitas
                                </h2>

                                <p
                                    class="mt-1 text-xs
                                        text-gray-500
                                        dark:text-gray-400"
                                >
                                    Foto representatif
                                    jenis fasilitas.
                                </p>
                            </div>

                            <span
                                class="flex h-10 w-10
                                    items-center
                                    justify-center
                                    rounded-2xl bg-blue-50
                                    text-blue-600
                                    dark:bg-blue-500/15
                                    dark:text-blue-300"
                            >
                                <i
                                    class="fa-solid fa-image"
                                ></i>
                            </span>
                        </div>

                        <div
                            class="relative aspect-[16/10]
                                overflow-hidden
                                bg-slate-100
                                dark:bg-gray-800"
                        >
                            @if ($qcFacility->photo_url)
                                <img
                                    src="{{
                                        $qcFacility->photo_url
                                    }}"
                                    alt="{{
                                        $qcFacility->name
                                    }}"
                                    class="h-full w-full
                                        object-contain"
                                >
                            @else
                                <div
                                    class="flex h-full w-full
                                        flex-col items-center
                                        justify-center"
                                >
                                    <i
                                        class="fa-solid
                                            fa-screwdriver-wrench
                                            text-5xl
                                            text-slate-300"
                                    ></i>

                                    <p
                                        class="mt-4 text-sm
                                            text-slate-400"
                                    >
                                        Foto belum tersedia
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Specification --}}
                    <div
                        class="rounded-3xl border
                            border-white/70 bg-white
                            p-6 shadow-sm
                            dark:border-gray-800
                            dark:bg-gray-900"
                    >
                        <h2
                            class="text-lg font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Spesifikasi Teknis
                        </h2>

                        <p
                            class="mt-1 text-sm
                                text-gray-500
                                dark:text-gray-400"
                        >
                            Spesifikasi yang berlaku
                            untuk master fasilitas ini.
                        </p>

                        @if (
                            $specificationLines
                                ->isNotEmpty()
                        )
                            <dl
                                class="mt-6 divide-y
                                    divide-gray-100
                                    overflow-hidden
                                    rounded-2xl border
                                    border-gray-100
                                    dark:divide-gray-800
                                    dark:border-gray-800"
                            >
                                @foreach (
                                    $specificationLines
                                    as $specificationLine
                                )
                                    @php
                                        $parts = explode(
                                            ':',
                                            $specificationLine,
                                            2
                                        );

                                        $label = trim(
                                            $parts[0] ?? ''
                                        );

                                        $value = trim(
                                            $parts[1] ?? ''
                                        );
                                    @endphp

                                    @if ($value !== '')
                                        <div
                                            class="grid gap-2
                                                px-5 py-4
                                                sm:grid-cols-[minmax(180px,0.8fr)_minmax(0,1.2fr)]"
                                        >
                                            <dt
                                                class="text-sm
                                                    font-semibold
                                                    text-gray-500
                                                    dark:text-gray-400"
                                            >
                                                {{ $label }}
                                            </dt>

                                            <dd
                                                class="break-words
                                                    text-sm
                                                    font-medium
                                                    text-gray-900
                                                    dark:text-gray-100"
                                            >
                                                {{ $value }}
                                            </dd>
                                        </div>
                                    @else
                                        <div
                                            class="px-5 py-4
                                                text-sm
                                                text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{
                                                $specificationLine
                                            }}
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                        @else
                            <div
                                class="mt-6 rounded-2xl
                                    border border-dashed
                                    border-gray-300
                                    px-6 py-10
                                    text-center text-sm
                                    text-gray-400
                                    dark:border-gray-700"
                            >
                                Spesifikasi teknis
                                belum tersedia.
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div
                        class="rounded-3xl border
                            border-white/70 bg-white
                            p-6 shadow-sm
                            dark:border-gray-800
                            dark:bg-gray-900"
                    >
                        <h2
                            class="text-lg font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Keterangan Tambahan
                        </h2>

                        @if ($qcFacility->description)
                            <div
                                class="mt-5 rounded-2xl
                                    bg-slate-50 px-5 py-4
                                    text-sm leading-7
                                    text-gray-700
                                    dark:bg-white/[0.04]
                                    dark:text-gray-300"
                            >
                                {!! nl2br(
                                    e(
                                        $qcFacility
                                            ->description
                                    )
                                ) !!}
                            </div>
                        @else
                            <p
                                class="mt-5 text-sm
                                    text-gray-400"
                            >
                                Belum ada keterangan.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Right --}}
                <div class="space-y-6">

                    {{-- Master Info --}}
                    <div
                        class="rounded-3xl border
                            border-white/70 bg-white
                            p-6 shadow-sm
                            dark:border-gray-800
                            dark:bg-gray-900"
                    >
                        <h2
                            class="text-lg font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Informasi Master
                        </h2>

                        <p
                            class="mt-1 text-sm
                                text-gray-500
                                dark:text-gray-400"
                        >
                            Informasi umum fasilitas.
                        </p>

                        <dl class="mt-6 space-y-5">

                            <div>
                                <dt
                                    class="text-xs font-bold
                                        uppercase tracking-wide
                                        text-gray-400"
                                >
                                    Kategori
                                </dt>

                                <dd
                                    class="mt-1 font-semibold
                                        text-gray-900
                                        dark:text-white"
                                >
                                    {{
                                        $qcFacility
                                            ->category?->name
                                        ?? '-'
                                    }}
                                </dd>
                            </div>

                            <div>
                                <dt
                                    class="text-xs font-bold
                                        uppercase tracking-wide
                                        text-gray-400"
                                >
                                    Merk
                                </dt>

                                <dd
                                    class="mt-1 font-semibold
                                        text-gray-900
                                        dark:text-white"
                                >
                                    {{
                                        $qcFacility->brand
                                        ?: '-'
                                    }}
                                </dd>
                            </div>

                            <div>
                                <dt
                                    class="text-xs font-bold
                                        uppercase tracking-wide
                                        text-gray-400"
                                >
                                    Tipe / Model
                                </dt>

                                <dd
                                    class="mt-1 font-semibold
                                        text-gray-900
                                        dark:text-white"
                                >
                                    {{
                                        $qcFacility->model
                                        ?: '-'
                                    }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Calibration Summary --}}
                    <div
                        class="rounded-3xl border
                            border-white/70 bg-white
                            p-6 shadow-sm
                            dark:border-gray-800
                            dark:bg-gray-900"
                    >
                        <h2
                            class="text-lg font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Status Kalibrasi Unit
                        </h2>

                        <div
                            class="mt-5 grid
                                grid-cols-2 gap-3"
                        >
                            <div
                                class="rounded-2xl
                                    bg-emerald-50 p-4
                                    dark:bg-emerald-500/10"
                            >
                                <p
                                    class="text-xs font-semibold
                                        text-emerald-600"
                                >
                                    Berlaku
                                </p>

                                <p
                                    class="mt-1 text-2xl
                                        font-bold
                                        text-emerald-700
                                        dark:text-emerald-300"
                                >
                                    {{ $validCalibrations }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl
                                    bg-amber-50 p-4
                                    dark:bg-amber-500/10"
                            >
                                <p
                                    class="text-xs font-semibold
                                        text-amber-600"
                                >
                                    H-30
                                </p>

                                <p
                                    class="mt-1 text-2xl
                                        font-bold
                                        text-amber-700
                                        dark:text-amber-300"
                                >
                                    {{
                                        $expiringCalibrations
                                    }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl
                                    bg-red-50 p-4
                                    dark:bg-red-500/10"
                            >
                                <p
                                    class="text-xs font-semibold
                                        text-red-600"
                                >
                                    Kedaluwarsa
                                </p>

                                <p
                                    class="mt-1 text-2xl
                                        font-bold
                                        text-red-700
                                        dark:text-red-300"
                                >
                                    {{
                                        $expiredCalibrations
                                    }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl
                                    bg-slate-100 p-4
                                    dark:bg-white/10"
                            >
                                <p
                                    class="text-xs font-semibold
                                        text-slate-500"
                                >
                                    Belum Ada
                                </p>

                                <p
                                    class="mt-1 text-2xl
                                        font-bold
                                        text-slate-700
                                        dark:text-white"
                                >
                                    {{ $noCalibrations }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Record --}}
                    <div
                        class="rounded-3xl border
                            border-white/70 bg-white
                            p-6 shadow-sm
                            dark:border-gray-800
                            dark:bg-gray-900"
                    >
                        <h2
                            class="text-lg font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Informasi Data
                        </h2>

                        <dl class="mt-5 space-y-4">
                            <div>
                                <dt
                                    class="text-xs font-bold
                                        uppercase
                                        text-gray-400"
                                >
                                    Dibuat Oleh
                                </dt>

                                <dd
                                    class="mt-1 text-sm
                                        font-semibold
                                        dark:text-white"
                                >
                                    {{ $creatorName }}
                                </dd>
                            </div>

                            <div>
                                <dt
                                    class="text-xs font-bold
                                        uppercase
                                        text-gray-400"
                                >
                                    Dibuat
                                </dt>

                                <dd
                                    class="mt-1 text-sm
                                        font-semibold
                                        dark:text-white"
                                >
                                    {{
                                        $qcFacility
                                            ->created_at
                                            ?->translatedFormat(
                                                'd F Y, H:i'
                                            )
                                        ?? '-'
                                    }}
                                </dd>
                            </div>

                            <div>
                                <dt
                                    class="text-xs font-bold
                                        uppercase
                                        text-gray-400"
                                >
                                    Diperbarui Oleh
                                </dt>

                                <dd
                                    class="mt-1 text-sm
                                        font-semibold
                                        dark:text-white"
                                >
                                    {{ $updaterName }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Units --}}
            <div
                id="unit-fasilitas"
                class="overflow-hidden
                    rounded-3xl border
                    border-white/70 bg-white
                    shadow-sm
                    dark:border-gray-800
                    dark:bg-gray-900"
            >
                <div
                    class="flex flex-col gap-4
                        border-b border-gray-100
                        px-6 py-5
                        dark:border-gray-800
                        sm:flex-row sm:items-center
                        sm:justify-between"
                >
                    <div>
                        <h2
                            class="text-lg font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Unit / Perangkat Fisik
                        </h2>

                        <p
                            class="mt-1 text-sm
                                text-gray-500
                                dark:text-gray-400"
                        >
                            Serial number, inventaris,
                            lokasi, kondisi dan kalibrasi
                            dikelola per unit.
                        </p>
                    </div>

                    @if ($canManage)
                        <a
                            href="{{ route(
                                'qc-facilities.units.create',
                                $qcFacility
                            ) }}"
                            class="inline-flex items-center
                                justify-center rounded-xl
                                bg-blue-600 px-4 py-2.5
                                text-sm font-semibold
                                text-white hover:bg-blue-700"
                        >
                            <i
                                class="fa-solid
                                    fa-plus mr-2"
                            ></i>

                            Tambah Unit
                        </a>
                    @endif
                </div>

                @if ($units->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table
                            class="w-full min-w-[1050px]
                                text-sm"
                        >
                            <thead
                                class="bg-slate-50
                                    text-xs font-bold
                                    uppercase tracking-wide
                                    text-gray-500
                                    dark:bg-gray-800/70
                                    dark:text-gray-400"
                            >
                                <tr>
                                    <th
                                        class="px-5 py-4
                                            text-left"
                                    >
                                        Serial Number
                                    </th>

                                    <th
                                        class="px-5 py-4
                                            text-left"
                                    >
                                        Inventaris
                                    </th>

                                    <th
                                        class="px-5 py-4
                                            text-left"
                                    >
                                        Lokasi
                                    </th>

                                    <th
                                        class="px-5 py-4
                                            text-left"
                                    >
                                        Kondisi
                                    </th>

                                    <th
                                        class="px-5 py-4
                                            text-left"
                                    >
                                        Kalibrasi
                                    </th>

                                    <th
                                        class="px-5 py-4
                                            text-left"
                                    >
                                        Berlaku Sampai
                                    </th>

                                    <th
                                        class="px-5 py-4
                                            text-right"
                                    >
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody
                                class="divide-y
                                    divide-gray-100
                                    dark:divide-gray-800"
                            >
                                @foreach ($units as $unit)
                                    @php
                                        $latest =
                                            $unit
                                                ->latestCalibration;

                                        $calStatus =
                                            $latest
                                                ? $latest
                                                    ->calibration_status
                                                : 'not_available';

                                        $calLabel =
                                            $latest
                                                ? $latest
                                                    ->calibration_status_label
                                                : 'Belum Ada Data';

                                        $conditionClass =
                                            $conditionClasses[
                                                $unit->condition
                                            ]
                                            ?? $conditionClasses[
                                                'baik'
                                            ];

                                        $calibrationClass =
                                            $calibrationClasses[
                                                $calStatus
                                            ]
                                            ?? $calibrationClasses[
                                                'not_available'
                                            ];
                                    @endphp

                                    <tr
                                        class="hover:bg-slate-50/70
                                            dark:hover:bg-white/[0.03]"
                                    >
                                        <td
                                            class="px-5 py-4
                                                font-mono
                                                font-semibold
                                                text-gray-900
                                                dark:text-white"
                                        >
                                            {{
                                                $unit
                                                    ->serial_number
                                                ?: '-'
                                            }}
                                        </td>

                                        <td
                                            class="px-5 py-4
                                                text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{
                                                $unit
                                                    ->inventory_number
                                                ?: '-'
                                            }}
                                        </td>

                                        <td
                                            class="px-5 py-4
                                                text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{
                                                $unit->location
                                                ?: '-'
                                            }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex
                                                    rounded-full
                                                    px-2.5 py-1
                                                    text-xs
                                                    font-semibold
                                                    ring-1 ring-inset
                                                    {{
                                                        $conditionClass
                                                    }}"
                                            >
                                                {{
                                                    $unit
                                                        ->condition_label
                                                }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex
                                                    rounded-full
                                                    px-2.5 py-1
                                                    text-xs
                                                    font-semibold
                                                    ring-1 ring-inset
                                                    {{
                                                        $calibrationClass
                                                    }}"
                                            >
                                                {{ $calLabel }}
                                            </span>
                                        </td>

                                        <td
                                            class="px-5 py-4
                                                text-gray-700
                                                dark:text-gray-300"
                                        >
                                            {{
                                                $latest
                                                    ?->calibration_valid_until
                                                    ?->translatedFormat(
                                                        'd M Y'
                                                    )
                                                ?? '-'
                                            }}
                                        </td>

                                        <td
                                            class="px-5 py-4
                                                text-right"
                                        >
                                            <a
                                                href="{{ route(
                                                    'qc-facilities.units.show',
                                                    [
                                                        'qcFacility' =>
                                                            $qcFacility,
                                                        'qcFacilityUnit' =>
                                                            $unit,
                                                    ]
                                                ) }}"
                                                class="inline-flex
                                                    items-center
                                                    rounded-lg
                                                    bg-blue-50
                                                    px-3 py-2
                                                    text-xs
                                                    font-semibold
                                                    text-blue-600
                                                    hover:bg-blue-100
                                                    dark:bg-blue-500/10
                                                    dark:text-blue-300"
                                            >
                                                <i
                                                    class="fa-solid
                                                        fa-eye mr-2"
                                                ></i>

                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div
                        class="px-6 py-14 text-center"
                    >
                        <span
                            class="mx-auto flex
                                h-16 w-16 items-center
                                justify-center
                                rounded-3xl
                                bg-slate-100
                                text-2xl
                                text-slate-400
                                dark:bg-white/10"
                        >
                            <i
                                class="fa-solid
                                    fa-box-open"
                            ></i>
                        </span>

                        <h3
                            class="mt-4 font-bold
                                text-gray-900
                                dark:text-white"
                        >
                            Belum ada unit fisik
                        </h3>

                        <p
                            class="mt-2 text-sm
                                text-gray-500"
                        >
                            Tambahkan perangkat fisik
                            beserta serial number-nya.
                        </p>

                        @if ($canManage)
                            <a
                                href="{{ route(
                                    'qc-facilities.units.create',
                                    $qcFacility
                                ) }}"
                                class="mt-5
                                    inline-flex items-center
                                    rounded-xl bg-blue-600
                                    px-4 py-2.5
                                    text-sm font-semibold
                                    text-white
                                    hover:bg-blue-700"
                            >
                                <i
                                    class="fa-solid
                                        fa-plus mr-2"
                                ></i>

                                Tambah Unit Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Delete Master Modal --}}
        @if ($canManage)
            <div
                x-cloak
                x-show="openDeleteModal"
                class="fixed inset-0 z-50
                    flex items-center
                    justify-center bg-black/60
                    px-4 backdrop-blur-sm"
            >
                <div
                    @click.outside="
                        openDeleteModal = false
                    "
                    class="w-full max-w-md
                        rounded-3xl bg-white
                        p-6 shadow-2xl
                        dark:bg-gray-900"
                >
                    <div class="flex gap-4">
                        <span
                            class="flex h-12 w-12
                                flex-shrink-0
                                items-center justify-center
                                rounded-2xl bg-red-50
                                text-red-600
                                dark:bg-red-500/15"
                        >
                            <i
                                class="fa-solid
                                    fa-trash"
                            ></i>
                        </span>

                        <div>
                            <h3
                                class="text-lg font-bold
                                    text-gray-900
                                    dark:text-white"
                            >
                                Hapus Master Fasilitas?
                            </h3>

                            <p
                                class="mt-2 text-sm
                                    leading-6
                                    text-gray-500
                                    dark:text-gray-400"
                            >
                                Master
                                <strong>
                                    {{ $qcFacility->name }}
                                </strong>
                                beserta
                                <strong>
                                    {{ $totalUnits }}
                                    unit
                                </strong>,
                                seluruh riwayat
                                kalibrasi, sertifikat
                                dan foto fasilitas
                                akan dihapus.
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-6 flex
                            justify-end gap-3"
                    >
                        <button
                            type="button"
                            @click="
                                openDeleteModal = false
                            "
                            class="rounded-xl border
                                border-gray-200
                                px-4 py-2.5
                                text-sm font-semibold
                                text-gray-600
                                dark:border-gray-700
                                dark:text-gray-300"
                        >
                            Batal
                        </button>

                        <form
                            method="POST"
                            action="{{ route(
                                'qc-facilities.destroy',
                                $qcFacility
                            ) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-xl
                                    bg-red-600
                                    px-4 py-2.5
                                    text-sm font-semibold
                                    text-white
                                    hover:bg-red-700"
                            >
                                Hapus Master
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
