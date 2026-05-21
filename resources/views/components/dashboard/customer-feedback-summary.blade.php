@props([
    'avgFeedbackScore' => null,
    'feedbackPercentage' => null,
    'totalFeedback' => 0,
    'maxFeedbackScore' => null,
    'minFeedbackScore' => null,
])

<div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-col gap-4 border-b border-gray-100 p-6 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                Customer Feedback Summary
            </p>
            <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                Ringkasan Kepuasan Pelanggan
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Ringkasan feedback berdasarkan periode dashboard.
            </p>
        </div>

        <a href="{{ route('feedback.index') }}"
           class="inline-flex w-fit items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            Lihat feedback
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-amber-100 bg-amber-50 p-5 dark:border-amber-900/30 dark:bg-amber-900/20">
            <p class="text-sm font-semibold text-amber-700 dark:text-amber-300">
                Rata-rata Skor
            </p>

            <div class="mt-4 flex items-end gap-2">
                <p class="text-4xl font-bold tracking-tight text-amber-700 dark:text-amber-300">
                    {{ isset($avgFeedbackScore) && $avgFeedbackScore ? number_format($avgFeedbackScore, 2) : '—' }}
                </p>
                <span class="mb-1 text-sm font-medium text-amber-700/60 dark:text-amber-300/60">
                    / 4
                </span>
            </div>

            <p class="mt-2 text-sm text-amber-700/70 dark:text-amber-300/70">
                Nilai rata-rata feedback pelanggan.
            </p>
        </div>

        <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-5 dark:border-emerald-900/30 dark:bg-emerald-900/20">
            <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                Presentase KPI
            </p>

            <p class="mt-4 text-4xl font-bold tracking-tight text-emerald-700 dark:text-emerald-300">
                {{ isset($feedbackPercentage) && $feedbackPercentage ? number_format($feedbackPercentage, 2) . '%' : '—' }}
            </p>

            <p class="mt-2 text-sm text-emerald-700/70 dark:text-emerald-300/70">
                Capaian feedback terhadap target KPI.
            </p>
        </div>

        <div class="rounded-3xl border border-blue-100 bg-blue-50 p-5 dark:border-blue-900/30 dark:bg-blue-900/20">
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                Total Survey
            </p>

            <p class="mt-4 text-4xl font-bold tracking-tight text-blue-700 dark:text-blue-300">
                {{ number_format($totalFeedback ?? 0) }}
            </p>

            <p class="mt-2 text-sm text-blue-700/70 dark:text-blue-300/70">
                Jumlah feedback yang masuk.
            </p>
        </div>

        <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/40">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                Range Nilai
            </p>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-white p-4 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Tertinggi
                    </p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ isset($maxFeedbackScore) && $maxFeedbackScore ? number_format($maxFeedbackScore, 2) : '—' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-4 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Terendah
                    </p>
                    <p class="mt-2 text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ isset($minFeedbackScore) && $minFeedbackScore ? number_format($minFeedbackScore, 2) : '—' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
