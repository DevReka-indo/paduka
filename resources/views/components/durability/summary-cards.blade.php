@props([
    'totalPenggantian' => 0,
    'komponenDurabilityTertinggi' => null,
    'komponenDurabilityTerendah' => null,
    'komponenPenggantianTerbanyak' => null,
])

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-sm">
                <i class="fa-solid fa-rotate text-2xl"></i>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-extrabold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    Total Penggantian
                </p>
                <p class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($totalPenggantian ?? 0, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Total jumlah penggantian
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-sm">
                <i class="fa-solid fa-chart-line text-2xl"></i>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-extrabold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Durability Tertinggi
                </p>
                <h3 class="mt-2 truncate text-xl font-bold text-gray-900 dark:text-white"
                    title="{{ $komponenDurabilityTertinggi->nama_komponen ?? '-' }}">
                    {{ $komponenDurabilityTertinggi->nama_komponen ?? '-' }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Rata-rata rentang penggantian
                </p>
                <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ round($komponenDurabilityTertinggi->rata_rentang ?? 0) }}
                    <span class="text-base font-semibold text-gray-500 dark:text-gray-400">Bulan</span>
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-red-600 text-white shadow-sm">
                <i class="fa-solid fa-arrow-trend-down text-2xl"></i>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-extrabold uppercase tracking-wide text-red-600 dark:text-red-400">
                    Durability Terendah
                </p>
                <h3 class="mt-2 truncate text-xl font-bold text-gray-900 dark:text-white"
                    title="{{ $komponenDurabilityTerendah->nama_komponen ?? '-' }}">
                    {{ $komponenDurabilityTerendah->nama_komponen ?? '-' }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Rata-rata rentang penggantian
                </p>
                <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                    {{ round($komponenDurabilityTerendah->rata_rentang ?? 0) }}
                    <span class="text-base font-semibold text-gray-500 dark:text-gray-400">Bulan</span>
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-orange-500 text-white shadow-sm">
                <i class="fa-solid fa-chart-simple text-2xl"></i>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-extrabold uppercase tracking-wide text-orange-500">
                    Penggantian Terbanyak
                </p>
                <h3 class="mt-2 truncate text-xl font-bold text-gray-900 dark:text-white"
                    title="{{ $komponenPenggantianTerbanyak->nama_komponen ?? '-' }}">
                    {{ $komponenPenggantianTerbanyak->nama_komponen ?? '-' }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Total penggantian
                </p>
                <p class="mt-2 text-3xl font-bold text-orange-500">
                    {{ number_format($komponenPenggantianTerbanyak->total_penggantian ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
