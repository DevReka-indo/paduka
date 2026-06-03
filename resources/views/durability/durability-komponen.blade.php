@extends('layouts.app')

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-emerald-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-400">
                        Durability Komponen
                    </p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Durability Komponen
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Rata-rata durability seluruh komponen berdasarkan data pergantian.
                    </p>
                </div>

                <form method="GET" action="{{ route('durability.durability-komponen') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-6">
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Dari
                        </label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Sampai
                        </label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Produk
                        </label>
                        <select name="produk_id"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($produkList as $produk)
                                <option value="{{ $produk->id }}" @selected((string) $produkId === (string) $produk->id)>
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Trainset
                        </label>
                        <select name="trainset_id"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($trainsetList as $trainset)
                                <option value="{{ $trainset->id }}" @selected((string) $trainsetId === (string) $trainset->id)>
                                    {{ $trainset->nomor_trainset ? 'TS-' . $trainset->nomor_trainset : '-' }}
                                    {{ $trainset->tipe_car ? ' / ' . $trainset->tipe_car : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Lokasi
                        </label>
                        <select name="lokasi_id"
                            class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($lokasiList as $lokasi)
                                <option value="{{ $lokasi->id }}" @selected((string) $lokasiId === (string) $lokasi->id)>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <i class="fa-solid fa-filter mr-2"></i>
                            Filter
                        </button>

                        <a href="{{ route('durability.durability-komponen') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($periodeLabel || $produkId || $trainsetId || $lokasiId)
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                Menampilkan data durability komponen

                @if($periodeLabel)
                    periode <strong>{{ $periodeLabel }}</strong>
                @endif

                @if($produkId)
                    @php $selectedProduk = $produkList->firstWhere('id', (int) $produkId); @endphp
                    @if($selectedProduk)
                        produk <strong>{{ $selectedProduk->nama_produk }}</strong>
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

        <x-durability.durability-komponen-chart
            :chart-labels="$chartLabels"
            :chart-labels-full="$chartLabelsFull"
            :chart-values="$chartValues"
            :chart-colors="$chartColors"
        />

    </div>
</div>
@endsection
