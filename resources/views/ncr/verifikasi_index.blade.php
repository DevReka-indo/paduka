@extends('layouts.app')

@section('header')
    Verifikasi NCR
@endsection

@section('content')
<div class="py-6 max-w-7xl mx-auto space-y-6">

    {{-- Header Bar --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
            Verifikasi NCR
        </h2>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
            Daftar NCR yang perlu diverifikasi
        </p>
    </div>

    {{-- Alert --}}
    @if(session('pesan'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center justify-between gap-3
                    bg-green-50 dark:bg-green-900/20
                    border border-green-200 dark:border-green-800/40
                    text-green-700 dark:text-green-400
                    text-sm px-4 py-3 rounded-2xl">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('pesan') }}
            </div>
            <button @click="show = false"
                    class="text-green-400 dark:text-green-500 hover:text-green-600 dark:hover:text-green-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if(session('belum'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center justify-between gap-3
                    bg-amber-50 dark:bg-amber-900/20
                    border border-amber-200 dark:border-amber-800/40
                    text-amber-700 dark:text-amber-400
                    text-sm px-4 py-3 rounded-2xl">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                </svg>
                {{ session('belum') }}
            </div>
            <button @click="show = false"
                    class="text-amber-400 dark:text-amber-500 hover:text-amber-600 dark:hover:text-amber-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <form method="GET" action="{{ route('ncr.verifikasi.index') }}" class="space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                {{-- Search --}}
                <div class="sm:col-span-2 xl:col-span-2">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Cari</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute left-3 top-1/2 -translate-y-1/2"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nomor NCR, proyek, proses, status, atau unit..."
                            class="w-full pl-9 pr-4 py-2.5 text-sm
                                   bg-white dark:bg-gray-900
                                   text-gray-700 dark:text-gray-200
                                   border border-gray-200 dark:border-gray-600
                                   rounded-xl
                                   placeholder:text-gray-400 dark:placeholder:text-gray-500
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-500
                                   focus:border-indigo-400 dark:focus:border-indigo-500"
                        />
                    </div>
                </div>

                {{-- Tanggal Dari --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Tanggal Masuk Dari</label>
                    <input
                        type="date"
                        name="tgl_dari"
                        value="{{ request('tgl_dari') }}"
                        class="w-full px-3 py-2.5 text-sm
                               bg-white dark:bg-gray-900
                               text-gray-700 dark:text-gray-200
                               border border-gray-200 dark:border-gray-600
                               rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-500
                               focus:border-indigo-400 dark:focus:border-indigo-500"
                    />
                </div>

                {{-- Tanggal Sampai --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Tanggal Masuk Sampai</label>
                    <input
                        type="date"
                        name="tgl_sampai"
                        value="{{ request('tgl_sampai') }}"
                        class="w-full px-3 py-2.5 text-sm
                               bg-white dark:bg-gray-900
                               text-gray-700 dark:text-gray-200
                               border border-gray-200 dark:border-gray-600
                               rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-500
                               focus:border-indigo-400 dark:focus:border-indigo-500"
                    />
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    Gunakan filter untuk mempersempit data verifikasi NCR.
                </div>

                <div class="flex items-center gap-2 sm:justify-end">
                    @if(request()->hasAny(['search', 'tgl_dari', 'tgl_sampai']))
                        <a
                            href="{{ route('ncr.verifikasi.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium
                                   text-gray-600 dark:text-gray-300
                                   bg-gray-100 dark:bg-gray-700
                                   hover:bg-gray-200 dark:hover:bg-gray-600
                                   rounded-xl transition-colors"
                        >
                            Reset Filter
                        </a>
                    @endif

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5
                               bg-indigo-600 hover:bg-indigo-700
                               text-white text-sm font-semibold
                               rounded-xl transition-colors shadow-sm"
                    >
                        Terapkan Filter
                    </button>
                </div>
            </div>

            {{-- Indikator filter aktif --}}
            @if(request()->hasAny(['search', 'tgl_dari', 'tgl_sampai']))
                <div class="pt-1 flex flex-wrap items-center gap-2">
                    <span class="text-xs text-gray-400 dark:text-gray-500">Filter aktif:</span>

                    @if(request('search'))
                        <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-full">
                            Cari: "{{ request('search') }}"
                        </span>
                    @endif

                    @if(request('tgl_dari'))
                        <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-full">
                            Dari: {{ \Carbon\Carbon::parse(request('tgl_dari'))->format('d-m-Y') }}
                        </span>
                    @endif

                    @if(request('tgl_sampai'))
                        <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-full">
                            Sampai: {{ \Carbon\Carbon::parse(request('tgl_sampai'))->format('d-m-Y') }}
                        </span>
                    @endif

                    <span class="text-xs text-gray-400 dark:text-gray-500">— {{ $ncr->total() }} hasil ditemukan</span>
                </div>
            @endif

        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nomor NCR</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Masuk</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Proses</th>
                        <th class="px-4 py-3 text-left font-semibold">Proyek</th>
                        <th class="px-4 py-3 text-left font-semibold">Lokasi Temuan</th>
                        <th class="px-4 py-3 text-left font-semibold">Unit Tujuan</th>
                        <th class="px-4 py-3 text-left font-semibold">Penanggung Jawab</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">

                    @forelse($ncr as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                        <td class="px-4 py-3 text-gray-400 dark:text-gray-500">
                            {{ $ncr->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline transition-colors">
                                {{ $item->nomor_ncr }}
                            </a>
                        </td>

                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                            {{ $item->tgl_masuk ? \Carbon\Carbon::parse($item->tgl_masuk)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->nama_proses ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-[180px]">
                            <p class="truncate" title="{{ $item->project->nama_proyek ?? '-' }}">
                                {{ $item->project->nama_proyek ?? '-' }}
                            </p>
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->status_temuan ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->unitKerja->nama_unit ?? $item->unit_tujuan ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->penanggungJawab->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @php $status = strtolower($item->keterangan ?? ''); @endphp

                            @if($status == 'open')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>OPEN
                                </span>
                            @elseif($status == 'process' || $status == 'proses')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>PROCESS
                                </span>
                            @elseif(in_array($status, ['close', 'closed']))
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>CLOSED
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-2.5 py-1 rounded-full">
                                    {{ strtoupper($item->keterangan ?? '-') }}
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">

                                {{-- Detail --}}
                                <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                    class="inline-flex items-center gap-1 text-xs font-medium
                                           bg-indigo-50 dark:bg-indigo-900/30
                                           text-indigo-600 dark:text-indigo-400
                                           hover:bg-indigo-100 dark:hover:bg-indigo-900/50
                                           px-2.5 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                @if((int) auth()->id() === (int) $item->user_id)
                                    <a href="{{ route('ncr.verifikasi.form', $item->nomor_ncr) }}"
                                        class="inline-flex items-center gap-1 text-xs font-medium
                                               bg-emerald-50 dark:bg-emerald-900/30
                                               text-emerald-600 dark:text-emerald-400
                                               hover:bg-emerald-100 dark:hover:bg-emerald-900/50
                                               px-2.5 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </a>
                                @endif

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-400 dark:text-gray-500">
                                    {{ request()->hasAny(['search','tgl_dari','tgl_sampai']) ? 'Tidak ada NCR yang sesuai filter' : 'Belum ada NCR yang perlu diverifikasi' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Footer: info total + pagination --}}
        @if($ncr->total() > 0)
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Menampilkan {{ $ncr->firstItem() }}–{{ $ncr->lastItem() }} dari {{ $ncr->total() }} data
            </p>
            <div class="[&_svg]:w-5 [&_svg]:h-5 [&_span]:text-sm">
                {{ $ncr->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
