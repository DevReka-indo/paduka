<div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-indigo-500/10 blur-3xl"></div>
    <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Dashboard Operasional
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Selamat datang, {{ Auth::user()->name }}
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 text-right dark:border-gray-800 dark:bg-gray-800/70">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                Status Sistem
            </p>
            <p class="mt-1 inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Aktif
            </p>
        </div>
    </div>
</div>
