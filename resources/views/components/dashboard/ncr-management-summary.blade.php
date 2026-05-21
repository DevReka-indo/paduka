@props([
    'availableMonths' => collect(),
    'defaultFrom' => null,
    'defaultTo' => null,
    'from' => null,
    'to' => null,
    'isAdmin' => false,
    'periodeLabel' => null,
    'filteredTotalNcr' => 0,
    'filteredTotalOpen' => 0,
    'filteredTotalProses' => 0,
    'filteredTotalClose' => 0,
    'feedbackPercentage' => null,
])

@php
    $closeRate = $filteredTotalNcr > 0 ? round(($filteredTotalClose / $filteredTotalNcr) * 100) : 0;
    $openRate = $filteredTotalNcr > 0 ? round(($filteredTotalOpen / $filteredTotalNcr) * 100) : 0;
    $feedbackKpi = isset($feedbackPercentage) && $feedbackPercentage ? round($feedbackPercentage) : 0;

    $pctOpen = $filteredTotalNcr > 0 ? round($filteredTotalOpen / $filteredTotalNcr * 100) : 0;
    $pctProses = $filteredTotalNcr > 0 ? round($filteredTotalProses / $filteredTotalNcr * 100) : 0;
    $pctClose = $filteredTotalNcr > 0 ? round($filteredTotalClose / $filteredTotalNcr * 100) : 0;
@endphp

<div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-col gap-4 border-b border-gray-100 p-6 dark:border-gray-800 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                NCR Management Summary
            </p>
            <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                Tren, Proporsi, dan Indikator NCR
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Periode {{ $periodeLabel ?? 'aktif' }}
            </p>
        </div>

        {{-- <form method="GET" class="flex flex-wrap items-center gap-2">
            <select name="from"
                    onchange="this.form.submit()"
                    class="rounded-xl border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                @foreach($availableMonths as $month)
                    <option value="{{ $month['value'] }}"
                        {{ request('from', $defaultFrom) == $month['value'] ? 'selected' : '' }}>
                        {{ $month['label'] }}
                    </option>
                @endforeach
            </select>

            <span class="text-sm text-gray-400">s/d</span>

            <select name="to"
                    onchange="this.form.submit()"
                    class="rounded-xl border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                @foreach($availableMonths as $month)
                    <option value="{{ $month['value'] }}"
                        {{ request('to', $defaultTo) == $month['value'] ? 'selected' : '' }}>
                        {{ $month['label'] }}
                    </option>
                @endforeach
            </select>
        </form> --}}
    </div>

    <div class="space-y-4 p-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-5 dark:border-emerald-900/30 dark:bg-emerald-900/20">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                        NCR Closure Rate
                    </p>
                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-emerald-700 shadow-sm dark:bg-gray-900 dark:text-emerald-300">
                        KPI
                    </span>
                </div>

                <p class="mt-4 text-4xl font-bold tracking-tight text-emerald-700 dark:text-emerald-300">
                    {{ $closeRate }}%
                </p>

                <p class="mt-2 text-sm text-emerald-700/70 dark:text-emerald-300/70">
                    {{ number_format($filteredTotalClose) }} dari {{ number_format($filteredTotalNcr) }} NCR selesai.
                </p>
            </div>

            <div class="rounded-3xl border border-red-100 bg-red-50 p-5 dark:border-red-900/30 dark:bg-red-900/20">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-red-700 dark:text-red-300">
                        Open Issue Rate
                    </p>
                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-red-700 shadow-sm dark:bg-gray-900 dark:text-red-300">
                        Risk
                    </span>
                </div>

                <p class="mt-4 text-4xl font-bold tracking-tight text-red-700 dark:text-red-300">
                    {{ $openRate }}%
                </p>

                <p class="mt-2 text-sm text-red-700/70 dark:text-red-300/70">
                    {{ number_format($filteredTotalOpen) }} NCR masih berstatus open.
                </p>
            </div>

            <div class="rounded-3xl border border-amber-100 bg-amber-50 p-5 dark:border-amber-900/30 dark:bg-amber-900/20">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-amber-700 dark:text-amber-300">
                        Feedback KPI
                    </p>
                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-amber-700 shadow-sm dark:bg-gray-900 dark:text-amber-300">
                        CSAT
                    </span>
                </div>

                <p class="mt-4 text-4xl font-bold tracking-tight text-amber-700 dark:text-amber-300">
                    {{ $feedbackKpi }}%
                </p>

                <p class="mt-2 text-sm text-amber-700/70 dark:text-amber-300/70">
                    Berdasarkan rata-rata feedback pelanggan.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/40 xl:col-span-2">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                            Tren NCR per Bulan
                        </h4>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Statistik NCR berdasarkan status bulanan.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded bg-red-400"></span>
                            Open
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded bg-amber-400"></span>
                            Process
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded bg-emerald-400"></span>
                            Close
                        </span>
                    </div>
                </div>

                <div class="relative h-72">
                    <canvas id="chartTren"></canvas>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/40">
                <div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-white">
                        Proporsi Status
                    </h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $from)->translatedFormat('F Y') }}
                        -
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $to)->translatedFormat('F Y') }}
                        {{ !$isAdmin ? ' — NCR Anda' : '' }}
                    </p>
                </div>

                <div class="mt-6 flex items-center justify-center">
                    <div class="relative h-48 w-48">
                        <canvas id="chartDonut"></canvas>

                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($filteredTotalNcr) }}
                            </p>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                Total NCR
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 dark:bg-gray-900">
                        <span class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            Open
                        </span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ number_format($filteredTotalOpen) }}
                            <span class="font-normal text-gray-400">({{ $pctOpen }}%)</span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 dark:bg-gray-900">
                        <span class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            Process
                        </span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ number_format($filteredTotalProses) }}
                            <span class="font-normal text-gray-400">({{ $pctProses }}%)</span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 dark:bg-gray-900">
                        <span class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            Close
                        </span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ number_format($filteredTotalClose) }}
                            <span class="font-normal text-gray-400">({{ $pctClose }}%)</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
