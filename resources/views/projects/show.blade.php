@extends('layouts.app')

@section('header')
    Detail Project
@endsection

@section('content')
<div class="py-6 max-w-5xl mx-auto space-y-4">

    <a href="{{ route('projects.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar project
    </a>

    {{-- Info Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $project->nama_proyek }}</h2>
                    <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-lg">
                        {{ $project->kode_proyek }}
                    </span>
                </div>
            </div>
            <a href="{{ route('projects.edit', $project) }}"
                class="inline-flex items-center gap-2 bg-amber-50 hover:bg-amber-100 text-amber-600 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Nomor Proyek</p>
                <p class="mt-1 text-sm font-semibold text-gray-800">{{ $project->nomor_proyek }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Kode Proyek</p>
                <p class="mt-1 font-mono text-sm text-gray-700">{{ $project->kode_proyek }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total NCR</p>
                <p class="mt-1">
                    <span class="inline-flex items-center gap-1 text-sm font-semibold
                        {{ $project->ncrs_count > 0 ? 'text-orange-600' : 'text-gray-400' }}">
                        {{ $project->ncrs_count }} NCR
                    </span>
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Nama Proyek</p>
                <p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ $project->nama_proyek }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Dibuat</p>
                <p class="mt-1 text-sm text-gray-700">
                    {{ \Carbon\Carbon::parse($project->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- NCR Terkait --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">NCR Terkait</h3>
                <p class="text-xs text-gray-400">{{ $project->ncrs->count() }} NCR pada project ini</p>
            </div>
        </div>

        @if($project->ncrs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nomor NCR</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Masuk</th>
                        <th class="px-4 py-3 text-left font-semibold">Status Temuan</th>
                        <th class="px-4 py-3 text-left font-semibold">Penanggung Jawab</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($project->ncrs as $index => $ncr)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
                                class="font-medium text-indigo-600 hover:underline transition-colors">
                                {{ $ncr->nomor_ncr }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($ncr->tgl_masuk)->format('d-m-Y') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $ncr->status_temuan }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $ncr->penanggungJawab->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($ncr->keterangan == 'open')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-600 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>OPEN
                                </span>
                            @elseif($ncr->keterangan == 'proses')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>PROSES
                                </span>
                            @elseif($ncr->keterangan == 'close')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 text-green-600 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>CLOSED
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
                                class="inline-flex items-center gap-1 text-xs font-medium bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat NCR
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-4 py-10 text-center">
            <div class="flex flex-col items-center gap-2 text-gray-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm font-medium text-gray-400">Belum ada NCR pada project ini</p>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
