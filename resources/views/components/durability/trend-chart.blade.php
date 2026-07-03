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
                Frekuensi penggantian berdasarkan tanggal kerusakan komponen
            </p>
        </div>

        <form method="GET" action="{{ route('durability.index') }}" class="flex flex-wrap items-center gap-2">
            {{-- Pertahankan filter utama --}}
            @if(request('tahun'))
                <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            @endif
            @php
                $selectedProdukIds = request()->input('produk_id', []);
                $selectedProdukIds = is_array($selectedProdukIds)
                    ? array_filter($selectedProdukIds)
                    : array_filter([$selectedProdukIds]);
            @endphp

            @foreach($selectedProdukIds as $produkId)
                <input type="hidden" name="produk_id[]" value="{{ $produkId }}">
            @endforeach
            @if(request('proyek_id'))
                <input type="hidden" name="proyek_id" value="{{ request('proyek_id') }}">
            @endif

            {{-- Dropdown: Dari Bulan --}}
            <div class="relative"
                x-data="{
                    open: false,
                    selected: '{{ $trendFrom ?? '' }}',
                    label: '{{ $trendFrom ? \Carbon\Carbon::createFromFormat('Y-m', $trendFrom)->translatedFormat('M Y') : 'Dari Bulan' }}'
                }"
                @click.outside="open = false">
                <input type="hidden" name="trend_from" :value="selected">
                <button type="button" @click="open = !open"
                    class="flex w-36 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                    <span x-text="label" class="truncate"></span>
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="absolute left-0 z-50 mt-1 w-40 rounded-2xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="max-h-52 overflow-y-auto">
                        <button type="button" @click="selected = ''; label = 'Dari Bulan'; open = false"
                            class="w-full px-4 py-2 text-left text-xs hover:bg-gray-50 dark:hover:bg-gray-700"
                            :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                            Dari Bulan
                        </button>
                        @foreach($availableTrendMonths as $month)
                            <button type="button"
                                @click="selected = '{{ $month['value'] }}'; label = '{{ $month['label'] }}'; open = false"
                                class="w-full px-4 py-2 text-left text-xs hover:bg-gray-50 dark:hover:bg-gray-700"
                                :class="selected === '{{ $month['value'] }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                {{ $month['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <span class="text-xs text-gray-400">s/d</span>

            {{-- Dropdown: Sampai Bulan --}}
            <div class="relative"
                x-data="{
                    open: false,
                    selected: '{{ $trendTo ?? '' }}',
                    label: '{{ $trendTo ? \Carbon\Carbon::createFromFormat('Y-m', $trendTo)->translatedFormat('M Y') : 'Sampai Bulan' }}'
                }"
                @click.outside="open = false">
                <input type="hidden" name="trend_to" :value="selected">
                <button type="button" @click="open = !open"
                    class="flex w-36 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                    <span x-text="label" class="truncate"></span>
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="absolute left-0 z-50 mt-1 w-40 rounded-2xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="max-h-52 overflow-y-auto">
                        <button type="button" @click="selected = ''; label = 'Sampai Bulan'; open = false"
                            class="w-full px-4 py-2 text-left text-xs hover:bg-gray-50 dark:hover:bg-gray-700"
                            :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                            Sampai Bulan
                        </button>
                        @foreach($availableTrendMonths as $month)
                            <button type="button"
                                @click="selected = '{{ $month['value'] }}'; label = '{{ $month['label'] }}'; open = false"
                                class="w-full px-4 py-2 text-left text-xs hover:bg-gray-50 dark:hover:bg-gray-700"
                                :class="selected === '{{ $month['value'] }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                {{ $month['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

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
