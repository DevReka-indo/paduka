@extends('layouts.app')

@section('header')
    Resume Durability Product
@endsection

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        Durability Product
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Dashboard Durability Product
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Monitoring durability component dan frekuensi penggantian berdasarkan tanggal kerusakan komponen.
                    </p>
                </div>

                <div class="flex flex-col gap-3 lg:items-end overflow-visible">
                    {{-- <a href="{{ route('durability.import.form') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                        <i class="fa-solid fa-upload mr-2"></i>
                        Import Data
                    </a> --}}

                    <form method="GET" action="{{ route('durability.index') }}" id="main-filter-form" class="flex flex-col gap-3 sm:flex-row sm:items-center">

                        {{-- Dropdown: Proyek --}}
                        <div class="relative" x-data="{ open: false, selected: '{{ request('proyek_id') }}', label: '{{ request('proyek_id') ?: 'Semua Proyek' }}' }" @click.outside="open = false">
                            <input type="hidden" name="proyek_id" :value="selected">
                            <button type="button" @click="open = !open"
                                class="flex w-44 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                <span x-text="label" class="truncate"></span>
                                <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="absolute left-0 z-50 mt-1 w-52 rounded-2xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <button type="button" @click="selected = ''; label = 'Semua Proyek'; open = false"
                                    class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                    :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                    Semua Proyek
                                </button>
                                @foreach ($proyekList as $proyek)
                                    <button type="button"
                                        @click="selected = '{{ $proyek->nama_proyek }}'; label = '{{ $proyek->nama_proyek }}'; open = false"
                                        class="w-full truncate px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="selected === '{{ $proyek->nama_proyek }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                        {{ $proyek->nama_proyek }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dropdown: Tahun --}}
                        <div class="relative" x-data="{ open: false, selected: '{{ request('tahun') }}', label: '{{ request('tahun') ?: 'Semua Tahun' }}' }" @click.outside="open = false">
                            <input type="hidden" name="tahun" :value="selected">
                            <button type="button" @click="open = !open"
                                class="flex w-36 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                <span x-text="label"></span>
                                <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="absolute left-0 z-50 mt-1 w-36 rounded-2xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <button type="button" @click="selected = ''; label = 'Semua Tahun'; open = false"
                                    class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                    :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                    Semua Tahun
                                </button>
                                @foreach ($tahunList as $tahun)
                                    <button type="button"
                                        @click="selected = '{{ $tahun }}'; label = '{{ $tahun }}'; open = false"
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="selected === '{{ $tahun }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                        {{ $tahun }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dropdown: Produk (multi-select) --}}
                        <div>
                            @php
                                $produkIdArr = request()->input('produk_id', []);
                                $produkIdArr = is_array($produkIdArr)
                                    ? array_map('strval', $produkIdArr)
                                    : array_filter([(string) $produkIdArr]);
                            @endphp

                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" @click="open = !open"
                                    class="flex w-52 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                    <span class="truncate">
                                        @if(empty($produkIdArr))
                                            Semua Produk
                                        @elseif(count($produkIdArr) === 1)
                                            {{ $produkList->firstWhere('id', (int) $produkIdArr[0])?->nama_produk ?? 'Semua Produk' }}
                                        @else
                                            {{ count($produkIdArr) }} Produk dipilih
                                        @endif
                                    </span>

                                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200"
                                        :class="open ? 'rotate-180' : ''"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
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
                                    class="absolute right-0 z-50 mt-1 w-64 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">

                                    {{-- Search --}}
                                    <div class="border-b border-gray-100 p-2 dark:border-gray-700">
                                        <input type="text"
                                            placeholder="Cari produk..."
                                            class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                            x-on:input="
                                                const q = $event.target.value.toLowerCase();
                                                $el.closest('.absolute').querySelectorAll('[data-produk-item]').forEach(el => {
                                                    el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
                                                });
                                            ">
                                    </div>

                                    {{-- List --}}
                                    <div class="max-h-56 overflow-y-auto p-1">
                                        @foreach ($produkList as $produk)
                                            <label data-produk-item
                                                data-name="{{ strtolower($produk->nama_produk) }}"
                                                class="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700">

                                                <input type="checkbox"
                                                    name="produk_id[]"
                                                    value="{{ $produk->id }}"
                                                    {{ in_array((string) $produk->id, $produkIdArr) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                                <span class="truncate">
                                                    {{ $produk->nama_produk }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    {{-- Footer --}}
                                    <div class="flex items-center justify-between border-t border-gray-100 p-2 dark:border-gray-700">
                                        <button type="button"
                                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                            onclick="this.closest('.absolute').querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false)">
                                            Reset
                                        </button>

                                        <button type="submit"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                            Terapkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Filter & Reset --}}
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="fa-solid fa-filter mr-2"></i>
                            Filter
                        </button>

                        <a href="{{ route('durability.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Reset
                        </a>
                    </form>

                </div>
            </div>
        </div>

        @if(request('tahun') || request('produk_id') || request('proyek_id'))
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                Menampilkan data durability

                @if(request('proyek_id'))
                    proyek <strong>{{ request('proyek_id') }}</strong>
                @endif

                @if(request('tahun'))
                    tahun <strong>{{ request('tahun') }}</strong>
                @endif

                @if(request('produk_id'))
                    @php
                        $selectedProdukIds = request()->input('produk_id', []);
                        $selectedProdukIds = is_array($selectedProdukIds)
                            ? $selectedProdukIds
                            : array_filter([$selectedProdukIds]);

                        $selectedProdukNames = $produkList
                            ->whereIn('id', $selectedProdukIds)
                            ->pluck('nama_produk')
                            ->join(', ');
                    @endphp

                    @if($selectedProdukNames)
                        untuk produk <strong>{{ $selectedProdukNames }}</strong>
                    @endif
                @endif
            </div>
        @endif

        <x-durability.summary-cards
            :total-penggantian="$totalPenggantian"
            :komponen-durability-tertinggi="$komponenDurabilityTertinggi"
            :komponen-durability-terendah="$komponenDurabilityTerendah"
            :komponen-penggantian-terbanyak="$komponenPenggantianTerbanyak"
        />

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

            <x-durability.trend-chart
                :available-trend-months="$availableTrendMonths"
                :trend-from="$trendFrom"
                :trend-to="$trendTo"
            />

            <x-durability.top-komponen-penggantian-chart />

            <x-durability.top-trainset-table
                :top-trainset-penggantian="$topTrainsetPenggantian"
                :produk-list="$produkList"
                :selected-trainset-produk="$selectedTrainsetProduk"
            />

            <x-durability.top-komponen-durability-chart />

        </div>

        <div class="flex justify-end">
            <a href="{{ route('durability.tabel-detail', request()->query()) }}"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                <i class="fa-solid fa-table mr-2"></i>
                Lihat Tabel Detail
            </a>
        </div>
    </div>
</div>

<x-durability.chart-script
    :trend-labels="$trendLabels"
    :trend-values="$trendValues"
    :top-komponen-penggantian="$topKomponenPenggantian"
    :top-komponen-durability="$topKomponenDurability"
/>
@endsection
