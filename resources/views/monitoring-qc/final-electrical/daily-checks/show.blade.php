@extends('layouts.app')

@section('header')
    Detail Daily Check QC Final - Elektrik
@endsection

@section('content')
    @php
        $statusClass = match ($dailyCheck->status_product) {
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
            'Visual' => $dailyCheck->visual_qty,
            'Kelengkapan' => $dailyCheck->completeness_qty,
            'Belltest' => $dailyCheck->belltest_qty,
            'Fungsi' => $dailyCheck->function_qty,
            'Torsi' => $dailyCheck->torque_qty,
        ];
    @endphp

    <div
        x-data="{ deleteOpen: false }"
        class="mx-auto w-full max-w-[1700px] space-y-6"
    >
        @if (session('success'))
            <div
                class="rounded-2xl border border-emerald-200 bg-emerald-50
                    px-5 py-4 text-sm text-emerald-700
                    dark:border-emerald-800/50 dark:bg-emerald-900/20
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
                    class="mt-1 text-2xl font-bold text-gray-900
                        dark:text-gray-100"
                >
                    {{ $dailyCheck->product_name }}
                </h1>

                <div
                    class="mt-3 flex flex-wrap items-center gap-2"
                >
                    <span
                        class="inline-flex rounded-full border px-3 py-1
                            text-xs font-semibold {{ $statusClass }}"
                    >
                        {{ $dailyCheck->status_product_label }}
                    </span>

                    <span
                        class="inline-flex rounded-full bg-gray-100 px-3 py-1
                            text-xs font-medium text-gray-600
                            dark:bg-gray-700 dark:text-gray-300"
                    >
                        {{ $dailyCheck->check_date?->format('d/m/Y') }}
                    </span>

                    <span
                        class="inline-flex rounded-full bg-indigo-50 px-3 py-1
                            text-xs font-medium text-indigo-700
                            dark:bg-indigo-900/20 dark:text-indigo-300"
                    >
                        {{ strtoupper($dailyCheck->source) }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.daily-check.index'
                    ) }}"
                    class="rounded-xl border border-gray-300 bg-white
                        px-4 py-2.5 text-sm font-medium text-gray-700
                        shadow-sm hover:bg-gray-50
                        dark:border-gray-600 dark:bg-gray-800
                        dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Kembali
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.ncr.create',
                        [
                            'daily_check_id' => $dailyCheck->id,
                        ]
                    ) }}"
                    class="rounded-xl bg-orange-500 px-4 py-2.5
                        text-sm font-semibold text-white shadow-sm
                        hover:bg-orange-600"
                >
                    Tambah Detail NCR
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.daily-check.edit',
                        $dailyCheck
                    ) }}"
                    class="rounded-xl bg-indigo-600 px-4 py-2.5
                        text-sm font-semibold text-white shadow-sm
                        hover:bg-indigo-700"
                >
                    Edit
                </a>

                <button
                    type="button"
                    @click="deleteOpen = true"
                    class="rounded-xl bg-red-600 px-4 py-2.5
                        text-sm font-semibold text-white shadow-sm
                        hover:bg-red-700"
                >
                    Hapus
                </button>
            </div>
        </div>

        {{-- Summary --}}
        <div
            class="grid grid-cols-2 gap-4
                lg:grid-cols-3 xl:grid-cols-6"
        >
            <div
                class="rounded-2xl border border-gray-100 bg-white p-5
                    shadow-sm dark:border-gray-700 dark:bg-gray-800"
            >
                <p class="text-xs font-medium text-gray-500">
                    Total Finding
                </p>

                <p
                    class="mt-2 text-2xl font-bold text-orange-600
                        dark:text-orange-400"
                >
                    {{ number_format($dailyCheck->total_findings) }}
                </p>
            </div>

            @foreach ($findingItems as $label => $value)
                <div
                    class="rounded-2xl border border-gray-100 bg-white p-5
                        shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >
                    <p class="text-xs font-medium text-gray-500">
                        {{ $label }}
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold text-gray-900
                            dark:text-gray-100"
                    >
                        {{ number_format($value) }}
                    </p>
                </div>
            @endforeach
        </div>

        <div
            class="grid grid-cols-1 gap-6
                xl:grid-cols-2"
        >
            {{-- Inspection --}}
            <div
                class="rounded-3xl border border-gray-100 bg-white p-6
                    shadow-sm dark:border-gray-700 dark:bg-gray-800"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Informasi Pemeriksaan
                </h2>

                <dl
                    class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5
                        sm:grid-cols-2"
                >
                    <div>
                        <dt class="text-xs text-gray-500">
                            Project
                        </dt>

                        <dd
                            class="mt-1 text-sm font-medium text-gray-900
                                dark:text-gray-100"
                        >
                            {{ $dailyCheck->project_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Product
                        </dt>

                        <dd
                            class="mt-1 text-sm font-medium text-gray-900
                                dark:text-gray-100"
                        >
                            {{ $dailyCheck->product_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Doc. Check
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->document_check ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Inspection Gate
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->inspection_gate ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Check Category
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->check_category ?: '-' }}
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
                            {{ $dailyCheck->inspector ?: '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Unit --}}
            <div
                class="rounded-3xl border border-gray-100 bg-white p-6
                    shadow-sm dark:border-gray-700 dark:bg-gray-800"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Identitas Unit
                </h2>

                <dl
                    class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5
                        sm:grid-cols-2"
                >
                    <div>
                        <dt class="text-xs text-gray-500">
                            Serial Number
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->serial_number ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Car
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->car_reference ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            TS / Batch
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->batch_reference ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Status IS
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->status_is ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Result
                        </dt>

                        <dd
                            class="mt-1 text-sm font-semibold text-gray-900
                                dark:text-gray-100"
                        >
                            {{ strtoupper($dailyCheck->result) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            No. NCR
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->ncr_number ?: '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- OIL --}}
            <div
                class="rounded-3xl border border-gray-100 bg-white p-6
                    shadow-sm dark:border-gray-700 dark:bg-gray-800
                    xl:col-span-2"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    OIL
                </h2>

                <dl
                    class="mt-5 grid grid-cols-1 gap-5
                        md:grid-cols-3"
                >
                    <div class="md:col-span-3">
                        <dt class="text-xs text-gray-500">
                            Detail OIL
                        </dt>

                        <dd
                            class="mt-1 whitespace-pre-line text-sm
                                text-gray-700 dark:text-gray-300"
                        >
                            {{ $dailyCheck->oil_description ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Jumlah OIL
                        </dt>

                        <dd
                            class="mt-1 text-sm font-semibold text-gray-900
                                dark:text-gray-100"
                        >
                            {{ number_format($dailyCheck->oil_count) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Closing OIL
                        </dt>

                        <dd
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->closing_oil_date
                                ?->format('d/m/Y') ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-gray-500">
                            Link OIL
                        </dt>

                        <dd class="mt-1 text-sm">
                            @if ($dailyCheck->oil_link)
                                <a
                                    href="{{ $dailyCheck->oil_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="font-medium text-indigo-600
                                        hover:underline dark:text-indigo-400"
                                >
                                    Buka OIL
                                </a>
                            @else
                                <span
                                    class="text-gray-500"
                                >
                                    -
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Audit --}}
            <div
                class="rounded-3xl border border-gray-100 bg-white p-6
                    shadow-sm dark:border-gray-700 dark:bg-gray-800
                    xl:col-span-2"
            >
                <h2
                    class="text-base font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Audit Data
                </h2>

                <div
                    class="mt-5 grid grid-cols-1 gap-5
                        md:grid-cols-3"
                >
                    <div>
                        <p class="text-xs text-gray-500">
                            Source
                        </p>

                        <p
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->source }}
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
                            {{ $dailyCheck->creator?->name
                                ?? 'Legacy Import' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Terakhir Diperbarui
                        </p>

                        <p
                            class="mt-1 text-sm text-gray-700
                                dark:text-gray-300"
                        >
                            {{ $dailyCheck->updated_at
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
            class="fixed inset-0 z-50 flex items-center justify-center
                bg-black/50 p-4"
        >
            <div
                @click.outside="deleteOpen = false"
                class="w-full max-w-md rounded-3xl bg-white p-6
                    shadow-xl dark:bg-gray-800"
            >
                <h3
                    class="text-lg font-semibold text-gray-900
                        dark:text-gray-100"
                >
                    Hapus Daily Check?
                </h3>

                <p
                    class="mt-2 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Data akan dihapus dari daftar monitoring.
                </p>

                <div
                    class="mt-6 flex justify-end gap-3"
                >
                    <button
                        type="button"
                        @click="deleteOpen = false"
                        class="rounded-xl border border-gray-300
                            px-4 py-2 text-sm font-medium text-gray-700
                            dark:border-gray-600 dark:text-gray-300"
                    >
                        Batal
                    </button>

                    <form
                        method="POST"
                        action="{{ route(
                            'monitoring-qc.final-electrical.daily-check.destroy',
                            $dailyCheck
                        ) }}"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-xl bg-red-600 px-4 py-2
                                text-sm font-semibold text-white
                                hover:bg-red-700"
                        >
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
