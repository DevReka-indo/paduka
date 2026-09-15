@extends('layouts.app')

@section('header')
    Detail NCR QC Final - Elektrik
@endsection

@section('content')
    @php
        $statusClass = match (
            $ncrDetail->ncr_status
        ) {
            'close' =>
                'bg-emerald-50 text-emerald-700 border-emerald-200
                dark:bg-emerald-900/20 dark:text-emerald-300
                dark:border-emerald-800/50',

            'open' =>
                'bg-red-50 text-red-700 border-red-200
                dark:bg-red-900/20 dark:text-red-300
                dark:border-red-800/50',

            default =>
                'bg-gray-50 text-gray-700 border-gray-200
                dark:bg-gray-700/50 dark:text-gray-300
                dark:border-gray-600',
        };

        $findingItems = [
            'Visual' =>
                $ncrDetail->visual_qty,

            'Kelengkapan' =>
                $ncrDetail->completeness_qty,

            'Spesifikasi' =>
                $ncrDetail->specification_qty,

            'Dimensi' =>
                $ncrDetail->dimension_qty,

            'Fungsi' =>
                $ncrDetail->function_qty,
        ];
    @endphp

    <div
        x-data="{ deleteOpen: false }"
        class="mx-auto w-full max-w-[1700px] space-y-6"
    >
        @if (session('success'))
            <div
                class="rounded-2xl border border-emerald-200
                    bg-emerald-50 px-5 py-4 text-sm
                    text-emerald-700
                    dark:border-emerald-800/50
                    dark:bg-emerald-900/20
                    dark:text-emerald-300"
            >
                {{ session('success') }}
            </div>
        @endif

        <div
            class="flex flex-col gap-4
                lg:flex-row lg:items-start lg:justify-between"
        >
            <div>
                <p
                    class="text-sm font-medium text-indigo-600
                        dark:text-indigo-400"
                >
                    Monitoring QC · QC Final - Elektrik
                </p>

                <h1
                    class="mt-1 text-2xl font-bold
                        text-gray-900 dark:text-gray-100"
                >
                    NCR {{ $ncrDetail->ncr_number }}
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    {{ $ncrDetail->product_name }}
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span
                        class="inline-flex rounded-full border
                            px-3 py-1 text-xs font-semibold
                            {{ $statusClass }}"
                    >
                        {{ $ncrDetail->ncr_status_label }}
                    </span>

                    <span
                        class="rounded-full bg-gray-100
                            px-3 py-1 text-xs text-gray-600
                            dark:bg-gray-700
                            dark:text-gray-300"
                    >
                        {{ $ncrDetail->issued_date
                            ?->format('d/m/Y') }}
                    </span>

                    <span
                        class="rounded-full bg-indigo-50
                            px-3 py-1 text-xs font-medium
                            text-indigo-700
                            dark:bg-indigo-900/20
                            dark:text-indigo-300"
                    >
                        {{ strtoupper($ncrDetail->source) }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.ncr.index'
                    ) }}"
                    class="rounded-xl border border-gray-300
                        bg-white px-4 py-2.5 text-sm font-medium
                        text-gray-700 shadow-sm hover:bg-gray-50
                        dark:border-gray-600 dark:bg-gray-800
                        dark:text-gray-300
                        dark:hover:bg-gray-700"
                >
                    Kembali
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.ncr.edit',
                        $ncrDetail
                    ) }}"
                    class="rounded-xl bg-indigo-600
                        px-4 py-2.5 text-sm font-semibold
                        text-white shadow-sm hover:bg-indigo-700"
                >
                    Edit
                </a>

                <button
                    type="button"
                    @click="deleteOpen = true"
                    class="rounded-xl bg-red-600 px-4 py-2.5
                        text-sm font-semibold text-white
                        shadow-sm hover:bg-red-700"
                >
                    Hapus
                </button>
            </div>
        </div>

        {{-- Finding Summary --}}
        <div
            class="grid grid-cols-2 gap-4
                lg:grid-cols-3 xl:grid-cols-6"
        >
            <div
                class="rounded-2xl border border-gray-100
                    bg-white p-5 shadow-sm
                    dark:border-gray-700 dark:bg-gray-800"
            >
                <p class="text-xs font-medium text-gray-500">
                    Total Finding
                </p>

                <p
                    class="mt-2 text-2xl font-bold
                        text-orange-600 dark:text-orange-400"
                >
                    {{ number_format(
                        $ncrDetail->total_findings
                    ) }}
                </p>
            </div>

            @foreach ($findingItems as $label => $value)
                <div
                    class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <p class="text-xs font-medium text-gray-500">
                        {{ $label }}
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold
                            text-gray-900 dark:text-gray-100"
                    >
                        {{ number_format($value) }}
                    </p>
                </div>
            @endforeach
        </div>

        <div
            class="grid grid-cols-1 gap-6 xl:grid-cols-2"
        >
            {{-- Informasi --}}
            <div
                class="rounded-3xl border border-gray-100
                    bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-800"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Informasi NCR
                </h2>

                <dl
                    class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5
                        sm:grid-cols-2"
                >
                    <div>
                        <dt class="text-xs text-gray-500">
                            Nomor NCR
                        </dt>

                        <dd
                            class="mt-1 text-sm font-semibold
                                text-gray-900 dark:text-gray-100"
                        >
                            {{ $ncrDetail->ncr_number }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Tanggal Terbit
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->issued_date
                                ?->format('d/m/Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Project
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->project_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Produk / Proses
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->product_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Inspector
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->inspector ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Cycle Time
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            @if (
                                $ncrDetail->cycle_time_minutes
                                !== null
                            )
                                {{ number_format(
                                    (float)
                                    $ncrDetail->cycle_time_minutes,
                                    2
                                ) }}
                                menit
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Status --}}
            <div
                class="rounded-3xl border border-gray-100
                    bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-800"
            >
                <h2
                    class="text-base font-semibold
                        text-gray-900 dark:text-gray-100"
                >
                    Status & Referensi
                </h2>

                <dl
                    class="mt-5 grid grid-cols-1
                        gap-x-6 gap-y-5 sm:grid-cols-2"
                >
                    <div>
                        <dt class="text-xs text-gray-500">
                            Status Komponen
                        </dt>

                        <dd
                            class="mt-1 text-sm font-semibold
                                text-gray-900 dark:text-gray-100"
                        >
                            {{ strtoupper(
                                $ncrDetail->component_status
                            ) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Status NCR
                        </dt>

                        <dd
                            class="mt-1 text-sm font-semibold
                                text-gray-900 dark:text-gray-100"
                        >
                            {{ $ncrDetail->ncr_status_label }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500">
                            Daily Check
                        </dt>

                        <dd class="mt-1">
                            @if ($ncrDetail->dailyCheck)
                                <a
                                    href="{{ route(
                                        'monitoring-qc.final-electrical.daily-check.show',
                                        $ncrDetail->dailyCheck
                                    ) }}"
                                    class="text-sm font-medium
                                        text-indigo-600 hover:underline
                                        dark:text-indigo-400"
                                >
                                    {{ $ncrDetail
                                        ->dailyCheck
                                        ->check_date
                                        ?->format('d/m/Y') }}
                                    —
                                    {{ $ncrDetail
                                        ->dailyCheck
                                        ->product_name }}
                                </a>
                            @else
                                <span
                                    class="text-sm text-gray-500"
                                >
                                    Tidak terhubung
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Ketidaksesuaian --}}
            <div
                class="rounded-3xl border border-gray-100
                    bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-800
                    xl:col-span-2"
            >
                <h2
                    class="text-base font-semibold
                        text-gray-900 dark:text-gray-100"
                >
                    Detail Ketidaksesuaian
                </h2>

                <dl
                    class="mt-5 grid grid-cols-1 gap-5
                        md:grid-cols-2"
                >
                    <div>
                        <dt class="text-xs text-gray-500">
                            Lokasi Ketidaksesuaian
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail
                                ->nonconformity_location
                                ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Unit Yang Dituju
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->target_unit ?: '-' }}
                        </dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-xs text-gray-500">
                            Uraian Ketidaksesuaian
                        </dt>

                        <dd
                            class="mt-2 whitespace-pre-line
                                rounded-2xl bg-gray-50 p-4
                                text-sm leading-6 text-gray-700
                                dark:bg-gray-900/50
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail
                                ->nonconformity_description }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Audit --}}
            <div
                class="rounded-3xl border border-gray-100
                    bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-800
                    xl:col-span-2"
            >
                <h2
                    class="text-base font-semibold
                        text-gray-900 dark:text-gray-100"
                >
                    Audit Data
                </h2>

                <div
                    class="mt-5 grid grid-cols-1 gap-5
                        md:grid-cols-4"
                >
                    <div>
                        <p class="text-xs text-gray-500">
                            Source
                        </p>

                        <p
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->source }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Dibuat Oleh
                        </p>

                        <p
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->creator?->name
                                ?? 'Legacy Import' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Dibuat
                        </p>

                        <p
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->created_at
                                ?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Diperbarui
                        </p>

                        <p
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $ncrDetail->updated_at
                                ?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div
            x-cloak
            x-show="deleteOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center
                justify-center bg-black/50 p-4"
        >
            <div
                @click.outside="deleteOpen = false"
                class="w-full max-w-md rounded-3xl
                    bg-white p-6 shadow-xl
                    dark:bg-gray-800"
            >
                <h3
                    class="text-lg font-semibold
                        text-gray-900 dark:text-gray-100"
                >
                    Hapus Detail NCR?
                </h3>

                <p
                    class="mt-2 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Detail NCR {{ $ncrDetail->ncr_number }}
                    akan dihapus.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="deleteOpen = false"
                        class="rounded-xl border
                            border-gray-300 px-4 py-2
                            text-sm font-medium text-gray-700
                            dark:border-gray-600
                            dark:text-gray-300"
                    >
                        Batal
                    </button>

                    <form
                        method="POST"
                        action="{{ route(
                            'monitoring-qc.final-electrical.ncr.destroy',
                            $ncrDetail
                        ) }}"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-xl bg-red-600
                                px-4 py-2 text-sm font-semibold
                                text-white hover:bg-red-700"
                        >
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
