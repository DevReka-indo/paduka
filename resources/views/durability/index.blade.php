<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-gray-100">
                        Dashboard Durability Produk
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">
                        Monitoring performa durability produk berdasarkan data pengujian, status, dan tren hasil durability.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                    <p class="text-sm text-slate-500 dark:text-gray-400">Total Produk Diuji</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-gray-100">0</p>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                    <p class="text-sm text-slate-500 dark:text-gray-400">Passed</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">0</p>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                    <p class="text-sm text-slate-500 dark:text-gray-400">Failed</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">0</p>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                    <p class="text-sm text-slate-500 dark:text-gray-400">On Progress</p>
                    <p class="mt-2 text-2xl font-bold text-amber-500">0</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-gray-700">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-gray-100">
                        Data Durability Produk
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-gray-400">
                        Daftar produk dan status pengujian durability.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-gray-700/50 text-slate-500 dark:text-gray-400 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3 text-left">Kode Produk</th>
                                <th class="px-5 py-3 text-left">Nama Produk</th>
                                <th class="px-5 py-3 text-left">Kategori</th>
                                <th class="px-5 py-3 text-left">Target Durability</th>
                                <th class="px-5 py-3 text-left">Actual</th>
                                <th class="px-5 py-3 text-left">Status</th>
                                <th class="px-5 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-gray-700 text-slate-700 dark:text-gray-300">
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada data durability produk.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
