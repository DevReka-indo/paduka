@extends('layouts.app')

@section('header')
    Lokasi Penggantian Komponen
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
    .dark .flatpickr-calendar { background: #1f2937; border-color: #374151; color: #f9fafb; }
    .dark .flatpickr-day { color: #d1d5db; }
    .dark .flatpickr-day:hover { background: #374151; border-color: #374151; }
    .dark .flatpickr-day.selected,
    .dark .flatpickr-day.selected:hover { background: #2563eb; border-color: #2563eb; color: #fff; }
    .dark .flatpickr-day.today { border-color: #2563eb; color: #60a5fa; }
    .dark .flatpickr-day.today:hover { background: #1e3a8a; }
    .dark .flatpickr-day.flatpickr-disabled,
    .dark .flatpickr-day.flatpickr-disabled:hover { color: #4b5563; }
    .dark .flatpickr-months .flatpickr-month,
    .dark .flatpickr-weekdays,
    .dark span.flatpickr-weekday { background: #1f2937; color: #9ca3af; fill: #9ca3af; }
    .dark .flatpickr-current-month input.cur-year,
    .dark .flatpickr-current-month .flatpickr-monthDropdown-months { color: #f9fafb; background: #1f2937; }
    .dark .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month { background: #1f2937; color: #f9fafb; }
    .dark .flatpickr-prev-month, .dark .flatpickr-next-month { color: #9ca3af; fill: #9ca3af; }
    .dark .flatpickr-prev-month:hover, .dark .flatpickr-next-month:hover { color: #f9fafb; fill: #f9fafb; }
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
                        Lokasi
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Lokasi Penggantian Komponen
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Data seluruh komponen yang mengalami penggantian berdasarkan lokasi.
                    </p>
                </div>

                <form method="GET" action="{{ route('durability.lokasi') }}" class="flex flex-wrap items-end gap-3">

                    {{-- Dari Tanggal --}}
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Dari
                        </label>
                        <div class="relative">
                            <input type="text" name="date_from" id="lokasi_date_from" value="{{ $dateFrom }}"
                                placeholder="Pilih tanggal..."
                                readonly
                                class="w-40 cursor-pointer rounded-xl border border-gray-200 bg-white py-2 pl-3 pr-9 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
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
                            <input type="text" name="date_to" id="lokasi_date_to" value="{{ $dateTo }}"
                                placeholder="Pilih tanggal..."
                                readonly
                                class="w-40 cursor-pointer rounded-xl border border-gray-200 bg-white py-2 pl-3 pr-9 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        <i class="fa-solid fa-filter mr-2"></i>
                        Filter
                    </button>

                    <a href="{{ route('durability.lokasi') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        @if($periodeLabel)
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                Menampilkan data lokasi penggantian komponen periode <strong>{{ $periodeLabel }}</strong>
            </div>
        @endif

        {{-- Top 5 Lokasi --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Top 5 Lokasi Penggantian Komponen
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Lokasi dengan total penggantian komponen tertinggi.
                    </p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Jumlah Penggantian (Kali)
                </div>
            </div>

            <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 md:grid-cols-5 md:divide-x md:divide-y-0">
                @forelse($topLokasi as $index => $item)
                    @php
                        $percentage = min(100, ((int) $item->total_penggantian / $maxTopLokasiValue) * 100);
                        $badgeClass = match($index) {
                            0 => 'bg-yellow-400 text-white',
                            1 => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200',
                            2 => 'bg-orange-500 text-white',
                            default => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                        };
                    @endphp
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold {{ $badgeClass }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $item->nama_lokasi ?? '-' }}
                                </p>
                                <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($item->total_penggantian ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                            <div class="h-full rounded-full bg-blue-600" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-5 p-10 text-center text-gray-400">
                        Belum ada data lokasi.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

            {{-- Tabel Lokasi --}}
            <div class="xl:col-span-5 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Lokasi Penggantian
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Ringkasan total penggantian berdasarkan lokasi.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-4 text-left font-bold">Lokasi</th>
                                <th class="px-5 py-4 text-center font-bold">Penggantian Komponen</th>
                                <th class="px-5 py-4 text-right font-bold">Penggantian Produk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($lokasiSummary as $item)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-5 py-4 font-semibold text-blue-600 dark:text-blue-400">
                                        {{ $item->nama_lokasi ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            {{ number_format($item->total_penggantian ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($item->total_produk ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-12 text-center text-gray-400">
                                        Belum ada data lokasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lokasiSummary->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
                        {{ $lokasiSummary->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            {{-- Chart Trainset --}}
            <div class="xl:col-span-7 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">
                        Total Penggantian Berdasarkan Trainset
                    </h2>

                    <form method="GET" action="{{ route('durability.lokasi') }}" class="mt-5 flex flex-wrap items-end gap-3">
                        @if($dateFrom)
                            <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                        @endif
                        @if($dateTo)
                            <input type="hidden" name="date_to" value="{{ $dateTo }}">
                        @endif

                        {{-- Dropdown: Produk (multi-select) --}}
                        <div>
                            @php
                                $produkIdArr = is_array($produkId)
                                    ? array_map('strval', $produkId)
                                    : array_filter([(string) $produkId]);
                            @endphp

                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" @click="open = !open"
                                    class="flex w-44 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
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
                                    class="absolute left-0 z-50 mt-1 w-64 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">

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
                                    <div class="max-h-52 overflow-y-auto p-1">
                                        @foreach($produkList as $produk)
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

                        {{-- Dropdown: Komponen --}}
                        <div class="relative"
                            x-data="{
                                open: false,
                                selected: '{{ $komponenId ?? '' }}',
                                label: '{{ $komponenId ? $komponenList->firstWhere('id', (int) $komponenId)?->nama_komponen : 'Semua Komponen' }}'
                            }"
                            @click.outside="open = false">
                            <input type="hidden" name="komponen_id" :value="selected">
                            <button type="button" @click="open = !open"
                                class="flex w-48 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
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
                                class="absolute left-0 z-50 mt-1 w-64 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                    <input type="text" placeholder="Cari komponen..."
                                        class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                        x-on:input="
                                            const q = $event.target.value.toLowerCase();
                                            $el.closest('.absolute').querySelectorAll('[data-opt]').forEach(el => {
                                                el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
                                            });
                                        ">
                                </div>
                                <div class="max-h-52 overflow-y-auto py-1">
                                    <button type="button" @click="selected = ''; label = 'Semua Komponen'; open = false"
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                        Semua Komponen
                                    </button>
                                    @foreach($komponenList as $komponen)
                                        <button type="button" data-opt data-name="{{ strtolower($komponen->nama_komponen) }}"
                                            @click="selected = '{{ $komponen->id }}'; label = '{{ $komponen->nama_komponen }}'; open = false"
                                            class="w-full truncate px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                            :class="selected === '{{ $komponen->id }}' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                            {{ $komponen->nama_komponen }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Dropdown: Lokasi --}}
                        <div class="relative"
                            x-data="{
                                open: false,
                                selected: '{{ $lokasiId ?? '' }}',
                                label: '{{ $lokasiId ? $lokasiList->firstWhere('id', (int) $lokasiId)?->nama_lokasi : 'Semua Lokasi' }}'
                            }"
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
                                    <button type="button" @click="selected = ''; label = 'Semua Lokasi'; open = false"
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="selected === '' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                        Semua Lokasi
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

                        {{-- Dropdown: Sort --}}
                        <div class="relative"
                            x-data="{
                                open: false,
                                selected: '{{ $sort ?? 'desc' }}',
                                label: '{{ ($sort ?? 'desc') === 'asc' ? 'Terkecil ke Terbesar' : 'Terbesar ke Terkecil' }}'
                            }"
                            @click.outside="open = false">
                            <input type="hidden" name="sort" :value="selected">
                            <button type="button" @click="open = !open"
                                class="flex w-48 items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
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
                                class="absolute left-0 z-50 mt-1 w-48 rounded-2xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <button type="button" @click="selected = 'desc'; label = 'Terbesar ke Terkecil'; open = false"
                                    class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                    :class="selected === 'desc' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                    Terbesar ke Terkecil
                                </button>
                                <button type="button" @click="selected = 'asc'; label = 'Terkecil ke Terbesar'; open = false"
                                    class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                                    :class="selected === 'asc' ? 'font-semibold text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'">
                                    Terkecil ke Terbesar
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                Terapkan
                            </button>
                            <a href="{{ route('durability.lokasi', array_filter(['date_from' => $dateFrom, 'date_to' => $dateTo])) }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                Reset Chart
                            </a>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <x-durability.lokasi-trainset-chart
                        :chart-trainset-labels="$chartTrainsetLabels"
                        :chart-trainset-values="$chartTrainsetValues"
                    />
                </div>
            </div>

        </div>

        <div class="flex items-start gap-2 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
            <i class="fa-solid fa-circle-info mt-0.5"></i>
            <p>Data menampilkan total penggantian komponen berdasarkan lokasi selama periode yang dipilih.</p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let pickerFrom, pickerTo;

    pickerTo = flatpickr('#lokasi_date_to', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        allowInput: false,
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            if (pickerFrom) pickerFrom.set('maxDate', dateStr);
        }
    });

    pickerFrom = flatpickr('#lokasi_date_from', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        allowInput: false,
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            if (pickerTo) pickerTo.set('minDate', dateStr);
        }
    });
});
</script>
@endpush
