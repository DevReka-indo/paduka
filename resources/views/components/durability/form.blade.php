@props([
    'durability' => null,
    'action',
    'method' => 'POST',
    'submitLabel' => 'Simpan Data',
])

@php
    $isEdit = filled($durability);

    $nomorPo      = old('nomor_po',      $durability?->proyek?->nomor_po);
    $customer     = old('customer',      $durability?->proyek?->customer);
    $namaProyek   = old('nama_proyek',   $durability?->proyek?->nama_proyek);
    $namaProduk   = old('nama_produk',   $durability?->komponen?->produk?->nama_produk);
    $namaKomponen = old('nama_komponen', $durability?->komponen?->nama_komponen);
    $nomorTrainset= old('nomor_trainset',$durability?->trainset?->nomor_trainset);
    $tipeCar      = old('tipe_car',      $durability?->trainset?->tipe_car);
    $namaLokasi   = old('nama_lokasi',   $durability?->lokasi?->nama_lokasi);
@endphp

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

    {{-- Card Header --}}
    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
            <i class="fa-solid fa-shield-halved text-[10px]"></i>
            Durability Product
        </span>
        <h2 class="mt-2.5 text-base font-semibold text-gray-900 dark:text-white">
            {{ $isEdit ? 'Edit data durability' : 'Tambah data durability' }}
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $isEdit
                ? 'Perbarui data komponen, kerusakan, dan penggantian sesuai laporan terbaru.'
                : 'Lengkapi data durability komponen berdasarkan data kerusakan dan penggantian.' }}
        </p>
    </div>

    <form method="POST" action="{{ $action }}" class="p-6">
        @csrf

        @if(strtoupper($method) !== 'POST')
            @method($method)
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-900/40 dark:bg-red-900/20">
                <i class="fa-solid fa-circle-exclamation mt-0.5 text-sm text-red-500 dark:text-red-400"></i>
                <div>
                    <p class="text-sm font-semibold text-red-700 dark:text-red-300">Data belum bisa disimpan.</p>
                    <ul class="mt-1.5 list-inside list-disc space-y-1 text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ── SEKSI 1: Informasi Proyek ── --}}
        <div class="mb-5 flex items-center gap-2">
            <i class="fa-solid fa-building-columns text-xs text-gray-400"></i>
            <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Informasi proyek</span>
            <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div>
                <label for="tahun" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Tahun <span class="text-red-500">*</span>
                </label>
                <input
                    id="tahun" type="number" name="tahun"
                    value="{{ old('tahun', $durability?->tahun ?? now()->year) }}"
                    min="2000" max="2100"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 shadow-none transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    required
                >
            </div>

            <div>
                <label for="nomor_po" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Nomor PO
                </label>
                <input
                    id="nomor_po" type="text" name="nomor_po"
                    value="{{ $nomorPo }}"
                    placeholder="4500010449"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>

            <div>
                <label for="customer" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Customer
                </label>
                <input
                    id="customer" type="text" name="customer"
                    value="{{ $customer }}"
                    placeholder="INKA"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>

            <div class="sm:col-span-3">
                <label for="nama_proyek" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Nama proyek
                </label>
                <input
                    id="nama_proyek" type="text" name="nama_proyek"
                    value="{{ $namaProyek }}"
                    placeholder="Contoh: 612"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>
        </div>

        {{-- ── SEKSI 2: Identitas Komponen ── --}}
        <div class="mb-5 flex items-center gap-2">
            <i class="fa-solid fa-microchip text-xs text-gray-400"></i>
            <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Identitas komponen</span>
            <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="nama_produk" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Produk <span class="text-red-500">*</span>
                </label>
                <input
                    id="nama_produk" type="text" name="nama_produk"
                    value="{{ $namaProduk }}"
                    placeholder="Panel Distribusi"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    required
                >
            </div>

            <div>
                <label for="nama_komponen" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Komponen <span class="text-red-500">*</span>
                </label>
                <input
                    id="nama_komponen" type="text" name="nama_komponen"
                    value="{{ $namaKomponen }}"
                    placeholder="ABB GSN201C"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    required
                >
            </div>

            <div>
                <label for="nomor_trainset" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Nomor trainset
                </label>
                <input
                    id="nomor_trainset" type="text" name="nomor_trainset"
                    value="{{ $nomorTrainset }}"
                    placeholder="25"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>

            <div>
                <label for="tipe_car" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Tipe car
                </label>
                <input
                    id="tipe_car" type="text" name="tipe_car"
                    value="{{ $tipeCar }}"
                    placeholder="K1 / K3 / M"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>

            <div class="sm:col-span-2">
                <label for="nama_lokasi" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Lokasi
                </label>
                <input
                    id="nama_lokasi" type="text" name="nama_lokasi"
                    value="{{ $namaLokasi }}"
                    placeholder="INKA / Cipinang"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>
        </div>

        {{-- ── SEKSI 3: Data Kerusakan & Penggantian ── --}}
        <div class="mb-5 flex items-center gap-2">
            <i class="fa-solid fa-calendar-days text-xs text-gray-400"></i>
            <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Data kerusakan & penggantian</span>
            <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div>
                <label for="tgl_kerusakan" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Tanggal kerusakan
                </label>
                <input
                    id="tgl_kerusakan" type="date" name="tgl_kerusakan"
                    value="{{ old('tgl_kerusakan', $durability?->tgl_kerusakan?->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>

            <div>
                <label for="tgl_terbit_lppb" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Tanggal LPPB
                </label>
                <input
                    id="tgl_terbit_lppb" type="date" name="tgl_terbit_lppb"
                    value="{{ old('tgl_terbit_lppb', $durability?->tgl_terbit_lppb?->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                >
            </div>

            <div>
                <label for="rentang_penggantian" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Rentang penggantian
                </label>
                <div class="relative">
                    <input
                        id="rentang_penggantian" type="number" name="rentang_penggantian"
                        value="{{ old('rentang_penggantian', $durability?->rentang_penggantian) }}"
                        min="0" placeholder="9"
                        class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-3 pr-16 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                    >
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs font-semibold text-gray-400">Bulan</span>
                </div>
            </div>

            <div>
                <label for="jumlah_penggantian" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                    Jumlah penggantian <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        id="jumlah_penggantian" type="number" name="jumlah_penggantian"
                        value="{{ old('jumlah_penggantian', $durability?->jumlah_penggantian ?? 1) }}"
                        min="0" placeholder="1"
                        class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-3 pr-12 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                        required
                    >
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs font-semibold text-gray-400">Kali</span>
                </div>
            </div>
        </div>

        {{-- ── SEKSI 4: Catatan ── --}}
        <div class="mb-5 flex items-center gap-2">
            <i class="fa-solid fa-note-sticky text-xs text-gray-400"></i>
            <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Catatan</span>
            <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
        </div>

        <div class="mb-6">
            <label for="case_keterangan" class="mb-1.5 block text-xs font-semibold text-gray-600 dark:text-gray-300">
                Case / keterangan
            </label>
            <textarea
                id="case_keterangan" name="case_keterangan" rows="4"
                placeholder="Tuliskan keterangan kerusakan atau catatan tambahan..."
                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
            >{{ old('case_keterangan', $durability?->case_keterangan) }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-end">
            <a
                href="{{ route('durability.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                {{ $submitLabel }}
            </button>
        </div>

    </form>
</div>
