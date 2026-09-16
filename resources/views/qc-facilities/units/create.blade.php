@extends('layouts.app')

@section('header')
    Tambah Unit Fasilitas QC
@endsection

@section('content_width', 'w-full')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-gray-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl space-y-6">

        <div
            class="rounded-3xl border border-white/70 bg-white p-6
                shadow-sm dark:border-gray-800 dark:bg-gray-900"
        >
            <p
                class="text-xs font-bold uppercase tracking-[0.18em]
                    text-blue-600 dark:text-blue-400"
            >
                Fasilitas Quality Control
            </p>

            <h1
                class="mt-2 text-2xl font-bold text-gray-900
                    dark:text-white"
            >
                Tambah Unit {{ $qcFacility->name }}
            </h1>

            <p
                class="mt-2 text-sm text-gray-500
                    dark:text-gray-400"
            >
                Tambahkan perangkat fisik berdasarkan
                serial number dan nomor inventaris.
            </p>
        </div>

        @if ($errors->any())
            <div
                class="rounded-2xl border border-red-200
                    bg-red-50 p-5 text-red-700
                    dark:border-red-900/40
                    dark:bg-red-900/20
                    dark:text-red-300"
            >
                <ul class="list-inside list-disc space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route(
                'qc-facilities.units.store',
                $qcFacility
            ) }}"
            class="rounded-3xl border border-white/70
                bg-white shadow-sm
                dark:border-gray-800 dark:bg-gray-900"
        >
            @csrf

            <div class="grid gap-6 p-6 md:grid-cols-2">

                {{-- Serial Number --}}
                <div>
                    <label
                        for="serial_number"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Serial Number
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="serial_number"
                        name="serial_number"
                        type="text"
                        value="{{ old('serial_number') }}"
                        required
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                        placeholder="Contoh: TG-2026-001"
                    >
                </div>

                {{-- Nomor Inventaris --}}
                <div>
                    <label
                        for="inventory_number"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Nomor Inventaris
                    </label>

                    <input
                        id="inventory_number"
                        name="inventory_number"
                        type="text"
                        value="{{ old('inventory_number') }}"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                        placeholder="Contoh: INV-QC-001"
                    >
                </div>

                {{-- Lokasi --}}
                <div>
                    <label
                        for="location"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Lokasi
                    </label>

                    <input
                        id="location"
                        name="location"
                        type="text"
                        value="{{ old('location') }}"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                        placeholder="Contoh: Laboratorium QC"
                    >
                </div>

                {{-- Kondisi --}}
                <div>
                    <label
                        for="condition"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Kondisi
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="condition"
                        name="condition"
                        required
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                    >
                        <option
                            value="baik"
                            @selected(old('condition', 'baik') === 'baik')
                        >
                            Baik
                        </option>

                        <option
                            value="perlu_perbaikan"
                            @selected(
                                old('condition')
                                    === 'perlu_perbaikan'
                            )
                        >
                            Perlu Perbaikan
                        </option>

                        <option
                            value="dalam_perbaikan"
                            @selected(
                                old('condition')
                                    === 'dalam_perbaikan'
                            )
                        >
                            Dalam Perbaikan
                        </option>

                        <option
                            value="tidak_layak"
                            @selected(
                                old('condition')
                                    === 'tidak_layak'
                            )
                        >
                            Tidak Layak Digunakan
                        </option>
                    </select>
                </div>

                {{-- Catatan --}}
                <div class="md:col-span-2">
                    <label
                        for="notes"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Catatan
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="5"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                        placeholder="Catatan kondisi atau informasi unit..."
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <div
                class="flex items-center justify-end gap-3
                    border-t border-gray-100 px-6 py-5
                    dark:border-gray-800"
            >
                <a
                    href="{{ route(
                        'qc-facilities.show',
                        $qcFacility
                    ) }}"
                    class="rounded-xl border border-gray-300
                        px-4 py-2.5 text-sm font-semibold
                        text-gray-600
                        dark:border-gray-700
                        dark:text-gray-300"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600
                        px-5 py-2.5 text-sm font-semibold
                        text-white hover:bg-blue-700"
                >
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
