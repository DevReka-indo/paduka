@extends('layouts.app')

@section('header')
    Import Durability Product
@endsection

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1200px] space-y-6">

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
                        Import Data Durability
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Upload file Excel hasil normalisasi untuk mengganti seluruh data durability lama.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('durability.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>

                    <a href="{{ route('durability.tabel-detail') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        <i class="fa-solid fa-table mr-2"></i>
                        Lihat Tabel Detail
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                <div class="flex gap-3">
                    <div class="mt-0.5">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Import berhasil</p>
                        <p class="mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">
                <div class="flex gap-3">
                    <div class="mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Import gagal</p>
                        <p class="mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">
                <div class="flex gap-3">
                    <div class="mt-0.5">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Validasi gagal</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Upload Form --}}
            <div class="lg:col-span-2">
                <div class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                            Upload Excel
                        </p>
                        <h2 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">
                            File Data Durability
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Gunakan file Excel yang sudah dinormalisasi menjadi satu sheet dengan header sesuai format import.
                        </p>
                    </div>

                    <form action="{{ route('durability.import') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-5"
                          onsubmit="return confirm('Yakin ingin mengganti seluruh data durability lama dengan file Excel ini?')">
                        @csrf

                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Pilih File Excel
                            </label>

                            <div class="mt-2 rounded-2xl border border-dashed border-gray-300 bg-slate-50 p-6 dark:border-gray-700 dark:bg-gray-950">
                                <input
                                    type="file"
                                    name="file"
                                    id="file"
                                    accept=".xlsx,.xls,.csv"
                                    required
                                    class="block w-full cursor-pointer rounded-xl border border-gray-200 bg-white text-sm text-gray-700 shadow-sm file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                >

                                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                    Format file yang didukung: .xlsx, .xls, .csv
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-sm text-yellow-800 dark:border-yellow-900/40 dark:bg-yellow-900/20 dark:text-yellow-300">
                            <div class="flex gap-3">
                                <div class="mt-0.5">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        Import ini bersifat replace all data
                                    </p>
                                    <p class="mt-1">
                                        Sistem akan menghapus seluruh data lama pada tabel durability, lalu memasukkan ulang data dari file Excel yang diupload.
                                    </p>
                                    <p class="mt-1">
                                        Tabel master durability produk, komponen, lokasi, proyek, dan trainset juga akan dibuat ulang dari file Excel.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('durability.index') }}"
                               class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                Batal
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                                <i class="fa-solid fa-upload mr-2"></i>
                                Import dan Ganti Data Lama
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Format Info --}}
            <div class="space-y-6">
                <div class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        Format Kolom
                    </p>

                    <h2 class="mt-2 text-lg font-bold text-gray-900 dark:text-white">
                        Header Excel
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pastikan sheet utama memiliki header berikut.
                    </p>

                    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">
                        <div class="divide-y divide-gray-200 text-sm dark:divide-gray-800">
                            @foreach ([
                                'tahun',
                                'proyek_id',
                                'komponen_id',
                                'trainset_id',
                                'lokasi_id',
                                'tgl_kerusakan',
                                'tgl_terbit_lppb',
                                'case_keterangan',
                                'rentang_penggantian',
                                'jumlah_penggantian',
                            ] as $column)
                                <div class="flex items-center justify-between bg-white px-4 py-2 dark:bg-gray-900">
                                    <span class="font-mono text-xs text-gray-700 dark:text-gray-300">
                                        {{ $column }}
                                    </span>

                                    @if (in_array($column, ['tgl_kerusakan', 'case_keterangan', 'rentang_penggantian', 'jumlah_penggantian']))
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                            Nullable
                                        </span>
                                    @else
                                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                            Required
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/70 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        Catatan
                    </p>

                    <div class="mt-3 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex gap-3">
                            <i class="fa-solid fa-check mt-1 text-green-500"></i>
                            <p>
                                Nilai <span class="font-mono">rentang_penggantian</span> dan
                                <span class="font-mono">jumlah_penggantian</span> harus berupa angka bulat.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <i class="fa-solid fa-check mt-1 text-green-500"></i>
                            <p>
                                Jika ada data foreign key yang tidak cocok dengan master, import akan dibatalkan.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <i class="fa-solid fa-check mt-1 text-green-500"></i>
                            <p>
                                Data lama tidak akan berubah jika proses import gagal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
