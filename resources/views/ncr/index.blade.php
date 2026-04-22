@extends('layouts.app')

@section('header')
    Registrasi NCR
@endsection

@section('content')
    <div class="py-6 max-w-7xl mx-auto space-y-4">

        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-400 dark:text-gray-500">
                Daftar seluruh laporan NCR
            </p>

            <div class="flex items-center gap-2">
                {{-- Tombol Generate Report --}}
                <button type="button"
                    onclick="openReportModal()"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 16v-8m0 8l-3-3m3 3l3-3M4 20h16" />
                    </svg>
                    Generate Report
                </button>

                {{-- Tombol Tambah NCR --}}
                <a href="{{ route('ncr.create') }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah NCR
                </a>
            </div>
        </div>

        {{-- Modal Generate Report --}}
        <div id="reportModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-lg rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Generate Report NCR</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih rentang tanggal dan status laporan</p>
                    </div>
                    <button type="button" onclick="closeReportModal()"
                        class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('ncr.export-report') }}" method="GET" class="px-6 py-5 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tgl_awal" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Mulai
                            </label>
                            <input type="date" name="tgl_awal" id="tgl_awal"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40">
                        </div>

                        <div>
                            <label for="tgl_akhir" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Akhir
                            </label>
                            <input type="date" name="tgl_akhir" id="tgl_akhir"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40">
                        </div>
                    </div>

                    <div>
                        <label for="ket_ncr" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status NCR
                        </label>
                        <select name="ket_ncr" id="ket_ncr"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/40">
                            <option value="">Semua Status</option>
                            <option value="open">Open</option>
                            <option value="close">Close</option>
                            <option value="process">Process</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" onclick="closeReportModal()"
                            class="inline-flex items-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Batal
                        </button>

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 16v-8m0 8l-3-3m3 3l3-3M4 20h16" />
                            </svg>
                            Download Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('pesan'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/40 text-green-700 dark:text-green-300 text-sm px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('pesan') }}
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-600 dark:hover:text-green-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Filter Bar --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <form method="GET" action="{{ route('ncr.index') }}" class="space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                    {{-- Search --}}
                    <div class="sm:col-span-2 xl:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Cari</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nomor NCR, proyek, atau lokasi temuan..."
                                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-800/50 focus:border-indigo-400 dark:focus:border-indigo-500" />
                        </div>
                    </div>

                    {{-- Tanggal Dari --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Tanggal Masuk Dari</label>
                        <input type="date" name="tgl_dari" value="{{ request('tgl_dari') }}"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-800/50 focus:border-indigo-400 dark:focus:border-indigo-500" />
                    </div>

                    {{-- Tanggal Sampai --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Tanggal Masuk Sampai</label>
                        <input type="date" name="tgl_sampai" value="{{ request('tgl_sampai') }}"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-800/50 focus:border-indigo-400 dark:focus:border-indigo-500" />
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:focus:ring-indigo-800/50 focus:border-indigo-400 dark:focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="open" @selected(request('status') == 'open')>Open</option>
                            <option value="process" @selected(request('status') == 'process')>Process</option>
                            <option value="close" @selected(request('status') == 'close')>Closed</option>
                        </select>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                    <div class="text-xs text-gray-400 dark:text-gray-500">
                        Gunakan filter untuk mempersempit data NCR.
                    </div>

                    <div class="flex items-center gap-2 sm:justify-end">
                        @if (request()->hasAny(['search', 'status', 'tgl_dari', 'tgl_sampai']))
                            <a href="{{ route('ncr.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-colors">
                                Reset Filter
                            </a>
                        @endif

                        <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                {{-- Indikator filter aktif --}}
                @if (request()->hasAny(['search', 'status', 'tgl_dari', 'tgl_sampai']))
                    <div class="pt-1 flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400 dark:text-gray-500">Filter aktif:</span>

                        @if (request('search'))
                            <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 px-2.5 py-1 rounded-full">
                                Cari: "{{ request('search') }}"
                            </span>
                        @endif

                        @if (request('status'))
                            <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 px-2.5 py-1 rounded-full">
                                Status: {{ ucfirst(request('status')) }}
                            </span>
                        @endif

                        @if (request('tgl_dari'))
                            <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 px-2.5 py-1 rounded-full">
                                Dari: {{ \Carbon\Carbon::parse(request('tgl_dari'))->format('d-m-Y') }}
                            </span>
                        @endif

                        @if (request('tgl_sampai'))
                            <span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 px-2.5 py-1 rounded-full">
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
                            <th class="px-4 py-3 text-left font-semibold">Revision</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Proses</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal Masuk</th>
                            <th class="px-4 py-3 text-left font-semibold">Proyek</th>
                            <th class="px-4 py-3 text-left font-semibold">Lokasi Temuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Penanggung Jawab</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">

                        @forelse($ncr as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">

                                <td class="px-4 py-3 text-gray-400 dark:text-gray-500">
                                    {{ $ncr->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-3">
                                    <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                        class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline transition-colors">
                                        {{ $item->nomor_ncr }}
                                    </a>
                                </td>

                                <td class="px-4 py-3">
                                    @if($item->latestRevision && $item->latestRevision->revision)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 px-2.5 py-1 rounded-full">
                                            {{ $item->latestRevision->revision }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-[220px] whitespace-normal break-words">
                                    {{ $item->nama_proses ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($item->tgl_masuk)->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-[220px] whitespace-normal break-words">
                                    {{ $item->project->nama_proyek ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-[220px] whitespace-normal break-words">
                                    {{ $item->status_temuan ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $item->penanggungJawab->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($item->keterangan == 'open')
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>OPEN
                                        </span>
                                    @elseif($item->keterangan == 'process')
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>PROCESS
                                        </span>
                                    @elseif($item->keterangan == 'close' || $item->keterangan == 'closed')
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>CLOSED
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 px-2.5 py-1 rounded-full">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">

                                        {{-- Detail --}}
                                        <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                            class="inline-flex items-center gap-1 text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 px-2.5 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if ((int) $item->user_id === (int) auth()->id() && strtolower($item->keterangan ?? '') === 'open')
                                            <a href="{{ route('ncr.edit', $item->nomor_ncr) }}"
                                                class="inline-flex items-center gap-1 text-xs font-medium bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 px-2.5 py-1.5 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('ncr.destroy', $item->nomor_ncr) }}" method="POST"
                                                class="inline" x-data=""
                                                x-on:submit.prevent="if(confirm('Yakin ingin menghapus NCR {{ $item->nomor_ncr }}?')) $el.submit()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 text-xs font-medium bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 px-2.5 py-1.5 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-300 dark:text-gray-600">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-400 dark:text-gray-500">
                                            {{ request()->hasAny(['search', 'status', 'tgl_dari', 'tgl_sampai']) ? 'Tidak ada NCR yang sesuai filter' : 'Belum ada data NCR' }}
                                        </p>
                                        @if (!request()->hasAny(['search', 'status', 'tgl_dari', 'tgl_sampai']))
                                            <a href="{{ route('ncr.create') }}"
                                                class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">
                                                Tambah NCR pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Footer: info total + pagination --}}
            @if ($ncr->total() > 0)
                <div
                    class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Menampilkan {{ $ncr->firstItem() }}–{{ $ncr->lastItem() }} dari {{ $ncr->total() }} data
                    </p>
                    {{ $ncr->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection

@push('scripts')
<script>
    function openReportModal() {
        const modal = document.getElementById('reportModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeReportModal() {
        const modal = document.getElementById('reportModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReportModal();
        }
    });

    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReportModal();
        }
    });
</script>
@endpush
