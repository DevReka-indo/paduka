@extends('layouts.app')

@section('header')
    Daily Check QC Final - Elektrik
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
            dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200';
    @endphp

    <div
        class="w-full max-w-full space-y-6"
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
                    Monitoring QC
                </p>

                <h1
                    class="mt-1 text-2xl font-bold text-gray-900
                        dark:text-gray-100"
                >
                    QC Final - Elektrik
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    Daily Check Final Inspection Elektrik.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.dashboard'
                    ) }}"
                    class="rounded-xl border border-indigo-200
                        bg-indigo-50 px-4 py-2.5 text-sm font-semibold
                        text-indigo-700 transition hover:bg-indigo-100
                        dark:border-indigo-800 dark:bg-indigo-900/20
                        dark:text-indigo-300 dark:hover:bg-indigo-900/30"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route(
                        'monitoring-qc.final-electrical.daily-check.create'
                    ) }}"
                    class="rounded-xl bg-indigo-600 px-4 py-2.5
                        text-sm font-semibold text-white shadow-sm
                        transition hover:bg-indigo-700"
                >
                    Tambah Daily Check
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
                        'label' => 'Total Check',
                        'value' => $summary['total'],
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
                        'label' => 'Total Temuan',
                        'value' => $summary['total_findings'],
                    ],
                    [
                        'label' => 'NCR',
                        'value' => $summary['total_ncr'],
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="rounded-2xl border border-gray-100 bg-white
                        p-5 shadow-sm dark:border-gray-700
                        dark:bg-gray-800"
                >
                    <p
                        class="text-xs font-medium uppercase tracking-wide
                            text-gray-500 dark:text-gray-400"
                    >
                        {{ $card['label'] }}
                    </p>

                    <p
                        class="mt-2 text-2xl font-bold text-gray-900
                            dark:text-gray-100"
                    >
                        {{ number_format($card['value']) }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <form
            method="GET"
            class="rounded-3xl border border-gray-100 bg-white p-5
                shadow-sm dark:border-gray-700 dark:bg-gray-800"
        >
            <div
                class="grid grid-cols-1 gap-4
                    sm:grid-cols-2 lg:grid-cols-4 2xl:grid-cols-7"
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
                        placeholder="Produk, SN, car, NCR, inspector..."
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
                        for="inspection_gate"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Lokasi
                    </label>

                    <select
                        id="inspection_gate"
                        name="inspection_gate"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua
                        </option>

                        @foreach ($gates as $gate)
                            <option
                                value="{{ $gate }}"
                                @selected(
                                    request('inspection_gate')
                                    === $gate
                                )
                            >
                                {{ $gate }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label
                        for="result"
                        class="mb-1.5 block text-xs font-medium
                            text-gray-600 dark:text-gray-400"
                    >
                        Result
                    </label>

                    <select
                        id="result"
                        name="result"
                        class="{{ $inputClass }}"
                    >
                        <option value="">
                            Semua
                        </option>

                        <option
                            value="ok"
                            @selected(request('result') === 'ok')
                        >
                            OK
                        </option>

                        <option
                            value="nok"
                            @selected(request('result') === 'nok')
                        >
                            NOK
                        </option>

                        <option
                            value="pending"
                            @selected(request('result') === 'pending')
                        >
                            Pending
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-gray-900 px-4 py-2.5
                            text-sm font-semibold text-white
                            hover:bg-gray-800
                            dark:bg-indigo-600 dark:hover:bg-indigo-700"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route(
                            'monitoring-qc.final-electrical.daily-check.index'
                        ) }}"
                        class="rounded-xl border border-gray-300
                            px-4 py-2.5 text-sm font-medium text-gray-600
                            hover:bg-gray-50 dark:border-gray-600
                            dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div
            class="overflow-hidden rounded-3xl border border-gray-100
                bg-white shadow-sm dark:border-gray-700
                dark:bg-gray-800"
        >
            <div class="overflow-x-auto">
                <table
                    class="w-full min-w-[1100px] text-sm"
                >
                    <thead
                        class="bg-gray-50 text-left text-xs font-semibold
                            uppercase tracking-wide text-gray-500
                            dark:bg-gray-900/50 dark:text-gray-400"
                    >
                        <tr>
                            <th class="px-5 py-4">
                                Tanggal
                            </th>

                            <th class="px-5 py-4">
                                Project / Product
                            </th>

                            <th class="px-5 py-4">
                                Unit
                            </th>

                            <th class="px-5 py-4">
                                Lokasi
                            </th>

                            <th class="px-5 py-4 text-center">
                                Finding
                            </th>

                            <th class="px-5 py-4">
                                Result
                            </th>

                            <th class="px-5 py-4">
                                NCR
                            </th>

                            <th class="px-5 py-4">
                                Inspector
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
                        @forelse ($dailyChecks as $dailyCheck)
                            @php
                                $resultClass = match ($dailyCheck->result) {
                                    'ok' =>
                                        'bg-emerald-50 text-emerald-700
                                        dark:bg-emerald-900/20
                                        dark:text-emerald-300',

                                    'nok' =>
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
                                    class="whitespace-nowrap px-5 py-4
                                        text-gray-600 dark:text-gray-300"
                                >
                                    {{ $dailyCheck->check_date
                                        ?->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <p
                                        class="max-w-[300px] truncate
                                            font-semibold text-gray-900
                                            dark:text-gray-100"
                                        title="{{ $dailyCheck->product_name }}"
                                    >
                                        {{ $dailyCheck->product_name }}
                                    </p>

                                    <p
                                        class="mt-1 max-w-[300px] truncate
                                            text-xs text-gray-500"
                                        title="{{ $dailyCheck->project_name }}"
                                    >
                                        {{ $dailyCheck->project_name }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p
                                        class="text-gray-700
                                            dark:text-gray-300"
                                    >
                                        SN:
                                        {{ $dailyCheck->serial_number ?: '-' }}
                                    </p>

                                    <p
                                        class="mt-1 text-xs text-gray-500"
                                    >
                                        {{ $dailyCheck->car_reference ?: '-' }}
                                        /
                                        {{ $dailyCheck->batch_reference ?: '-' }}
                                    </p>
                                </td>

                                <td
                                    class="px-5 py-4 text-gray-600
                                        dark:text-gray-300"
                                >
                                    {{ $dailyCheck->inspection_gate }}
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
                                        {{ $dailyCheck->total_findings }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex rounded-full
                                            px-2.5 py-1 text-xs
                                            font-semibold {{ $resultClass }}"
                                    >
                                        {{ strtoupper(
                                            $dailyCheck->result
                                        ) }}
                                    </span>
                                </td>

                                <td
                                    class="px-5 py-4 text-gray-600
                                        dark:text-gray-300"
                                >
                                    {{ $dailyCheck->ncr_number ?: '-' }}
                                </td>

                                <td
                                    class="px-5 py-4 text-gray-600
                                        dark:text-gray-300"
                                >
                                    {{ $dailyCheck->inspector }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4
                                        text-right"
                                >
                                    <a
                                        href="{{ route(
                                            'monitoring-qc.final-electrical.daily-check.show',
                                            $dailyCheck
                                        ) }}"
                                        class="font-medium text-indigo-600
                                            hover:text-indigo-700
                                            dark:text-indigo-400"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route(
                                            'monitoring-qc.final-electrical.daily-check.edit',
                                            $dailyCheck
                                        ) }}"
                                        class="ml-3 font-medium text-gray-600
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
                                    colspan="9"
                                    class="px-6 py-16 text-center"
                                >
                                    <p
                                        class="font-medium text-gray-700
                                            dark:text-gray-300"
                                    >
                                        Belum ada Daily Check.
                                    </p>

                                    <p
                                        class="mt-1 text-sm text-gray-500"
                                    >
                                        Tambahkan data Final Inspection Elektrik
                                        untuk memulai monitoring.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($dailyChecks->hasPages())
                <div
                    class="border-t border-gray-100 px-5 py-4
                        dark:border-gray-700"
                >
                    {{ $dailyChecks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
