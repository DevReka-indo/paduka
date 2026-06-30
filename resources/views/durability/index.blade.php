@extends('layouts.app')

@section('header')
    Resume Durability Product
@endsection

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
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

                <div class="flex flex-col gap-3 lg:items-end">
                    {{-- <a href="{{ route('durability.import.form') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                        <i class="fa-solid fa-upload mr-2"></i>
                        Import Data
                    </a> --}}

                    <form method="GET" action="{{ route('durability.index') }}" class="flex flex-col gap-3 sm:flex-row">

                        <select name="proyek_id"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Proyek</option>
                            @foreach ($proyekList as $proyek)
                                <option value="{{ $proyek->nama_proyek }}"
                                        @selected(request('proyek_id') == $proyek->nama_proyek)>
                                    {{ $proyek->nama_proyek }}
                                </option>
                            @endforeach
                        </select>

                        <select name="tahun"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>

                        <select name="produk_id"
                            class="rounded-xl border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Produk</option>
                            @foreach ($produkList as $produk)
                                <option value="{{ $produk->id }}" @selected(request('produk_id') == $produk->id)>
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>

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

        @if(request('tahun') || request('produk_id'))
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
                Menampilkan data durability

                @if(request('tahun'))
                    tahun <strong>{{ request('tahun') }}</strong>
                @endif

                @if(request('produk_id'))
                    @php
                        $selectedProdukName = $produkList->firstWhere('id', request('produk_id'))?->nama_produk;
                    @endphp

                    @if($selectedProdukName)
                        untuk produk <strong>{{ $selectedProdukName }}</strong>
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
