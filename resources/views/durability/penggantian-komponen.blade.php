@extends('layouts.app')

@section('header')
    Database Komponen
@endsection

@section('content_width', 'w-full')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        font-family: inherit;
    }

    .dark .flatpickr-calendar {
        background: #1f2937;
        border-color: #374151;
        color: #f9fafb;
    }

    .dark .flatpickr-day {
        color: #d1d5db;
    }

    .dark .flatpickr-day:hover {
        background: #374151;
        border-color: #374151;
    }

    .dark .flatpickr-day.selected,
    .dark .flatpickr-day.selected:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .dark .flatpickr-day.inRange {
        background: #1e3a8a;
        border-color: #1e3a8a;
        color: #bfdbfe;
    }

    .dark .flatpickr-day.today {
        border-color: #2563eb;
        color: #60a5fa;
    }

    .dark .flatpickr-day.today:hover {
        background: #1e3a8a;
    }

    .dark .flatpickr-day.flatpickr-disabled,
    .dark .flatpickr-day.flatpickr-disabled:hover {
        color: #4b5563;
    }

    .dark .flatpickr-months .flatpickr-month,
    .dark .flatpickr-weekdays,
    .dark span.flatpickr-weekday {
        background: #1f2937;
        color: #9ca3af;
        fill: #9ca3af;
    }

    .dark .flatpickr-current-month input.cur-year,
    .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
        color: #f9fafb;
        background: #1f2937;
    }

    .dark .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
        background: #1f2937;
        color: #f9fafb;
    }

    .dark .flatpickr-prev-month,
    .dark .flatpickr-next-month {
        color: #9ca3af;
        fill: #9ca3af;
    }

    .dark .flatpickr-prev-month:hover,
    .dark .flatpickr-next-month:hover {
        color: #f9fafb;
        fill: #f9fafb;
    }

    .dark .flatpickr-time {
        background: #1f2937;
        border-color: #374151;
        color: #f9fafb;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        Database Komponen
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Total Penggantian Komponen
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Data seluruh komponen yang mengalami penggantian berdasarkan periode dan filter yang dipilih.
                    </p>
                </div>

                <form method="GET" action="{{ route('durability.penggantian-komponen') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:flex xl:flex-wrap xl:items-end">

                {{-- Dari Tanggal --}}
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Dari
                    </label>
                    <div class="relative">
                        <input type="text" name="date_from" id="date_from" value="{{ $dateFrom }}"
                            placeholder="Pilih tanggal..."
                            readonly
                            class="w-full cursor-pointer rounded-xl border border-gray-200 bg-white py-2 pl-3 pr-9 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                            <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Sampai Tanggal --}}
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Sampai
                    </label>
                    <div class="relative">
                        <input type="text" name="date_to" id="date_to" value="{{ $dateTo }}"
                            placeholder="Pilih tanggal..."
                            readonly
                            class="w-full cursor-pointer rounded-xl border border-gray-200 bg-white py-2 pl-3 pr-9 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                            <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Dropdown: Produk (multi-select) --}}
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Produk
                    </label>
                    @php
                        $produkIdArr = is_array($produkId) ? array_map('strval', $produkId) : [];
                    @endphp
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                            class="flex w-48 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                            <span class="truncate">
                                @if(empty($produkIdArr))
                                    Semua Produk
                                @elseif(count($produkIdArr) === 1)
                                    {{ $produkList->firstWhere('id', (int) $produkIdArr[0])?->nama_produk ?? 'Semua Produk' }}
                                @else
                                    {{ count($produkIdArr) }} Produk dipilih
                                @endif
                            </span>
                            <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            class="absolute left-0 z-50 mt-1 w-64 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">

                            {{-- Search --}}
                            <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                <input type="text" placeholder="Cari produk..."
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
                                @foreach($produkList as $produk)
                                    <label data-produk-item data-name="{{ strtolower($produk->nama_produk) }}"
                                        class="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700">
                                        <input type="checkbox"
                                            name="produk_id[]"
                                            value="{{ $produk->id }}"
                                            {{ in_array((string) $produk->id, $produkIdArr) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        {{ $produk->nama_produk }}
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

                    {{-- Dropdown: Trainset --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Trainset
                        </label>
                        @php
                            $selectedTrainsetObj = $trainsetId ? $trainsetList->firstWhere('id', (int) $trainsetId) : null;
                            $trainsetLabel = $selectedTrainsetObj
                                ? ('TS-' . $selectedTrainsetObj->nomor_trainset . ($selectedTrainsetObj->tipe_car ? ' / ' . $selectedTrainsetObj->tipe_car : ''))
                                : 'Semua';
                        @endphp
                        <div class="relative"
                            x-data="{ open: false, selected: '{{ $trainsetId ?? '' }}', label: '{{ $trainsetLabel }}' }"
                            @click.outside="open = false">
                            <input type="hidden" name="trainset_id" :value="selected">
                            <button type="button" @click="open = !open"
                                class="flex w-44 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                <span x-text="label" class="truncate"></span>
                                <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                class="absolute left-0 z-50 mt-1 w-52 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                    <input type="text" placeholder="Cari trainset..."
                                        class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                        x-on:input="
                                            const q = $event.target.value.toLowerCase();
                                            $el.closest('.absolute').querySelectorAll('[data-opt]').forEach(el => {
                                                el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
                                            });
                                        ">
                                </div>
                                <div class="max-h-52 overflow-y-auto py-1">
                                    <button type="button" @click="selected = ''; label = 'Semua'; open = false"
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                        Semua
                                    </button>
                                    @foreach($trainsetList as $trainset)
                                        @php
                                            $tsLabel = ($trainset->nomor_trainset ? 'TS-' . $trainset->nomor_trainset : '-')
                                                . ($trainset->tipe_car ? ' / ' . $trainset->tipe_car : '');
                                        @endphp
                                        <button type="button" data-opt data-name="{{ strtolower($tsLabel) }}"
                                            @click="selected = '{{ $trainset->id }}'; label = '{{ $tsLabel }}'; open = false"
                                            class="w-full truncate px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                            :class="selected === '{{ $trainset->id }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                            {{ $tsLabel }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dropdown: Lokasi --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Lokasi
                        </label>
                        @php
                            $selectedLokasiObj = $lokasiId ? $lokasiList->firstWhere('id', (int) $lokasiId) : null;
                            $lokasiLabel = $selectedLokasiObj?->nama_lokasi ?? 'Semua';
                        @endphp
                        <div class="relative"
                            x-data="{ open: false, selected: '{{ $lokasiId ?? '' }}', label: '{{ $lokasiLabel }}' }"
                            @click.outside="open = false">
                            <input type="hidden" name="lokasi_id" :value="selected">
                            <button type="button" @click="open = !open"
                                class="flex w-44 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                <span x-text="label" class="truncate"></span>
                                <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                class="absolute left-0 z-50 mt-1 w-48 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <div class="max-h-52 overflow-y-auto py-1">
                                    <button type="button" @click="selected = ''; label = 'Semua'; open = false"
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                        Semua
                                    </button>
                                    @foreach($lokasiList as $lokasi)
                                        <button type="button" data-opt data-name="{{ strtolower($lokasi->nama_lokasi) }}"
                                            @click="selected = '{{ $lokasi->id }}'; label = '{{ $lokasi->nama_lokasi }}'; open = false"
                                            class="w-full truncate px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                            :class="selected === '{{ $lokasi->id }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                            {{ $lokasi->nama_lokasi }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="fa-solid fa-filter mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('durability.penggantian-komponen') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Reset
                        </a>
                    </div>

                </form>

            </div>
        </div>

        @if($periodeLabel || $produkId || $trainsetId || $lokasiId)
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                Menampilkan data penggantian komponen

                @if($periodeLabel)
                    periode <strong>{{ $periodeLabel }}</strong>
                @endif

                @if(!empty($produkId))
                    @php
                        $selectedProdukNames = $produkList
                            ->whereIn('id', $produkId)
                            ->pluck('nama_produk')
                            ->join(', ');
                    @endphp
                    @if($selectedProdukNames)
                        produk <strong>{{ $selectedProdukNames }}</strong>
                    @endif
                @endif

                @if($trainsetId)
                    @php $selectedTrainset = $trainsetList->firstWhere('id', (int) $trainsetId); @endphp
                    @if($selectedTrainset)
                        trainset
                        <strong>
                            {{ $selectedTrainset->nomor_trainset ? 'TS-' . $selectedTrainset->nomor_trainset : '-' }}
                            {{ $selectedTrainset->tipe_car ? ' / ' . $selectedTrainset->tipe_car : '' }}
                        </strong>
                    @endif
                @endif

                @if($lokasiId)
                    @php $selectedLokasi = $lokasiList->firstWhere('id', (int) $lokasiId); @endphp
                    @if($selectedLokasi)
                        lokasi <strong>{{ $selectedLokasi->nama_lokasi }}</strong>
                    @endif
                @endif
            </div>
        @endif

        {{-- Chart --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Total Penggantian Setiap Komponen
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Jumlah penggantian seluruh komponen pada periode yang dipilih.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('durability.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="h-[520px]">
                    <canvas id="chartPenggantianKomponen"></canvas>
                </div>

                <div class="mt-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <strong>{{ number_format($chartFromItem, 0, ',', '.') }}</strong>
                        -
                        <strong>{{ number_format($chartToItem, 0, ',', '.') }}</strong>
                        dari
                        <strong>{{ number_format($chartTotalItems, 0, ',', '.') }}</strong>
                        komponen
                    </p>

                    @if($chartTotalPages > 1)
                        <div class="flex flex-wrap items-center gap-2">
                            @php
                                $baseChartQuery = request()->except(['chart_page', 'page']);
                                $prevChartPage = max($chartPage - 1, 1);
                                $nextChartPage = min($chartPage + 1, $chartTotalPages);

                                $startPage = max($chartPage - 2, 1);
                                $endPage = min($chartPage + 2, $chartTotalPages);
                            @endphp

                            <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $prevChartPage])) }}"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700
                                {{ $chartPage <= 1 ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>

                            @if($startPage > 1)
                                <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => 1])) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    1
                                </a>

                                @if($startPage > 2)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                            @endif

                            @for($page = $startPage; $page <= $endPage; $page++)
                                <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $page])) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border text-sm font-semibold shadow-sm transition
                                    {{ $chartPage == $page
                                        ? 'border-blue-600 bg-blue-600 text-white'
                                        : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    {{ $page }}
                                </a>
                            @endfor

                            @if($endPage < $chartTotalPages)
                                @if($endPage < $chartTotalPages - 1)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif

                                <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $chartTotalPages])) }}"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    {{ $chartTotalPages }}
                                </a>
                            @endif

                            <a href="{{ route('durability.penggantian-komponen', array_merge($baseChartQuery, ['chart_page' => $nextChartPage])) }}"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700
                                {{ $chartPage >= $chartTotalPages ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-5 flex items-start gap-2 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p>
                        Grafik menampilkan jumlah total penggantian untuk setiap komponen selama periode yang dipilih.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Flatpickr ────────────────────────────────────────────────
    const pickerTo = flatpickr('#date_to', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        allowInput: false,
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            pickerFrom.set('maxDate', dateStr);
        }
    });

    const pickerFrom = flatpickr('#date_from', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        allowInput: false,
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            pickerTo.set('minDate', dateStr);
        }
    });

    // ─── Chart ────────────────────────────────────────────────────
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    const labels = @json($chartLabels);
    const fullLabels = @json($chartLabelsFull);
    const values = @json($chartValues);

    let chartPenggantianKomponen = null;
    let rebuildTimer = null;

    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            gridColor: isDark ? 'rgba(148, 163, 184, 0.16)' : 'rgba(148, 163, 184, 0.22)',
            tickColor: isDark ? '#94a3b8' : '#64748b',
            labelColor: isDark ? '#f8fafc' : '#0f172a',
            tooltipBg: isDark ? 'rgba(15, 23, 42, 0.96)' : 'rgba(255, 255, 255, 0.98)',
            tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
            tooltipBody: isDark ? '#cbd5e1' : '#334155',
        };
    }

    function destroyChart() {
        if (chartPenggantianKomponen) {
            chartPenggantianKomponen.destroy();
            chartPenggantianKomponen = null;
        }
    }

    function createChart(animated = true) {
        const canvas = document.getElementById('chartPenggantianKomponen');
        if (!canvas || typeof Chart === 'undefined') return;

        const theme = getChartTheme();
        destroyChart();

        chartPenggantianKomponen = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penggantian',
                    data: values,
                    backgroundColor: 'rgba(37, 99, 235, 0.95)',
                    borderRadius: 6,
                    maxBarThickness: 30,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: animated ? { duration: 450, easing: 'easeOutCubic' } : false,
                layout: { padding: { top: 24, right: 12, bottom: 0, left: 0 } },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: theme.tickColor, boxWidth: 10, boxHeight: 10, padding: 18 }
                    },
                    datalabels: {
                        anchor: 'end', align: 'top', offset: 4, clamp: true,
                        color: theme.labelColor,
                        font: { size: 10, weight: 'bold' },
                        formatter: value => Number(value).toLocaleString('id-ID')
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            title: context => fullLabels[context[0].dataIndex] ?? context[0].label,
                            label: context => 'Jumlah: ' + Number(context.raw).toLocaleString('id-ID') + ' Kali'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...values, 0) * 1.18,
                        title: { display: true, text: 'Jumlah Penggantian', color: theme.tickColor, font: { weight: 'bold' } },
                        grid: { color: theme.gridColor },
                        border: { display: false },
                        ticks: { color: theme.tickColor, precision: 0 }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: theme.tickColor, maxRotation: 50, minRotation: 50, autoSkip: false, font: { size: 10 } }
                    }
                }
            }
        });
    }

    createChart(true);

    window.rebuildPenggantianKomponenChart = function () {
        clearTimeout(rebuildTimer);
        rebuildTimer = setTimeout(() => createChart(false), 120);
    };

    new MutationObserver(mutations => {
        if (mutations.some(m => m.type === 'attributes' && m.attributeName === 'class')) {
            window.rebuildPenggantianKomponenChart();
        }
    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

});
</script>
@endpush
