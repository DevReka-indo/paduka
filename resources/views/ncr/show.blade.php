@extends('layouts.app')

@section('header')
    Detail NCR — {{ $ncr->nomor_ncr }}
@endsection

@section('content')
    <div class="py-6 max-w-4xl mx-auto space-y-4">

        <a href="{{ route('ncr.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar NCR
        </a>

        {{-- Alert Sukses --}}
        @if (session('pesan'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('pesan') }}
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('belum'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-center justify-between gap-3 bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                    </svg>
                    {{ session('belum') }}
                </div>
                <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Header Card --}}
        @php $status = strtolower($ncr->keterangan ?? ''); @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    {{-- <div>
                        <p class="text-xs text-gray-400 mb-0.5">Nomor NCR</p>
                        <h2 class="text-lg font-bold text-indigo-600">{{ $ncr->nomor_ncr }}</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $ncr->tgl_masuk ? \Carbon\Carbon::parse($ncr->tgl_masuk)->translatedFormat('l, d F Y') : '-' }}
                        </p>
                    </div> --}}
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Nomor NCR</p>
                        <h2 class="text-lg font-bold text-indigo-600">{{ $ncr->nomor_ncr }}</h2>

                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $ncr->tgl_masuk ? \Carbon\Carbon::parse($ncr->tgl_masuk)->translatedFormat('l, d F Y') : '-' }}
                        </p>

                        @if (in_array($status, ['close', 'closed']) && !empty($ncr->hasil_verifikasi))
                            <div class="mt-2 flex flex-wrap items-center gap-2">

                                @if ($ncr->hasil_verifikasi === 'Efektif')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold bg-green-50 text-green-600 px-3 py-1.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Verifikasi: EFEKTIF
                                    </span>
                                @elseif ($ncr->hasil_verifikasi === 'Tidak Efektif')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-50 text-red-600 px-3 py-1.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Verifikasi: TIDAK EFEKTIF
                                    </span>

                                    @if (!empty($ncr->ncr_baru))
                                        <a href="{{ route('ncr.show', $ncr->ncr_baru) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12H9m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                            NCR Baru: {{ $ncr->ncr_baru }}
                                        </a>
                                    @endif
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full">
                                        Verifikasi: {{ $ncr->hasil_verifikasi }}
                                    </span>
                                @endif

                            </div>
                        @endif
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="flex-shrink-0">
                    @if ($status == 'open')
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-50 text-red-600 px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>OPEN
                        </span>
                    @elseif($status == 'process')
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-50 text-amber-600 px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>PROCESS
                        </span>
                    @elseif(in_array($status, ['close', 'closed']))
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold bg-green-50 text-green-600 px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>CLOSED
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold bg-gray-100 text-gray-400 px-3 py-1.5 rounded-full">-</span>
                    @endif
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex flex-wrap items-center gap-2">

                {{-- Tomnol Export PDF selalu muncul, karena bisa berguna untuk berbagai keperluan (arsip, laporan, dll) --}}
                <a href="{{ route('ncr.export.pdf', $ncr->nomor_ncr) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </a>

                @if (
                    // $ncr->keterangan === 'close' &&
                    //     $ncr->hasil_verifikasi === 'Tidak Efektif' &&
                    (int) $ncr->user_id === (int) auth()->id() && empty($ncr->ncr_baru))
                    <a href="{{ route('ncr.create', ['from_ncr' => $ncr->nomor_ncr]) }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium bg-red-600 text-white hover:bg-red-700 px-3 py-1.5 rounded-lg transition-colors">

                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>

                        Open NCR Baru
                    </a>
                @endif

                {{-- button back to open --}}
                @if (
                    in_array($ncr->keterangan, ['process', 'close']) &&
                    (int) $ncr->user_id === (int) auth()->id()
                )
                    <form action="{{ route('ncr.open', $ncr->nomor_ncr) }}" method="POST" class="inline-block">
                        @csrf

                        <button type="submit"
                            onclick="return confirm('Yakin ingin mengembalikan NCR ke OPEN?')"
                            class="inline-flex items-center gap-1.5 text-xs font-medium bg-amber-500 text-white hover:bg-amber-600 px-3 py-1.5 rounded-lg transition-colors">

                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h11M3 6h11M3 14h7m4 0l4-4m0 0l-4-4m4 4H10" />
                            </svg>

                            Back to Open
                        </button>
                    </form>
                @endif

                @if (isset($canTanggapi) && $canTanggapi && $status === 'open')
                    <a href="{{ route('ncr.tanggapi', $ncr->nomor_ncr) }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Tanggapi
                    </a>
                @endif

                @if (auth()->id() === $ncr->user_id && strtolower($ncr->keterangan ?? '') === 'process')
                    <a href="{{ route('ncr.verifikasi.form', $ncr->nomor_ncr) }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium bg-emerald-600 text-white hover:bg-emerald-700 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Verifikasi
                    </a>
                @endif

                @if ((int) $ncr->user_id === (int) auth()->id() && strtolower($ncr->keterangan ?? '') === 'open')
                    <a href="{{ route('ncr.edit', $ncr->nomor_ncr) }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium bg-amber-50 text-amber-600 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>

                    <form action="{{ route('ncr.destroy', $ncr->nomor_ncr) }}" method="POST" class="inline"
                        x-data=""
                        x-on:submit.prevent="if(confirm('Yakin ingin menghapus NCR {{ $ncr->nomor_ncr }}?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif

            </div>
        </div>

        {{-- Section 1: Informasi Dasar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Informasi Dasar</h3>
                    <p class="text-xs text-gray-400">Nomor NCR, tanggal terbit, dan inspektor</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                @include('ncr.partials.field', ['label' => 'Nomor NCR', 'value' => $ncr->nomor_ncr])
                @include('ncr.partials.field', [
                    'label' => 'Tanggal Masuk',
                    'value' => $ncr->tgl_masuk
                        ? \Carbon\Carbon::parse($ncr->tgl_masuk)->translatedFormat('l, d F Y')
                        : null,
                ])
                <div class="md:col-span-2">
                    @include('ncr.partials.field', [
                        'label' => 'Nama Inspektor',
                        'value' => $ncr->user->name ?? null,
                    ])
                </div>
            </div>
        </div>

        {{-- Section 2: Detail Temuan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Detail Temuan</h3>
                    <p class="text-xs text-gray-400">Proyek, lokasi, dan uraian ketidaksesuaian</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                @include('ncr.partials.field', [
                    'label' => 'Nama Produk / Proses',
                    'value' => $ncr->nama_proses,
                ])
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Nama / Kode Proyek</p>
                    <p class="text-sm text-gray-700">
                        @if ($ncr->project)
                            <span
                                class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded mr-1">{{ $ncr->project->kode_proyek }}</span>
                            {{ $ncr->project->nama_proyek }}
                        @else
                            <span class="text-gray-400 italic">—</span>
                        @endif
                    </p>
                </div>
                @include('ncr.partials.field', [
                    'label' => 'Acuan Pemeriksaan',
                    'value' => $ncr->acuan_periksa,
                ])
                @include('ncr.partials.field', [
                    'label' => 'Surat Jalan (S/N)',
                    'value' => $ncr->surat_jalan,
                ])
                <div class="md:col-span-2">
                    @include('ncr.partials.field', [
                        'label' => 'Lokasi Temuan',
                        'value' => $ncr->status_temuan,
                    ])
                </div>
                <div class="md:col-span-2">
                    @include('ncr.partials.field', [
                        'label' => 'Uraian Ketidaksesuaian',
                        'value' => $ncr->uraian,
                        'multiline' => true,
                    ])
                </div>
            </div>
        </div>

        {{-- Section 3: Penanganan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Penanganan</h3>
                    <p class="text-xs text-gray-400">PIC, target penyelesaian, dan disposisi</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    @include('ncr.partials.field', [
                        'label' => 'Person In Charge (PIC)',
                        'value' => $ncr->penanggungJawab->name ?? null,
                    ])
                </div>

                {{-- Target dengan indikator sisa hari --}}
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Target Penyelesaian</p>
                    @if ($ncr->tgl_target)
                        @php
                            $sisaHari = \Carbon\Carbon::now()
                                ->startOfDay()
                                ->diffInDays(\Carbon\Carbon::parse($ncr->tgl_target)->startOfDay(), false);
                        @endphp
                        <p class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($ncr->tgl_target)->translatedFormat('d F Y') }}
                        </p>
                        @if (!in_array($status, ['close', 'closed']))
                            @if ($sisaHari < 0)
                                <span
                                    class="inline-flex items-center gap-1 mt-1.5 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01" />
                                    </svg>
                                    Lewat {{ abs($sisaHari) }} hari
                                </span>
                            @elseif($sisaHari == 0)
                                <span
                                    class="inline-flex items-center gap-1 mt-1.5 text-xs font-semibold bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full">
                                    Hari ini!
                                </span>
                            @elseif($sisaHari <= 7)
                                <span
                                    class="inline-flex items-center gap-1 mt-1.5 text-xs font-semibold bg-yellow-50 text-yellow-600 px-2.5 py-1 rounded-full">
                                    {{ $sisaHari }} hari lagi
                                </span>
                            @endif
                        @endif
                    @else
                        <p class="text-sm text-gray-400 italic">—</p>
                    @endif
                </div>

                @include('ncr.partials.field', [
                    'label' => 'Disposisi Inspektor',
                    'value' => $ncr->disposisi_inspektor,
                ])

                <div class="md:col-span-2">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Unit Yang Dituju</p>
                    @if ($ncr->unitKerja)
                        <div class="flex items-center gap-2">
                            @if ($ncr->unitKerja->kode_unit)
                                <span
                                    class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $ncr->unitKerja->kode_unit }}</span>
                            @endif
                            <span class="text-sm text-gray-700">{{ $ncr->unitKerja->nama_unit }}</span>
                        </div>
                    @elseif($ncr->unit_tujuan)
                        <p class="text-sm text-gray-700">{{ $ncr->unit_tujuan }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">—</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Section 4: Dokumen Pendukung --}}
        {{-- up_file disimpan via Storage::disk('public')->store('ncr') --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Dokumen Pendukung</h3>
                    <p class="text-xs text-gray-400">Dokumen pendukung yang terlampir pada NCR</p>
                </div>
            </div>
            <div class="p-6">

                {{-- Deskripsi Dokumen --}}
                @if (!empty($ncr->doc_pendukung))
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">
                            Deskripsi Dokumen
                        </p>
                        <p class="text-sm text-gray-700">
                            {{ $ncr->doc_pendukung }}
                        </p>
                    </div>
                @endif

                {{-- Gambar --}}
                @if (!empty($ncr->up_file))
                    <img src="{{ Storage::url($ncr->up_file) }}" alt="Dokumen Pendukung"
                        class="w-full max-h-[28rem] object-contain rounded-xl border border-gray-200 bg-gray-50" />
                @else
                    <div
                        class="h-32 bg-gray-50 rounded-xl border border-dashed border-gray-200 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <p class="text-sm text-gray-400">Belum ada dokumen pendukung</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- Section 5: Hasil Tanggapan Unit --}}
        {{-- Muncul saat status process atau close/closed --}}
        @if (in_array($status, ['process', 'close', 'closed']))
            <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-100 bg-emerald-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-emerald-800">Hasil Tanggapan Unit</h3>
                            <p class="text-xs text-emerald-600">Tindak lanjut dari unit yang dituju</p>
                        </div>
                    </div>
                    @if (isset($canTanggapi) && $canTanggapi && $status === 'process')
                        <a href="{{ route('ncr.tanggapi', $ncr->nomor_ncr) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium bg-white text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Tanggapan
                        </a>
                    @endif

                    @if (auth()->id() === $ncr->user_id && strtolower($ncr->keterangan ?? '') === 'process')
                        <a href="{{ route('ncr.verifikasi.form', $ncr->nomor_ncr) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium bg-white text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Verifikasi
                        </a>
                    @endif
                </div>

                <div class="p-6 space-y-6">

                    {{-- Akar Masalah + Kategori --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @include('ncr.partials.field', [
                            'label' => 'Akar Masalah',
                            'value' => $ncr->akar_masalah,
                        ])
                        @include('ncr.partials.field', [
                            'label' => 'Kategori Ketidaksesuaian',
                            'value' => $ncr->kategori_masalah,
                        ])
                    </div>

                    <div class="border-t border-gray-100"></div>

                    {{-- Uraian panjang --}}
                    <div class="space-y-4">
                        @include('ncr.partials.field', [
                            'label' => 'Uraian Akar Masalah',
                            'value' => $ncr->uraian_masalah,
                            'multiline' => true,
                        ])
                        @include('ncr.partials.field', [
                            'label' => 'Tindakan Perbaikan',
                            'value' => $ncr->uraian_perbaikan,
                            'multiline' => true,
                        ])
                        @include('ncr.partials.field', [
                            'label' => 'Tindakan Pencegahan',
                            'value' => $ncr->uraian_pencegahan,
                            'multiline' => true,
                        ])
                    </div>

                    <div class="border-t border-gray-100"></div>

                    {{-- Disposisi & Manager --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @include('ncr.partials.field', [
                            'label' => 'Disposisi Unit',
                            'value' => $ncr->disposisi_unit,
                        ])
                        @include('ncr.partials.field', [
                            'label' => 'Senior Manager / Manager',
                            'value' => $ncr->senior_manager,
                        ])
                        @include('ncr.partials.field', [
                            'label' => 'Tanggal Tanggapan',
                            'value' => $ncr->tgl_managers
                                ? \Carbon\Carbon::parse($ncr->tgl_managers)->translatedFormat('l, d F Y')
                                : null,
                        ])
                        @include('ncr.partials.field', [
                            'label' => 'Deskripsi Dokumen Lampiran',
                            'value' => $ncr->doc_lampiran,
                        ])
                    </div>

                    {{-- Lampiran Tanggapan --}}
                    {{-- up_filee masih disimpan via move() ke public/gambar/ncr --}}
                    <div class="border-t border-gray-100 pt-5">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Dokumen Lampiran
                            Tanggapan</p>
                        @if (!empty($ncr->up_filee) && $ncr->up_filee !== 'gambar_default.png')
                            <img src="{{ asset('storage/' . $ncr->up_filee) }}" alt="Dokumen Lampiran Tanggapan"
                                class="w-full max-h-[28rem] object-contain rounded-xl border border-gray-200 bg-gray-50" />
                        @else
                            <div
                                class="h-28 bg-gray-50 rounded-xl border border-dashed border-gray-200 flex items-center justify-center">
                                <p class="text-sm text-gray-400">Belum ada dokumen lampiran tanggapan</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @endif

        @if (!empty($ncr->hasil_verifikasi))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-start justify-between gap-4 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Hasil Verifikasi</h3>
                            <p class="text-xs text-gray-400">Hasil evaluasi NCR oleh pembuat NCR</p>
                        </div>
                    </div>

                    {{-- @if (!empty($ncr->ncr_baru))
                        <div class="alert alert-info mt-2">
                            NCR baru telah dibuat:
                            <a href="{{ route('ncr.show', $ncr->ncr_baru) }}" class="fw-semibold">
                                {{ $ncr->ncr_baru }}
                            </a>
                        </div>
                    @endif --}}

                    @if (auth()->id() === $ncr->user_id)
                        <a href="{{ route('ncr.verifikasi.form', $ncr->nomor_ncr) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Verifikasi
                        </a>
                    @endif
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    @include('ncr.partials.field', [
                        'label' => 'Nama Inspektor',
                        'value' => $ncr->user->name ?? null,
                    ])

                    @include('ncr.partials.field', [
                        'label' => 'Tanggal Verifikasi',
                        'value' => $ncr->tgl_verifikasi
                            ? \Carbon\Carbon::parse($ncr->tgl_verifikasi)->translatedFormat('l, d F Y')
                            : null,
                    ])

                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Hasil Verifikasi</p>

                        @if ($ncr->hasil_verifikasi === 'Efektif')
                            <span
                                class="inline-flex items-center gap-1.5 text-xs font-semibold bg-green-50 text-green-600 px-3 py-1.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>EFEKTIF
                            </span>
                        @elseif ($ncr->hasil_verifikasi === 'Tidak Efektif')
                            <span
                                class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-50 text-red-600 px-3 py-1.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>TIDAK EFEKTIF
                            </span>
                        @else
                            <p class="text-sm text-gray-800">{{ $ncr->hasil_verifikasi ?? '-' }}</p>
                        @endif
                    </div>

                    @if ($ncr->hasil_verifikasi === 'Tidak Efektif' && !empty($ncr->ncr_baru))
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">NCR Baru</p>
                            <a href="{{ route('ncr.show', $ncr->ncr_baru) }}"
                                class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $ncr->ncr_baru }}
                            </a>
                        </div>
                    @endif

                    <div class="md:col-span-2">
                        @include('ncr.partials.field', [
                            'label' => 'Penjelasan Verifikasi',
                            'value' => $ncr->verifikasi_qc,
                            'multiline' => true,
                        ])
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
