@extends('layouts.app')

@section('header')
    Detail Project
@endsection

@section('content')
<div class="py-6 max-w-5xl mx-auto space-y-4">

    {{-- Back Link --}}
    <a href="{{ route('projects.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar project
    </a>

    {{-- Info Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        {{ $project->nama_proyek }}
                    </h2>
                    <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2.5 py-0.5 rounded-lg">
                        {{ $project->kode_proyek }}
                    </span>
                </div>
            </div>

            <a href="{{ route('projects.edit', $project) }}"
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
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Nomor Proyek</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-100">
                    {{ $project->nomor_proyek }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Kode Proyek</p>
                <p class="mt-1 font-mono text-sm text-gray-700 dark:text-gray-200">
                    {{ $project->kode_proyek }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total NCR</p>
                <p class="mt-1">
                    <span class="text-sm font-semibold
                        {{ $project->ncrs_count > 0
                            ? 'text-orange-600 dark:text-orange-400'
                            : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $project->ncrs_count }} NCR
                    </span>
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Nama Proyek</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
                    {{ $project->nama_proyek }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Dibuat</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                    {{ \Carbon\Carbon::parse($project->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>

        </div>
    </div>

    {{-- NCR Terkait --}}
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
                    {{ $project->ncrs->count() }} NCR pada project ini
                </p>
            </div>
        </div>

        {{-- NCR Table --}}
        @if($project->ncrs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-400 dark:text-gray-500">
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nomor NCR</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Temuan</th>
                        <th class="px-4 py-3 text-left">PIC</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($project->ncrs as $index => $ncr)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                        <td class="px-4 py-3 text-gray-400 dark:text-gray-500">
                            {{ $index + 1 }}
                        </td>

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
                            {{ $ncr->status_temuan }}
                        </td>

                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $ncr->penanggungJawab->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @php $status = $ncr->keterangan; @endphp
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                @if($status == 'open')    bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-300
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
                                Lihat
                            </a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
            Belum ada NCR pada project ini
        </div>
        @endif

    </div>

</div>
@endsection
