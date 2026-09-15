@extends('layouts.app')

@section('header')
    Detail Durability Product
@endsection

@section('content_width', 'w-full')

@section('content')

<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-[1600px] space-y-6">

        {{-- ========================================================= --}}
        {{-- Header --}}
        {{-- ========================================================= --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                <div>

                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        Durability Product
                    </p>

                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Detail Data Durability Product
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi detail durability komponen berdasarkan data LPPB dan riwayat kerusakan.
                    </p>

                </div>

                <div class="flex flex-wrap items-center gap-2">

                    {{-- Kembali --}}
                    <a
                        href="{{ route('durability.tabel-detail', request()->query()) }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    {{-- Edit --}}
                    <a
                        href="{{ route('durability.edit', $durability) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        <i class="fa-solid fa-pen-to-square mr-2"></i>
                        Edit Data
                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Summary --}}
        {{-- ========================================================= --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Tahun --}}
            <div class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                            Tahun
                        </p>

                        <p class="mt-3 text-2xl font-extrabold text-gray-900 dark:text-white">
                            {{ $durability->tahun ?? '-' }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                </div>

            </div>


            {{-- Rentang Penggantian --}}
            <div class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                            Rentang Penggantian
                        </p>

                        <div class="mt-3 flex items-end gap-2">

                            <span class="text-2xl font-extrabold text-gray-900 dark:text-white">
                                {{ $durability->rentang_penggantian ?? '-' }}
                            </span>

                            @if($durability->rentang_penggantian !== null)
                                <span class="pb-1 text-sm font-semibold text-gray-400">
                                    Bulan
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600 dark:bg-cyan-900/20 dark:text-cyan-400">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>

                </div>

            </div>


            {{-- Jumlah Penggantian --}}
            <div class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                            Jumlah Penggantian
                        </p>

                        <div class="mt-3 flex items-end gap-2">

                            <span class="text-2xl font-extrabold text-gray-900 dark:text-white">
                                {{ $durability->jumlah_penggantian ?? '-' }}
                            </span>

                            @if($durability->jumlah_penggantian !== null)
                                <span class="pb-1 text-sm font-semibold text-gray-400">
                                    Kali
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                    </div>

                </div>

            </div>


            {{-- Lokasi --}}
            <div class="rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">

                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                            Lokasi
                        </p>

                        <p
                            class="mt-3 truncate text-lg font-extrabold text-gray-900 dark:text-white"
                            title="{{ $durability->lokasi->nama_lokasi ?? '-' }}"
                        >
                            {{ $durability->lokasi->nama_lokasi ?? '-' }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Main Detail --}}
        {{-- ========================================================= --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- ===================================================== --}}
            {{-- Left --}}
            {{-- ===================================================== --}}
            <div class="space-y-6 xl:col-span-2">


                {{-- Informasi Proyek --}}
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900 dark:text-white">
                                    Informasi Proyek
                                </h2>

                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    Informasi customer dan proyek terkait.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 divide-y divide-gray-100 md:grid-cols-2 md:divide-x md:divide-y-0 dark:divide-gray-800">

                        {{-- Nomor PO --}}
                        <div class="p-6">

                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                Nomor PO
                            </p>

                            <p class="mt-2 font-bold text-gray-900 dark:text-white">
                                {{ $durability->proyek->nomor_po ?? '-' }}
                            </p>

                        </div>


                        {{-- Customer --}}
                        <div class="p-6">

                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                Customer
                            </p>

                            <p class="mt-2 font-bold text-gray-900 dark:text-white">
                                {{ $durability->proyek->customer ?? '-' }}
                            </p>

                        </div>

                    </div>


                    <div class="border-t border-gray-100 p-6 dark:border-gray-800">

                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                            Nama Proyek
                        </p>

                        <p class="mt-2 text-base font-bold text-gray-900 dark:text-white">
                            {{ $durability->proyek->nama_proyek ?? '-' }}
                        </p>

                    </div>

                </div>


                {{-- Informasi Produk --}}
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 dark:bg-cyan-900/20 dark:text-cyan-400">
                                <i class="fa-solid fa-cubes"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900 dark:text-white">
                                    Informasi Produk & Komponen
                                </h2>

                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    Identitas produk dan komponen yang mengalami penggantian.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 gap-0 md:grid-cols-2">

                        {{-- Produk --}}
                        <div class="border-b border-gray-100 p-6 md:border-b-0 md:border-r dark:border-gray-800">

                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                Produk
                            </p>

                            <p class="mt-2 font-bold text-gray-900 dark:text-white">
                                {{ $durability->komponen->produk->nama_produk ?? '-' }}
                            </p>

                        </div>


                        {{-- Komponen --}}
                        <div class="p-6">

                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                Detail Komponen
                            </p>

                            <p class="mt-2 font-bold text-gray-900 dark:text-white">
                                {{ $durability->komponen->nama_komponen ?? '-' }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Keterangan / Case --}}
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900 dark:text-white">
                                    Case / Keterangan
                                </h2>

                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    Informasi atau catatan terkait kerusakan komponen.
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="p-6">

                        @if($durability->case_keterangan)

                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5 text-sm leading-7 text-gray-700 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-300">
                                {!! nl2br(e($durability->case_keterangan)) !!}
                            </div>

                        @else

                            <div class="flex flex-col items-center justify-center py-8 text-center">

                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-gray-800">
                                    <i class="fa-solid fa-file-circle-xmark"></i>
                                </div>

                                <p class="mt-3 text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    Tidak ada keterangan tambahan.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Right Sidebar --}}
            {{-- ===================================================== --}}
            <div class="space-y-6">


                {{-- Trainset --}}
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400">
                                <i class="fa-solid fa-train"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900 dark:text-white">
                                    Trainset
                                </h2>

                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    Posisi komponen pada trainset.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="divide-y divide-gray-100 dark:divide-gray-800">

                        <div class="flex items-center justify-between gap-4 px-6 py-4">

                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Nomor Trainset
                            </span>

                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $durability->trainset->nomor_trainset ?? '-' }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4 px-6 py-4">

                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Tipe Car
                            </span>

                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $durability->trainset->tipe_car ?? '-' }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4 px-6 py-4">

                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Lokasi
                            </span>

                            <span class="text-right font-bold text-gray-900 dark:text-white">
                                {{ $durability->lokasi->nama_lokasi ?? '-' }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Timeline --}}
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400">
                                <i class="fa-solid fa-timeline"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900 dark:text-white">
                                    Timeline
                                </h2>

                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    Tanggal terkait data kerusakan dan LPPB.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="p-6">

                        <div class="relative space-y-7">

                            {{-- vertical line --}}
                            <div class="absolute bottom-5 left-[17px] top-5 w-px bg-gray-200 dark:bg-gray-700"></div>


                            {{-- Kerusakan --}}
                            <div class="relative flex gap-4">

                                <div class="z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-4 border-white bg-red-100 text-red-600 dark:border-gray-900 dark:bg-red-900/30 dark:text-red-400">
                                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                </div>

                                <div class="pt-1">

                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                        Tanggal Kerusakan
                                    </p>

                                    <p class="mt-1 font-bold text-gray-900 dark:text-white">
                                        {{ $durability->tgl_kerusakan
                                            ? $durability->tgl_kerusakan->translatedFormat('d F Y')
                                            : '-' }}
                                    </p>

                                    @if($durability->tgl_kerusakan)

                                        <p class="mt-0.5 text-xs text-gray-400">
                                            {{ $durability->tgl_kerusakan->format('Y-m-d') }}
                                        </p>

                                    @endif

                                </div>

                            </div>


                            {{-- LPPB --}}
                            <div class="relative flex gap-4">

                                <div class="z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-4 border-white bg-blue-100 text-blue-600 dark:border-gray-900 dark:bg-blue-900/30 dark:text-blue-400">
                                    <i class="fa-solid fa-file-circle-check text-xs"></i>
                                </div>

                                <div class="pt-1">

                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                        Tanggal Terbit LPPB
                                    </p>

                                    <p class="mt-1 font-bold text-gray-900 dark:text-white">
                                        {{ $durability->tgl_terbit_lppb
                                            ? $durability->tgl_terbit_lppb->translatedFormat('d F Y')
                                            : '-' }}
                                    </p>

                                    @if($durability->tgl_terbit_lppb)

                                        <p class="mt-0.5 text-xs text-gray-400">
                                            {{ $durability->tgl_terbit_lppb->format('Y-m-d') }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Informasi Sistem --}}
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">

                        <h2 class="font-bold text-gray-900 dark:text-white">
                            Informasi Data
                        </h2>

                    </div>


                    <div class="divide-y divide-gray-100 text-sm dark:divide-gray-800">

                        <div class="flex items-center justify-between gap-4 px-6 py-4">

                            <span class="text-gray-500 dark:text-gray-400">
                                ID Data
                            </span>

                            <span class="font-mono font-semibold text-gray-700 dark:text-gray-300">
                                #{{ $durability->id }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4 px-6 py-4">

                            <span class="text-gray-500 dark:text-gray-400">
                                Dibuat
                            </span>

                            <span class="text-right font-semibold text-gray-700 dark:text-gray-300">
                                {{ $durability->created_at
                                    ? $durability->created_at->format('d-m-Y H:i')
                                    : '-' }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4 px-6 py-4">

                            <span class="text-gray-500 dark:text-gray-400">
                                Terakhir Diubah
                            </span>

                            <span class="text-right font-semibold text-gray-700 dark:text-gray-300">
                                {{ $durability->updated_at
                                    ? $durability->updated_at->format('d-m-Y H:i')
                                    : '-' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Bottom Action --}}
        {{-- ========================================================= --}}
        <div class="flex flex-col gap-3 rounded-3xl border border-white/70 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="font-bold text-gray-900 dark:text-white">
                    Kelola Data Durability
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Anda dapat memperbarui atau menghapus data durability ini.
                </p>

            </div>


            <div class="flex flex-wrap gap-2">

                {{-- Edit --}}
                <a
                    href="{{ route('durability.edit', $durability) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <i class="fa-solid fa-pen-to-square mr-2"></i>
                    Edit Data
                </a>


                {{-- Delete --}}
                <form
                    method="POST"
                    action="{{ route('durability.destroy', $durability) }}"
                    onsubmit="return confirm('Yakin ingin menghapus data durability ini? Data yang sudah dihapus tidak dapat dikembalikan.')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-100 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30"
                    >
                        <i class="fa-solid fa-trash mr-2"></i>
                        Hapus Data
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
