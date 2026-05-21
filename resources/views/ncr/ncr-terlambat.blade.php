@extends('layouts.app')

@section('header')
    NCR Terlambat
@endsection

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                NCR Terlambat
            </h2>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
                Daftar NCR yang belum selesai dan sudah melewati tanggal target{{ !$isAdmin ? ' — NCR Anda' : '' }}
            </p>
        </div>

        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg transition-colors">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-50 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Daftar NCR Terlambat
                    </h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Belum close & tanggal target sudah lewat
                    </p>
                </div>
            </div>

            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-1 rounded-full">
                Total: {{ $ncrTerlambat->total() }}
            </span>
        </div>

        @if($ncrTerlambat->isEmpty())
            <div class="px-4 py-10 text-center">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <p class="text-sm font-medium text-gray-400 dark:text-gray-500">
                        Tidak ada NCR terlambat
                    </p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-4 py-3 text-left font-semibold">Nomor NCR</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Proses</th>
                            <th class="px-4 py-3 text-left font-semibold">Proyek</th>
                            <th class="px-4 py-3 text-left font-semibold">Lokasi Temuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Penanggung Jawab</th>
                            <th class="px-4 py-3 text-left font-semibold">Target</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterlambatan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach($ncrTerlambat as $item)
                            <tr class="bg-red-50/50 dark:bg-red-900/10 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                        class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $item->nomor_ncr }}
                                    </a>
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[220px] whitespace-normal break-words">
                                    {{ $item->nama_proses ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[220px] whitespace-normal break-words">
                                    {{ $item->project->nama_proyek ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[220px] whitespace-normal break-words">
                                    {{ $item->status_temuan ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $item->penanggungJawab->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($item->tgl_target)->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-full">
                                        Lewat {{ abs($item->sisa_hari) }} hari
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    @php $st = strtolower($item->keterangan ?? ''); @endphp

                                    @if($st == 'open')
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>OPEN
                                        </span>
                                    @elseif($st == 'process')
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>PROCESS
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-2.5 py-1 rounded-full">
                                            {{ strtoupper($item->keterangan ?? '-') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <a href="{{ route('ncr.show', $item->nomor_ncr) }}"
                                        class="inline-flex items-center gap-1 text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $ncrTerlambat->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
