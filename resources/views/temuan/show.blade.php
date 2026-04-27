@extends('layouts.app')

@section('header')
    Detail Lokasi Temuan
@endsection

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

    {{-- Back Link --}}
    <a href="{{ route('temuan.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar temuan
    </a>

    {{-- Info Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        {{ $lokasi->nomor_temuan }}
                    </h2>
                    <span class="inline-flex items-center text-xs font-semibold
                        bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-300 px-2.5 py-0.5 rounded-full">
                        {{ $lokasi->status_temuan }}
                    </span>
                </div>
            </div>

            <a href="{{ route('temuan.edit', $lokasi) }}"
                class="inline-flex items-center gap-2 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 text-amber-600 dark:text-amber-300 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>

        </div>

        {{-- Card Body --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Nomor Temuan</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-100">
                    {{ $lokasi->nomor_temuan }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Lokasi Temuan</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                    {{ $lokasi->status_temuan }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total NCR</p>
                <p class="mt-1">
                    <span class="text-sm font-semibold
                        {{ $lokasi->ncrs_count > 0
                            ? 'text-orange-600 dark:text-orange-400'
                            : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $lokasi->ncrs_count }} NCR
                    </span>
                </p>
            </div>

            <div class="md:col-span-3">
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Kategori Temuan</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
                    {{ $lokasi->detail_temuan ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Dibuat</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                    {{ \Carbon\Carbon::parse($lokasi->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Terakhir Diperbarui</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                    {{ \Carbon\Carbon::parse($lokasi->updated_at)->translatedFormat('d F Y') }}
                </p>
            </div>

        </div>
    </div>

    {{-- NCR Terkait --}}
    @if($lokasi->ncrs->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Section Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-orange-50 dark:bg-orange-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">NCR Terkait</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $lokasi->ncrs->count() }} NCR menggunakan temuan ini
                </p>
            </div>
        </div>

        {{-- NCR Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-400 dark:text-gray-500">
                        <th class="px-4 py-3 text-left">Nomor NCR</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Proyek</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($lokasi->ncrs as $ncr)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
                                class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                {{ $ncr->nomor_ncr }}
                            </a>
                        </td>

                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($ncr->tgl_masuk)->format('d-m-Y') }}
                        </td>

                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $ncr->project->nama_proyek ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @php $status = $ncr->keterangan; @endphp
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                @if($status == 'open')   bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-300
                                @elseif($status == 'proses') bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-300
                                @elseif($status == 'close')  bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500
                                @endif">
                                {{ strtoupper($status ?? '-') }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
                                class="px-2 py-1 text-xs bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-300 rounded-lg">
                                Lihat NCR
                            </a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @endif

</div>
@endsection
