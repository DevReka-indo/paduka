@extends('layouts.app')

@section('header')
    Detail Unit Fasilitas QC
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

        $latestCalibration = $qcFacilityUnit->latestCalibration;

        $conditionClasses = match ($qcFacilityUnit->condition) {
            'baik' =>
                'bg-emerald-50 text-emerald-700 ring-emerald-600/20
                 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',

            'perlu_perbaikan' =>
                'bg-amber-50 text-amber-700 ring-amber-600/20
                 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',

            'dalam_perbaikan' =>
                'bg-blue-50 text-blue-700 ring-blue-600/20
                 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-400/20',

            'tidak_layak' =>
                'bg-red-50 text-red-700 ring-red-600/20
                 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',

            default =>
                'bg-slate-100 text-slate-600 ring-slate-500/20
                 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
        };

        $calibrationStatus = $latestCalibration
            ? $latestCalibration->calibration_status
            : 'not_available';

        $calibrationLabel = $latestCalibration
            ? $latestCalibration->calibration_status_label
            : 'Belum Ada Data Kalibrasi';

        $calibrationClasses = match ($calibrationStatus) {
            'valid' =>
                'bg-emerald-50 text-emerald-700 ring-emerald-600/20
                 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/20',

            'expiring' =>
                'bg-amber-50 text-amber-700 ring-amber-600/20
                 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',

            'expired' =>
                'bg-red-50 text-red-700 ring-red-600/20
                 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-400/20',

            default =>
                'bg-slate-100 text-slate-600 ring-slate-500/20
                 dark:bg-white/10 dark:text-slate-300 dark:ring-white/10',
        };
    @endphp

    <div
        x-data="{ openDeleteModal: false }"
        class="min-h-screen bg-slate-50 px-4 py-6
            dark:bg-gray-950 sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-7xl space-y-6">

            @if (session('success'))
                <div
                    class="rounded-2xl border border-emerald-200
                        bg-emerald-50 px-5 py-4 text-sm
                        text-emerald-700
                        dark:border-emerald-900/40
                        dark:bg-emerald-900/20
                        dark:text-emerald-300"
                >
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div
                class="rounded-3xl border border-white/70
                    bg-white p-6 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="flex flex-col gap-5
                        lg:flex-row lg:items-center
                        lg:justify-between"
                >
                    <div class="min-w-0">
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex rounded-full
                                    bg-blue-50 px-3 py-1
                                    text-xs font-semibold text-blue-700
                                    dark:bg-blue-500/15 dark:text-blue-300"
                            >
                                {{ $qcFacility->category?->name ?? 'Tanpa Kategori' }}
                            </span>

                            <span
                                class="inline-flex rounded-full
                                    px-3 py-1 text-xs font-semibold
                                    ring-1 ring-inset
                                    {{ $conditionClasses }}"
                            >
                                {{ $qcFacilityUnit->condition_label }}
                            </span>

                            <span
                                class="inline-flex rounded-full
                                    px-3 py-1 text-xs font-semibold
                                    ring-1 ring-inset
                                    {{ $calibrationClasses }}"
                            >
                                {{ $calibrationLabel }}
                            </span>
                        </div>

                        <p
                            class="mt-4 text-sm font-semibold
                                text-blue-600 dark:text-blue-400"
                        >
                            {{ $qcFacility->name }}
                        </p>

                        <h1
                            class="mt-1 break-all text-2xl
                                font-extrabold text-gray-900
                                dark:text-white"
                        >
                            {{ $qcFacilityUnit->serial_number }}
                        </h1>

                        <p
                            class="mt-2 text-sm text-gray-500
                                dark:text-gray-400"
                        >
                            Unit fisik fasilitas Quality Control.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a
                            href="{{ route(
                                'qc-facilities.show',
                                $qcFacility
                            ) }}#unit-fasilitas"
                            class="inline-flex items-center
                                rounded-xl border border-gray-200
                                bg-white px-4 py-2.5
                                text-sm font-semibold text-gray-700
                                shadow-sm hover:bg-gray-50
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-gray-200
                                dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                        @if ($canManage)
                            <a
                                href="{{ route(
                                    'qc-facilities.units.edit',
                                    [
                                        'qcFacility' => $qcFacility,
                                        'qcFacilityUnit' => $qcFacilityUnit,
                                    ]
                                ) }}"
                                class="inline-flex items-center
                                    rounded-xl bg-blue-600
                                    px-4 py-2.5 text-sm
                                    font-semibold text-white
                                    hover:bg-blue-700"
                            >
                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                Edit Unit
                            </a>

                            <button
                                type="button"
                                @click="openDeleteModal = true"
                                class="inline-flex items-center
                                    rounded-xl border border-red-200
                                    bg-red-50 px-4 py-2.5
                                    text-sm font-semibold text-red-600
                                    hover:bg-red-100
                                    dark:border-red-900/40
                                    dark:bg-red-500/10
                                    dark:text-red-300"
                            >
                                <i class="fa-solid fa-trash mr-2"></i>
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="grid gap-6
                    lg:grid-cols-[minmax(0,1fr)_360px]"
            >
                <div class="space-y-6">

                    {{-- Identitas Unit --}}
                    <div
                        class="rounded-3xl border border-white/70
                            bg-white p-6 shadow-sm
                            dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2
                            class="text-lg font-bold text-gray-900
                                dark:text-white"
                        >
                            Identitas Unit
                        </h2>

                        <p
                            class="mt-1 text-sm text-gray-500
                                dark:text-gray-400"
                        >
                            Informasi perangkat fisik
                            {{ $qcFacility->name }}.
                        </p>

                        <dl
                            class="mt-6 grid gap-4
                                sm:grid-cols-2"
                        >
                            <div
                                class="rounded-2xl bg-slate-50 p-4
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Serial Number
                                </dt>

                                <dd
                                    class="mt-2 break-all font-mono
                                        font-semibold text-gray-900
                                        dark:text-white"
                                >
                                    {{ $qcFacilityUnit->serial_number }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 p-4
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Nomor Inventaris
                                </dt>

                                <dd
                                    class="mt-2 font-semibold
                                        text-gray-900 dark:text-white"
                                >
                                    {{ $qcFacilityUnit->inventory_number ?: '-' }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 p-4
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Lokasi
                                </dt>

                                <dd
                                    class="mt-2 font-semibold
                                        text-gray-900 dark:text-white"
                                >
                                    {{ $qcFacilityUnit->location ?: '-' }}
                                </dd>
                            </div>

                            <div
                                class="rounded-2xl bg-slate-50 p-4
                                    dark:bg-white/[0.04]"
                            >
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Kondisi
                                </dt>

                                <dd class="mt-2">
                                    <span
                                        class="inline-flex rounded-full
                                            px-2.5 py-1 text-xs
                                            font-semibold ring-1 ring-inset
                                            {{ $conditionClasses }}"
                                    >
                                        {{ $qcFacilityUnit->condition_label }}
                                    </span>
                                </dd>
                            </div>
                        </dl>

                        @if ($qcFacilityUnit->notes)
                            <div
                                class="mt-5 rounded-2xl
                                    border border-gray-100
                                    bg-slate-50 p-4
                                    dark:border-gray-800
                                    dark:bg-white/[0.04]"
                            >
                                <p
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Catatan
                                </p>

                                <p
                                    class="mt-2 whitespace-pre-line
                                        text-sm leading-6 text-gray-700
                                        dark:text-gray-300"
                                >
                                    {{ $qcFacilityUnit->notes }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Riwayat Kalibrasi --}}
                    <div
                        class="overflow-hidden rounded-3xl
                            border border-white/70
                            bg-white shadow-sm
                            dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div
                            class="flex items-center justify-between
                                border-b border-gray-100
                                px-6 py-5 dark:border-gray-800"
                        >
                            <div>
                                <h2
                                    class="text-lg font-bold
                                        text-gray-900 dark:text-white"
                                >
                                    Riwayat Kalibrasi
                                </h2>

                                <p
                                    class="mt-1 text-sm text-gray-500
                                        dark:text-gray-400"
                                >
                                    Histori kalibrasi khusus SN ini.
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span
                                    class="rounded-full bg-slate-100
                                        px-3 py-1 text-xs font-semibold
                                        text-slate-600
                                        dark:bg-white/10 dark:text-slate-300"
                                >
                                    {{ $qcFacilityUnit->calibrations->count() }}
                                    Riwayat
                                </span>

                                @if ($canManage)
                                    <a
                                        href="{{ route(
                                            'qc-facilities.units.calibrations.create',
                                            [
                                                'qcFacility' => $qcFacility,
                                                'qcFacilityUnit' => $qcFacilityUnit,
                                            ]
                                        ) }}"
                                        class="inline-flex items-center rounded-xl
                                            bg-blue-600 px-3.5 py-2
                                            text-xs font-semibold text-white
                                            hover:bg-blue-700"
                                    >
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Tambah Kalibrasi
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($qcFacilityUnit->calibrations->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead
                                        class="bg-slate-50 text-xs font-bold uppercase
                                            tracking-wide text-gray-500
                                            dark:bg-gray-800/70 dark:text-gray-400"
                                    >
                                        <tr>
                                            <th class="px-5 py-4 text-left">
                                                Tanggal
                                            </th>

                                            <th class="px-5 py-4 text-left">
                                                Berlaku Sampai
                                            </th>

                                            <th class="px-5 py-4 text-left">
                                                No. Sertifikat
                                            </th>

                                            <th class="px-5 py-4 text-left">
                                                Laboratorium
                                            </th>

                                            <th class="px-5 py-4 text-left">
                                                Status
                                            </th>

                                            <th class="px-5 py-4 text-left">
                                                File
                                            </th>

                                            @if ($canManage)
                                                <th class="px-5 py-4 text-right">
                                                    Aksi
                                                </th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody
                                        class="divide-y divide-gray-100
                                            dark:divide-gray-800"
                                    >
                                        @foreach ($qcFacilityUnit->calibrations as $calibration)
                                            @php
                                                $statusClasses = match (
                                                    $calibration->calibration_status
                                                ) {
                                                    'valid' =>
                                                        'text-emerald-600 dark:text-emerald-300',

                                                    'expiring' =>
                                                        'text-amber-600 dark:text-amber-300',

                                                    'expired' =>
                                                        'text-red-600 dark:text-red-300',

                                                    default =>
                                                        'text-gray-500 dark:text-gray-400',
                                                };
                                            @endphp

                                            <tr
                                                class="hover:bg-slate-50/70
                                                    dark:hover:bg-white/[0.03]"
                                            >
                                                <td
                                                    class="px-5 py-4
                                                        text-gray-700 dark:text-gray-200"
                                                >
                                                    <p class="font-medium">
                                                        {{
                                                            $calibration
                                                                ->calibration_date
                                                                ?->translatedFormat('d F Y')
                                                            ?? '-'
                                                        }}
                                                    </p>

                                                    @if ($calibration->notes)
                                                        <p
                                                            class="mt-1 max-w-xs
                                                                text-xs text-gray-400"
                                                        >
                                                            {{ Str::limit(
                                                                $calibration->notes,
                                                                80
                                                            ) }}
                                                        </p>
                                                    @endif
                                                </td>

                                                <td
                                                    class="px-5 py-4
                                                        text-gray-700
                                                        dark:text-gray-200"
                                                >
                                                    {{
                                                        $calibration
                                                            ->calibration_valid_until
                                                            ?->translatedFormat('d F Y')
                                                        ?? '-'
                                                    }}
                                                </td>

                                                <td
                                                    class="px-5 py-4
                                                        text-gray-700
                                                        dark:text-gray-200"
                                                >
                                                    {{ $calibration->certificate_number ?: '-' }}
                                                </td>

                                                <td
                                                    class="px-5 py-4
                                                        text-gray-700
                                                        dark:text-gray-200"
                                                >
                                                    {{ $calibration->calibration_laboratory ?: '-' }}
                                                </td>

                                                <td class="px-5 py-4">
                                                    <span
                                                        class="font-semibold
                                                            {{ $statusClasses }}"
                                                    >
                                                        {{ $calibration->calibration_status_label }}
                                                    </span>
                                                </td>

                                                {{-- File Sertifikat --}}
                                                <td class="px-5 py-4">
                                                    @if ($calibration->certificate_path)
                                                        <a
                                                            href="{{ route(
                                                                'qc-facilities.units.calibrations.certificate',
                                                                [
                                                                    'qcFacility' => $qcFacility,
                                                                    'qcFacilityUnit' => $qcFacilityUnit,
                                                                    'qcFacilityCalibration' =>
                                                                        $calibration,
                                                                ]
                                                            ) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center
                                                                text-sm font-semibold text-blue-600
                                                                hover:text-blue-700
                                                                dark:text-blue-400
                                                                dark:hover:text-blue-300"
                                                        >
                                                            <i
                                                                class="fa-solid
                                                                    fa-file-lines mr-2"
                                                            ></i>

                                                            Lihat
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Action --}}
                                                @if ($canManage)
                                                    <td class="px-5 py-4">
                                                        <div
                                                            class="flex items-center
                                                                justify-end gap-2"
                                                        >
                                                            <a
                                                                href="{{ route(
                                                                    'qc-facilities.units.calibrations.edit',
                                                                    [
                                                                        'qcFacility' =>
                                                                            $qcFacility,
                                                                        'qcFacilityUnit' =>
                                                                            $qcFacilityUnit,
                                                                        'qcFacilityCalibration' =>
                                                                            $calibration,
                                                                    ]
                                                                ) }}"
                                                                title="Edit Kalibrasi"
                                                                class="inline-flex h-9 w-9
                                                                    items-center justify-center
                                                                    rounded-lg border border-gray-200
                                                                    text-gray-600
                                                                    transition hover:bg-gray-50
                                                                    dark:border-gray-700
                                                                    dark:text-gray-300
                                                                    dark:hover:bg-gray-800"
                                                            >
                                                                <i
                                                                    class="fa-solid
                                                                        fa-pen-to-square"
                                                                ></i>
                                                            </a>

                                                            <form
                                                                method="POST"
                                                                action="{{ route(
                                                                    'qc-facilities.units.calibrations.destroy',
                                                                    [
                                                                        'qcFacility' =>
                                                                            $qcFacility,
                                                                        'qcFacilityUnit' =>
                                                                            $qcFacilityUnit,
                                                                        'qcFacilityCalibration' =>
                                                                            $calibration,
                                                                    ]
                                                                ) }}"
                                                                onsubmit="
                                                                    return confirm(
                                                                        'Apakah Anda yakin ingin menghapus riwayat kalibrasi ini?'
                                                                    );
                                                                "
                                                            >
                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    type="submit"
                                                                    title="Hapus Kalibrasi"
                                                                    class="inline-flex h-9 w-9
                                                                        items-center justify-center
                                                                        rounded-lg border border-red-200
                                                                        text-red-600
                                                                        transition hover:bg-red-50
                                                                        dark:border-red-900/40
                                                                        dark:text-red-400
                                                                        dark:hover:bg-red-500/10"
                                                                >
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div
                                class="px-6 py-14 text-center
                                    text-sm text-gray-400"
                            >
                                Belum ada riwayat kalibrasi untuk unit ini.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">

                    {{-- Master --}}
                    <div
                        class="rounded-3xl border border-white/70
                            bg-white p-6 shadow-sm
                            dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2
                            class="font-bold text-gray-900
                                dark:text-white"
                        >
                            Master Fasilitas
                        </h2>

                        <dl class="mt-5 space-y-4">
                            <div>
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Nama
                                </dt>

                                <dd
                                    class="mt-1 font-semibold
                                        text-gray-900 dark:text-white"
                                >
                                    {{ $qcFacility->name }}
                                </dd>
                            </div>

                            <div>
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Merk
                                </dt>

                                <dd
                                    class="mt-1 font-semibold
                                        text-gray-900 dark:text-white"
                                >
                                    {{ $qcFacility->brand ?: '-' }}
                                </dd>
                            </div>

                            <div>
                                <dt
                                    class="text-xs font-bold uppercase
                                        tracking-wide text-gray-400"
                                >
                                    Model
                                </dt>

                                <dd
                                    class="mt-1 font-semibold
                                        text-gray-900 dark:text-white"
                                >
                                    {{ $qcFacility->model ?: '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Kalibrasi Terakhir --}}
                    <div
                        class="rounded-3xl border border-white/70
                            bg-white p-6 shadow-sm
                            dark:border-gray-800 dark:bg-gray-900"
                    >
                        <h2
                            class="font-bold text-gray-900
                                dark:text-white"
                        >
                            Kalibrasi Terakhir
                        </h2>

                        @if ($latestCalibration)
                            <div class="mt-5 space-y-4">
                                <div>
                                    <p
                                        class="text-xs font-bold uppercase
                                            tracking-wide text-gray-400"
                                    >
                                        Tanggal Kalibrasi
                                    </p>

                                    <p
                                        class="mt-1 font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{
                                            $latestCalibration
                                                ->calibration_date
                                                ?->translatedFormat('d F Y')
                                            ?? '-'
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <p
                                        class="text-xs font-bold uppercase
                                            tracking-wide text-gray-400"
                                    >
                                        Berlaku Sampai
                                    </p>

                                    <p
                                        class="mt-1 font-semibold
                                            text-gray-900 dark:text-white"
                                    >
                                        {{
                                            $latestCalibration
                                                ->calibration_valid_until
                                                ?->translatedFormat('d F Y')
                                            ?? '-'
                                        }}
                                    </p>
                                </div>

                                <span
                                    class="inline-flex rounded-full
                                        px-3 py-1.5 text-xs font-semibold
                                        ring-1 ring-inset
                                        {{ $calibrationClasses }}"
                                >
                                    {{ $calibrationLabel }}
                                </span>
                            </div>
                        @else
                            <p
                                class="mt-5 text-sm text-gray-400"
                            >
                                Belum ada data kalibrasi.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        @if ($canManage)
            <div
                x-cloak
                x-show="openDeleteModal"
                class="fixed inset-0 z-50
                    flex items-center justify-center
                    bg-black/60 p-4"
            >
                <div
                    @click.outside="openDeleteModal = false"
                    class="w-full max-w-md rounded-3xl
                        bg-white p-6 shadow-2xl
                        dark:bg-gray-900"
                >
                    <h3
                        class="text-lg font-bold text-gray-900
                            dark:text-white"
                    >
                        Hapus Unit?
                    </h3>

                    <p
                        class="mt-2 text-sm text-gray-500
                            dark:text-gray-400"
                    >
                        Unit
                        <strong>
                            {{ $qcFacilityUnit->serial_number }}
                        </strong>
                        beserta seluruh riwayat kalibrasinya
                        akan dihapus.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="openDeleteModal = false"
                            class="rounded-xl border
                                border-gray-200 px-4 py-2.5
                                text-sm font-semibold text-gray-700
                                dark:border-gray-700
                                dark:text-gray-300"
                        >
                            Batal
                        </button>

                        <form
                            method="POST"
                            action="{{ route(
                                'qc-facilities.units.destroy',
                                [
                                    'qcFacility' => $qcFacility,
                                    'qcFacilityUnit' => $qcFacilityUnit,
                                ]
                            ) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-xl bg-red-600
                                    px-4 py-2.5 text-sm
                                    font-semibold text-white
                                    hover:bg-red-700"
                            >
                                Hapus Unit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
