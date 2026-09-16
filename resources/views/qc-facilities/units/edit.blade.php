@extends('layouts.app')

@section('header')
    Edit Unit Fasilitas QC
@endsection

@section('content_width', 'w-full')

@section('content')
    <div
        class="min-h-screen bg-slate-50 px-4 py-6
            dark:bg-gray-950 sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-5xl space-y-6">

            <div
                class="rounded-3xl border border-white/70
                    bg-white p-6 shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                <p
                    class="text-xs font-bold uppercase
                        tracking-[0.18em] text-blue-600
                        dark:text-blue-400"
                >
                    {{ $qcFacility->name }}
                </p>

                <h1
                    class="mt-2 text-2xl font-bold
                        text-gray-900 dark:text-white"
                >
                    Edit Unit Fasilitas
                </h1>

                <p
                    class="mt-2 text-sm text-gray-500
                        dark:text-gray-400"
                >
                    {{ $qcFacilityUnit->serial_number }}
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
                    <ul
                        class="list-inside list-disc
                            space-y-1 text-sm"
                    >
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route(
                    'qc-facilities.units.update',
                    [
                        'qcFacility' => $qcFacility,
                        'qcFacilityUnit' => $qcFacilityUnit,
                    ]
                ) }}"
                class="rounded-3xl border border-white/70
                    bg-white shadow-sm
                    dark:border-gray-800 dark:bg-gray-900"
            >
                @csrf
                @method('PUT')

                <div
                    class="grid gap-6 p-6
                        md:grid-cols-2"
                >
                    <div>
                        <label
                            for="serial_number"
                            class="mb-2 block text-sm
                                font-semibold text-gray-700
                                dark:text-gray-300"
                        >
                            Serial Number
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="serial_number"
                            name="serial_number"
                            type="text"
                            required
                            value="{{ old(
                                'serial_number',
                                $qcFacilityUnit->serial_number
                            ) }}"
                            class="w-full rounded-xl
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-white"
                        >
                    </div>

                    <div>
                        <label
                            for="inventory_number"
                            class="mb-2 block text-sm
                                font-semibold text-gray-700
                                dark:text-gray-300"
                        >
                            Nomor Inventaris
                        </label>

                        <input
                            id="inventory_number"
                            name="inventory_number"
                            type="text"
                            value="{{ old(
                                'inventory_number',
                                $qcFacilityUnit->inventory_number
                            ) }}"
                            class="w-full rounded-xl
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-white"
                        >
                    </div>

                    <div>
                        <label
                            for="location"
                            class="mb-2 block text-sm
                                font-semibold text-gray-700
                                dark:text-gray-300"
                        >
                            Lokasi
                        </label>

                        <input
                            id="location"
                            name="location"
                            type="text"
                            value="{{ old(
                                'location',
                                $qcFacilityUnit->location
                            ) }}"
                            class="w-full rounded-xl
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-white"
                        >
                    </div>

                    <div>
                        <label
                            for="condition"
                            class="mb-2 block text-sm
                                font-semibold text-gray-700
                                dark:text-gray-300"
                        >
                            Kondisi
                        </label>

                        <select
                            id="condition"
                            name="condition"
                            required
                            class="w-full rounded-xl
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-white"
                        >
                            @foreach ([
                                'baik' => 'Baik',
                                'perlu_perbaikan' => 'Perlu Perbaikan',
                                'dalam_perbaikan' => 'Dalam Perbaikan',
                                'tidak_layak' => 'Tidak Layak Digunakan',
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'condition',
                                            $qcFacilityUnit->condition
                                        ) === $value
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="notes"
                            class="mb-2 block text-sm
                                font-semibold text-gray-700
                                dark:text-gray-300"
                        >
                            Catatan
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="5"
                            class="w-full rounded-xl
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-white"
                        >{{ old(
                            'notes',
                            $qcFacilityUnit->notes
                        ) }}</textarea>
                    </div>
                </div>

                <div
                    class="flex justify-end gap-3
                        border-t border-gray-100
                        px-6 py-5 dark:border-gray-800"
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
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
