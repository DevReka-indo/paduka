@props([
    'availableTrendMonths' => collect(),
    'trendFrom' => null,
    'trendTo' => null,
])

<div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                Trend Penggantian Komponen
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Jumlah penggantian berdasarkan bulan terbit LPPB.
            </p>
        </div>

        <form method="GET" action="{{ route('durability.index') }}" class="flex flex-wrap items-center gap-2">
            {{-- Pertahankan filter utama --}}
            @if(request('tahun'))
                <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            @endif

            @if(request('produk_id'))
                <input type="hidden" name="produk_id" value="{{ request('produk_id') }}">
            @endif

            <select name="trend_from"
                class="rounded-xl border-gray-200 bg-white text-xs text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Dari Bulan</option>
                @foreach($availableTrendMonths as $month)
                    <option value="{{ $month['value'] }}" @selected($trendFrom === $month['value'])>
                        {{ $month['label'] }}
                    </option>
                @endforeach
            </select>

            <span class="text-xs text-gray-400">s/d</span>

            <select name="trend_to"
                class="rounded-xl border-gray-200 bg-white text-xs text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Sampai Bulan</option>
                @foreach($availableTrendMonths as $month)
                    <option value="{{ $month['value'] }}" @selected($trendTo === $month['value'])>
                        {{ $month['label'] }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Terapkan
            </button>

            @if($trendFrom || $trendTo)
                <a href="{{ route('durability.index', request()->except(['trend_from', 'trend_to', 'page'])) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if($trendFrom || $trendTo)
        <div class="mt-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
            Menampilkan trend

            @if($trendFrom)
                dari <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $trendFrom)->translatedFormat('M Y') }}</strong>
            @endif

            @if($trendTo)
                sampai <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $trendTo)->translatedFormat('M Y') }}</strong>
            @endif
        </div>
    @endif

    <div class="mt-5 h-[420px]">
        <canvas id="trendPenggantianChart"></canvas>
    </div>
</div>
