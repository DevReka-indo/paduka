@extends('layouts.app')

@section('header')
    Tambah Kalibrasi
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
                {{ $qcFacility->name }}
            </p>

            <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Riwayat Kalibrasi
            </h1>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Serial Number:
                <span class="font-semibold">
                    {{ $qcFacilityUnit->serial_number }}
                </span>
            </p>
        </div>

        @if ($errors->any())
            <div
                class="rounded-2xl border border-red-200 bg-red-50
                    p-5 text-red-700 dark:border-red-900/40
                    dark:bg-red-900/20 dark:text-red-300"
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
            enctype="multipart/form-data"
            action="{{ route(
                'qc-facilities.units.calibrations.store',
                [
                    'qcFacility' => $qcFacility,
                    'qcFacilityUnit' => $qcFacilityUnit,
                ]
            ) }}"
            class="rounded-3xl border border-white/70 bg-white
                shadow-sm dark:border-gray-800 dark:bg-gray-900"
        >
            @csrf

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <label
                        for="calibration_date"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Tanggal Kalibrasi
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="calibration_date"
                        name="calibration_date"
                        type="date"
                        required
                        value="{{ old('calibration_date') }}"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                    >
                </div>

                <div>
                    <label
                        for="calibration_valid_until"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Berlaku Sampai
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="calibration_valid_until"
                        name="calibration_valid_until"
                        type="date"
                        required
                        value="{{ old('calibration_valid_until') }}"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                    >
                </div>

                <div>
                    <label
                        for="certificate_number"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Nomor Sertifikat
                    </label>

                    <input
                        id="certificate_number"
                        name="certificate_number"
                        type="text"
                        value="{{ old('certificate_number') }}"
                        placeholder="Contoh: CAL-2026-001"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                    >
                </div>

                <div>
                    <label
                        for="calibration_laboratory"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Laboratorium / Vendor
                    </label>

                    <input
                        id="calibration_laboratory"
                        name="calibration_laboratory"
                        type="text"
                        value="{{ old('calibration_laboratory') }}"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                    >
                </div>

                <div class="md:col-span-2">
                    <label
                        for="certificate"
                        class="mb-2 block text-sm font-semibold
                            text-gray-700 dark:text-gray-300"
                    >
                        Sertifikat Kalibrasi
                    </label>

                    <input
                        id="certificate"
                        name="certificate"
                        type="file"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full rounded-xl border
                            border-gray-300 bg-white text-sm
                            text-gray-700 dark:border-gray-700
                            dark:bg-gray-800 dark:text-gray-300"
                    >

                    <p class="mt-2 text-xs text-gray-400">
                        PDF, JPG atau PNG. Maksimal 10 MB.
                    </p>
                </div>

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
                        rows="4"
                        class="w-full rounded-xl border-gray-300
                            dark:border-gray-700 dark:bg-gray-800
                            dark:text-white"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <div
                class="flex justify-end gap-3 border-t
                    border-gray-100 px-6 py-5
                    dark:border-gray-800"
            >
                <a
                    href="{{ route(
                        'qc-facilities.units.show',
                        [
                            'qcFacility' => $qcFacility,
                            'qcFacilityUnit' => $qcFacilityUnit,
                        ]
                    ) }}"
                    class="rounded-xl border border-gray-300
                        px-4 py-2.5 text-sm font-semibold
                        text-gray-600 dark:border-gray-700
                        dark:text-gray-300"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5
                        text-sm font-semibold text-white
                        hover:bg-blue-700"
                >
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Kalibrasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection