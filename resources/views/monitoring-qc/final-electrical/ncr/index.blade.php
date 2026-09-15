@extends('layouts.app')

@section('header')
    Detail NCR QC Final - Elektrik
@endsection

@section('content')
    @php
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $inputClass = 'block w-full rounded-xl border-gray-300
            bg-white text-sm text-gray-700 shadow-sm
            focus:border-indigo-500 focus:ring-indigo-500
            dark:border-gray-600 dark:bg-gray-900
            dark:text-gray-200';
    @endphp

    <div class="w-full max-w-full space-y-6">
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
                    class="mt-1 text-2xl font-bold text-gray-900
                        dark:text-gray-100"
                >
                    Detail NCR
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Monitoring ketidaksesuaian dan status NCR
                    Final Inspection Elektrik.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.dashboard'
                    ) }}"
                    class="rounded-xl border border-indigo-200
                        bg-indigo-50 px-4 py-2.5 text-sm
                        font-semibold text-indigo-700
                        hover:bg-indigo-100
                        dark:border-indigo-800
                        dark:bg-indigo-900/20
                        dark:text-indigo-300"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.ncr.create'
                    ) }}"
                    class="rounded-xl bg-indigo-600 px-4 py-2.5
                        text-sm font-semibold text-white shadow-sm
                        hover:bg-indigo-700"
                >
                    Tambah Detail NCR
                </a>
            </div>
        </div>

        {{-- Summary --}}
        <div
            class="grid grid-cols-2 gap-4
                lg:grid-cols-3 2xl:grid-cols-6"
        >
            @php
                $cards = [
                    [
                        'label' => 'Detail NCR',
                        'value' => $summary['total_details'],
                    ],
                    [
                        'label' => 'NCR Unik',
                        'value' => $summary['unique_ncr'],
                    ],
                    [
                        'label' => 'Open',
                        'value' => $summary['open'],
                    ],
                    [
                        'label' => 'Close',
                        'value' => $summary['closed'],
                    ],
                    [
                        'label' => 'Pending',
                        'value' => $summary['pending'],
                    ],
                    [
                        'label' => 'Total Finding',
                        'value' => $summary['total_findings'],
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="rounded-2xl border border-gray-100
                        bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase
                            tracking-wide text-gray-500
                            dark:text-gray-400"
                    >
                        {{ $card['label'] }}
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold
                            text-gray-900 dark:text-gray-100"
                    >
                        {{ number_format($card['value']) }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <form
            method="GET"
            class="rounded-3xl border border-gray-100
                bg-white p-5 shadow-sm
                dark:border-gray-700 dark:bg-gray-800"
        >
            <div
                class="grid grid-cols-1 gap-4
                    sm:grid-cols-2 lg:grid-cols-4
                    2xl:grid-cols-7"
            >
                <div class="sm:col-span-2">
                    <label
                        for="search"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Pencarian
                    </label>

                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="NCR, produk, lokasi, uraian..."
                        class="{{ $inputClass }}"
                    >
                </div>

                <div>
                    <label
                        for="year"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Tahun
                    </label>

                    <select
                        id="year"
                        name="year"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua
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
                    <label
                        for="month"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Bulan
                    </label>

                    <select
                        id="month"
                        name="month"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua
                        </option>

                        @foreach ($months as $number => $month)
                            <option
                                value="{{ $number }}"
                                @selected(
                                    (string) request('month')
                                    === (string) $number
                                )
                            >
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label
                        for="project_id"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Project
                    </label>

                    <select
                        id="project_id"
                        name="project_id"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua
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
                                —
                                {{ $project->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label
                        for="component_status"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Status
                    </label>

                    <select
                        id="component_status"
                        name="component_status"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua
                        </option>

                        <option
                            value="ok"
                            @selected(
                                request('component_status') === 'ok'
                            )
                        >
                            Close
                        </option>

                        <option
                            value="nok"
                            @selected(
                                request('component_status') === 'nok'
                            )
                        >
                            Open
                        </option>

                        <option
                            value="pending"
                            @selected(
                                request('component_status') === 'pending'
                            )
                        >
                            Pending
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-gray-900
                            px-4 py-2.5 text-sm font-semibold
                            text-white hover:bg-gray-800
                            dark:bg-indigo-600
                            dark:hover:bg-indigo-700"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route(
                            'monitoring-qc.final-electrical.ncr.index'
                        ) }}"
                        class="rounded-xl border border-gray-300
                            px-4 py-2.5 text-sm font-medium
                            text-gray-600 hover:bg-gray-50
                            dark:border-gray-600
                            dark:text-gray-300
                            dark:hover:bg-gray-700"
                    >
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div
            class="overflow-hidden rounded-3xl
                border border-gray-100 bg-white shadow-sm
                dark:border-gray-700 dark:bg-gray-800"
        >
            <div class="overflow-x-auto">
                <table
                    class="w-full min-w-[1200px] text-sm"
                >
                    <thead
                        class="bg-gray-50 text-left text-xs
                            font-semibold uppercase tracking-wide
                            text-gray-500 dark:bg-gray-900/50
                            dark:text-gray-400"
                    >
                        <tr>
                            <th class="px-5 py-4">
                                NCR / Tanggal
                            </th>

                            <th class="px-5 py-4">
                                Project / Product
                            </th>

                            <th class="px-5 py-4">
                                Ketidaksesuaian
                            </th>

                            <th class="px-5 py-4 text-center">
                                Finding
                            </th>

                            <th class="px-5 py-4">
                                Status NCR
                            </th>

                            <th class="px-5 py-4">
                                Inspector
                            </th>

                            <th class="px-5 py-4">
                                Daily Check
                            </th>

                            <th class="px-5 py-4 text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        class="divide-y divide-gray-100
                            dark:divide-gray-700"
                    >
                        @forelse ($ncrDetails as $ncrDetail)
                            @php
                                $statusClass = match (
                                    $ncrDetail->ncr_status
                                ) {
                                    'close' =>
                                        'bg-emerald-50 text-emerald-700
                                        dark:bg-emerald-900/20
                                        dark:text-emerald-300',

                                    'open' =>
                                        'bg-red-50 text-red-700
                                        dark:bg-red-900/20
                                        dark:text-red-300',

                                    default =>
                                        'bg-gray-100 text-gray-600
                                        dark:bg-gray-700
                                        dark:text-gray-300',
                                };
                            @endphp

                            <tr
                                class="transition hover:bg-gray-50/70
                                    dark:hover:bg-gray-700/30"
                            >
                                <td
                                    class="whitespace-nowrap
                                        px-5 py-4"
                                >
                                    <p
                                        class="font-semibold
                                            text-gray-900
                                            dark:text-gray-100"
                                    >
                                        {{ $ncrDetail->ncr_number }}
                                    </p>

                                    <p
                                        class="mt-1 text-xs
                                            text-gray-500"
                                    >
                                        {{ $ncrDetail->issued_date
                                            ?->format('d/m/Y') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p
                                        class="max-w-[260px] truncate
                                            font-medium text-gray-900
                                            dark:text-gray-100"
                                    >
                                        {{ $ncrDetail->product_name }}
                                    </p>

                                    <p
                                        class="mt-1 max-w-[260px]
                                            truncate text-xs
                                            text-gray-500"
                                    >
                                        {{ $ncrDetail->project_name }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p
                                        class="max-w-[320px]
                                            line-clamp-2 text-gray-600
                                            dark:text-gray-300"
                                    >
                                        {{ $ncrDetail
                                            ->nonconformity_description }}
                                    </p>

                                    <p
                                        class="mt-1 max-w-[320px]
                                            truncate text-xs
                                            text-gray-500"
                                    >
                                        {{ $ncrDetail
                                            ->nonconformity_location
                                            ?: '-' }}
                                    </p>
                                </td>

                                <td
                                    class="px-5 py-4 text-center"
                                >
                                    <span
                                        class="inline-flex min-w-9
                                            justify-center rounded-lg
                                            bg-orange-50 px-2.5 py-1
                                            font-semibold text-orange-700
                                            dark:bg-orange-900/20
                                            dark:text-orange-300"
                                    >
                                        {{ $ncrDetail->total_findings }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex rounded-full
                                            px-2.5 py-1 text-xs
                                            font-semibold {{ $statusClass }}"
                                    >
                                        {{ $ncrDetail
                                            ->ncr_status_label }}
                                    </span>
                                </td>

                                <td
                                    class="px-5 py-4 text-gray-600
                                        dark:text-gray-300"
                                >
                                    {{ $ncrDetail->inspector ?: '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($ncrDetail->dailyCheck)
                                        <a
                                            href="{{ route(
                                                'monitoring-qc.final-electrical.daily-check.show',
                                                $ncrDetail->dailyCheck
                                            ) }}"
                                            class="font-medium
                                                text-indigo-600
                                                hover:underline
                                                dark:text-indigo-400"
                                        >
                                            #{{ $ncrDetail
                                                ->dailyCheck->id }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td
                                    class="whitespace-nowrap
                                        px-5 py-4 text-right"
                                >
                                    <a
                                        href="{{ route(
                                            'monitoring-qc.final-electrical.ncr.show',
                                            $ncrDetail
                                        ) }}"
                                        class="font-medium
                                            text-indigo-600
                                            hover:text-indigo-700
                                            dark:text-indigo-400"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route(
                                            'monitoring-qc.final-electrical.ncr.edit',
                                            $ncrDetail
                                        ) }}"
                                        class="ml-3 font-medium
                                            text-gray-600
                                            hover:text-gray-900
                                            dark:text-gray-400
                                            dark:hover:text-gray-200"
                                    >
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="px-6 py-16 text-center"
                                >
                                    <p
                                        class="font-medium
                                            text-gray-700
                                            dark:text-gray-300"
                                    >
                                        Belum ada Detail NCR.
                                    </p>

                                    <p
                                        class="mt-1 text-sm
                                            text-gray-500"
                                    >
                                        Tambahkan NCR QC Final Elektrik
                                        untuk memulai monitoring.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ncrDetails->hasPages())
                <div
                    class="border-t border-gray-100
                        px-5 py-4 dark:border-gray-700"
                >
                    {{ $ncrDetails->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
