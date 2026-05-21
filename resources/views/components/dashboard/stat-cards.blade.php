@props([
    'isAdmin' => false,
    'totalNcr' => 0,
    'totalOpen' => 0,
    'totalProses' => 0,
    'totalClose' => 0,
    'totalProject' => null,
    'totalUser' => null,
])

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 {{ $isAdmin ? 'xl:grid-cols-5' : 'xl:grid-cols-4' }}">

    <a href="{{ route('ncr.index') }}"
       class="group relative overflow-hidden rounded-3xl bg-gray-950 p-5 text-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl dark:bg-gray-900 dark:ring-1 dark:ring-gray-800">
        <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10 blur-2xl"></div>

        <div class="relative flex min-h-[128px] flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white/80">
                    NCR
                </span>
            </div>

            <div>
                <p class="text-4xl font-bold tracking-tight">
                    {{ number_format($totalNcr) }}
                </p>
                <p class="mt-1 text-sm text-white/60">
                    {{ $isAdmin ? 'Total Semua NCR' : 'Total NCR' }}
                </p>
            </div>
        </div>
    </a>

    <a href="{{ route('ncr.index', ['status' => 'open']) }}"
       class="group rounded-3xl border border-red-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-red-200 hover:shadow-xl dark:border-red-900/30 dark:bg-gray-900">
        <div class="flex min-h-[128px] flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-red-50 dark:bg-red-900/30">
                    <span class="h-3 w-3 rounded-full bg-red-500"></span>
                </div>
                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 dark:bg-red-900/30 dark:text-red-300">
                    Open
                </span>
            </div>

            <div>
                <p class="text-4xl font-bold tracking-tight text-red-600 dark:text-red-400">
                    {{ number_format($totalOpen) }}
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Belum selesai
                </p>
            </div>
        </div>
    </a>

    <a href="{{ route('ncr.index', ['status' => 'process']) }}"
       class="group rounded-3xl border border-amber-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-amber-200 hover:shadow-xl dark:border-amber-900/30 dark:bg-gray-900">
        <div class="flex min-h-[128px] flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 dark:bg-amber-900/30">
                    <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                </div>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600 dark:bg-amber-900/30 dark:text-amber-300">
                    Process
                </span>
            </div>

            <div>
                <p class="text-4xl font-bold tracking-tight text-amber-600 dark:text-amber-400">
                    {{ number_format($totalProses) }}
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Dalam tindak lanjut
                </p>
            </div>
        </div>
    </a>

    <a href="{{ route('ncr.index', ['status' => 'close']) }}"
       class="group rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl dark:border-emerald-900/30 dark:bg-gray-900">
        <div class="flex min-h-[128px] flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-900/30">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                </div>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300">
                    Closed
                </span>
            </div>

            <div>
                <p class="text-4xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400">
                    {{ number_format($totalClose) }}
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Sudah selesai
                </p>
            </div>
        </div>
    </a>

    @if($isAdmin)
        <div class="rounded-3xl border border-indigo-100 bg-white p-5 shadow-sm dark:border-indigo-900/30 dark:bg-gray-900">
            <div class="flex min-h-[128px] flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-900/30">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">
                        Admin
                    </span>
                </div>

                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-4xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                            {{ number_format($totalProject ?? 0) }}
                        </p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Project
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalUser ?? 0) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            User aktif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
