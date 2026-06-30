@extends('layouts.app')

@section('header')
    Lokasi Penggantian Komponen
@endsection

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
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

                <form method="GET" action="{{ route('durability.lokasi') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">

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
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                Total Penggantian Berdasarkan Trainset
                            </h2>
                            {{-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Total penggantian berdasarkan trainset.
                            </p> --}}
                        </div>
                    </div>

                    <form method="GET" action="{{ route('durability.lokasi') }}" class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        @if($dateFrom)
                            <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                        @endif

                        @if($dateTo)
                            <input type="hidden" name="date_to" value="{{ $dateTo }}">
                        @endif

                        <select name="produk_id"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Produk</option>
                            @foreach($produkList as $produk)
                                <option value="{{ $produk->id }}" @selected((string) $produkId === (string) $produk->id)>
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>

                        <select name="komponen_id"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Komponen</option>
                            @foreach($komponenList as $komponen)
                                <option value="{{ $komponen->id }}" @selected((string) $komponenId === (string) $komponen->id)>
                                    {{ $komponen->nama_komponen }}
                                </option>
                            @endforeach
                        </select>

                        <select name="lokasi_id"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Lokasi</option>
                            @foreach($lokasiList as $lokasi)
                                <option value="{{ $lokasi->id }}" @selected((string) $lokasiId === (string) $lokasi->id)>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>

                        <select name="sort"
                            onchange="this.form.submit()"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="desc" @selected($sort === 'desc')>Terbesar ke Terkecil</option>
                            <option value="asc" @selected($sort === 'asc')>Terkecil ke Terbesar</option>
                        </select>

                        <div class="sm:col-span-2 xl:col-span-4 flex justify-end gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                Terapkan
                            </button>

                            <a href="{{ route('durability.lokasi', array_filter([
                                'date_from' => $dateFrom,
                                'date_to' => $dateTo,
                            ])) }}"
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
            <p>
                Data menampilkan total penggantian komponen berdasarkan lokasi selama periode yang dipilih.
            </p>
        </div>
    </div>
</div>
@endsection
