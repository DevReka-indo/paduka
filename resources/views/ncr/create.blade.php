@extends('layouts.app')

@section('header')
    Tambah NCR Baru
@endsection

@section('content')
    <div class="py-6 max-w-5xl mx-auto space-y-4">

        <a href="{{ route('ncr.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar NCR
        </a>

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 text-red-700 dark:text-red-300 rounded-xl px-4 py-3 text-sm">
                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ncr.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            @if (!empty($ncrLama))
                <input type="hidden" name="from_ncr" value="{{ $ncrLama->nomor_ncr }}">

                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 text-amber-700 dark:text-amber-300 rounded-xl px-4 py-3 text-sm">
                    Anda sedang membuat NCR baru dari NCR:
                    <a href="{{ route('ncr.show', $ncrLama->nomor_ncr) }}" class="font-semibold underline">
                        {{ $ncrLama->nomor_ncr }}
                    </a>
                </div>
            @endif

            {{-- Section 1: Informasi Dasar --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Informasi Dasar</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Nomor NCR, tanggal, dan inspektor</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <x-input-label for="nomor_ncr" value="Nomor NCR" />
                        <x-text-input id="nomor_ncr" name="nomor_ncr" type="text"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed"
                            :value="old('nomor_ncr', $nomorNCR)" readonly />
                    </div>

                    <div>
                        <x-input-label for="tgl_display" value="Hari, Tanggal Terbit" />
                        <x-text-input id="tgl_display" type="text"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed"
                            value="{{ \Carbon\Carbon::parse(old('tgl_masuk', now()))->translatedFormat('l, d F Y') }}"
                            readonly />
                        <input type="hidden" name="tgl_masuk" value="{{ old('tgl_masuk', now()->format('Y-m-d')) }}" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="inspektor" value="Nama Inspektor" />
                        <x-text-input id="inspektor" type="text"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed"
                            value="{{ auth()->user()->name ?? '-' }}" readonly />
                    </div>

                </div>
            </div>

            {{-- Section 2: Detail Temuan --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 bg-orange-50 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Detail Temuan</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Proyek, lokasi, dan uraian ketidaksesuaian</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <x-input-label for="nama_proses" value="Nama Produk / Proses" />
                        <x-text-input id="nama_proses" name="nama_proses" type="text"
                            class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600"
                            :value="old('nama_proses')" placeholder="Nama produk atau proses" required />
                        <x-input-error :messages="$errors->get('nama_proses')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="kode_proyek" value="Nama / Kode Proyek" />
                        <select id="kode_proyek" name="kode_proyek" data-placeholder="Cari proyek..."
                            class="searchable-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""></option>
                            @foreach ($proyek as $item)
                                <option value="{{ $item->kode_proyek }}" @selected(old('kode_proyek') == $item->kode_proyek)>
                                    {{ $item->kode_proyek }} — {{ $item->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kode_proyek')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="acuan_periksa" value="Acuan Pemeriksaan" />
                        <x-text-input id="acuan_periksa" name="acuan_periksa" type="text"
                            class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600"
                            :value="old('acuan_periksa')" placeholder="Inspection Sheet" />
                        <x-input-error :messages="$errors->get('acuan_periksa')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="surat_jalan" value="Surat Jalan (S/N)" />
                        <x-text-input id="surat_jalan" name="surat_jalan" type="text"
                            class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600"
                            :value="old('surat_jalan')" placeholder="Nomor surat jalan" />
                        <x-input-error :messages="$errors->get('surat_jalan')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="status_temuan" value="Lokasi Ketidaksesuaian" />
                        <select id="status_temuan" name="status_temuan" data-placeholder="Cari lokasi temuan..."
                            class="searchable-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""></option>
                            @foreach ($temuan as $item)
                                <option value="{{ $item->status_temuan }}" @selected(old('status_temuan') == $item->status_temuan)>
                                    {{ $item->status_temuan }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status_temuan')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="uraian" value="Uraian Ketidaksesuaian" />
                        <textarea id="uraian" name="uraian" rows="4"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                            placeholder="Jelaskan uraian ketidaksesuaian...">{{ old('uraian') }}</textarea>
                        <x-input-error :messages="$errors->get('uraian')" class="mt-2" />
                    </div>

                </div>
            </div>

            {{-- Section 3: Penanganan --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Penanganan</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">PIC, target penyelesaian, unit, dan disposisi</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <x-input-label for="penanggung_jawab" value="Person In Charge (PIC)" />
                        <select id="penanggung_jawab" name="penanggung_jawab" data-placeholder="Cari PIC..."
                            class="searchable-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""></option>
                            @foreach ($pengguna as $item)
                                <option value="{{ $item->id }}" @selected(old('penanggung_jawab') == $item->id)>
                                    {{ $item->name }}{{ $item->jabatan ? ' — ' . $item->jabatan : '' }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('penanggung_jawab')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tgl_target" value="Target Tanggal Penyelesaian" />
                        <x-text-input id="tgl_target" name="tgl_target" type="date"
                            class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600"
                            :value="old('tgl_target')" />
                        <x-input-error :messages="$errors->get('tgl_target')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="disposisi_inspektor" value="Disposisi Inspektor" />
                        <select id="disposisi_inspektor" name="disposisi_inspektor" data-placeholder="Cari disposisi..."
                            class="searchable-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""></option>
                            <option value="internal" @selected(old('disposisi_inspektor') == 'internal')>Internal</option>
                            <option value="eksternal" @selected(old('disposisi_inspektor') == 'eksternal')>Eksternal</option>
                        </select>
                        <x-input-error :messages="$errors->get('disposisi_inspektor')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="unit_kerja_id" value="Unit Yang Dituju" />
                        <select id="unit_kerja_id" name="unit_kerja_id" data-placeholder="Cari unit kerja..."
                            class="searchable-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""></option>
                            @foreach ($unitKerja as $item)
                                <option value="{{ $item->id }}" @selected(old('unit_kerja_id') == $item->id)>
                                    @if ($item->kode_unit)
                                        {{ $item->kode_unit }} —
                                    @endif
                                    {{ $item->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('unit_kerja_id')" class="mt-2" />
                    </div>

                </div>
            </div>

            {{-- Section 4: Dokumen Pendukung --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 space-y-4" x-data="dokumenUpload()">

                    <div>
                        <x-input-label for="doc_pendukung" value="Deskripsi Dokumen Pendukung" />
                        <x-text-input id="doc_pendukung" name="doc_pendukung" type="text"
                            class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600"
                            :value="old('doc_pendukung')" placeholder="Deskripsi singkat dokumen" />
                        <x-input-error :messages="$errors->get('doc_pendukung')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="up_file" value="Upload Dokumen (jpg, jpeg, png)" />
                        <div class="mt-1">
                            <input type="file" id="up_file" name="up_file" accept=".jpg,.jpeg,.png"
                                class="w-full text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer
                                file:mr-3 file:py-2 file:px-4 file:border-0 file:text-xs file:font-medium
                                file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-600 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50"
                                @change="onFileChange($event)" />
                        </div>
                        <x-input-error :messages="$errors->get('up_file')" class="mt-2" />
                    </div>

                    <div x-show="preview" x-transition>
                        <img :src="preview" alt="Preview"
                            class="w-full max-h-48 object-contain rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan NCR
                </x-primary-button>

                <a href="{{ route('ncr.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function dokumenUpload() {
            return {
                preview: null,
                onFileChange(event) {
                    const file = event.target.files[0];
                    if (!file) {
                        this.preview = null;
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

    <style>
        .ts-wrapper.single .ts-control {
            min-height: 42px;
            border-radius: 0.5rem;
            border-color: rgb(209 213 219);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            box-shadow: none;
            background-color: #ffffff;
            color: #374151;
        }

        .ts-wrapper.focus .ts-control {
            border-color: rgb(99 102 241);
            box-shadow: 0 0 0 1px rgb(99 102 241);
        }

        .ts-dropdown {
            border-radius: 0.75rem;
            border-color: rgb(229 231 235);
            font-size: 0.875rem;
            overflow: hidden;
            background: #ffffff;
            color: #374151;
        }

        .ts-dropdown .option,
        .ts-dropdown .create {
            padding: 0.625rem 0.875rem;
        }

        .ts-control input {
            font-size: 0.875rem !important;
        }

        .dark .ts-wrapper.single .ts-control {
            background-color: #111827;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .dark .ts-wrapper.single .ts-control input {
            color: #e5e7eb !important;
        }

        .dark .ts-wrapper.single .ts-control .item {
            color: #e5e7eb;
        }

        .dark .ts-dropdown {
            background: #111827;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .dark .ts-dropdown .option {
            background: transparent;
            color: #e5e7eb;
        }

        .dark .ts-dropdown .option.active,
        .dark .ts-dropdown .option:hover {
            background: #1f2937;
            color: #f9fafb;
        }

        .dark .ts-dropdown .no-results {
            color: #9ca3af !important;
        }

        .dark .ts-control,
        .dark .ts-dropdown,
        .dark .ts-dropdown-content {
            scrollbar-color: #4b5563 #111827;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.searchable-select').forEach(function (el) {
                const placeholder = el.dataset.placeholder || 'Ketik untuk mencari...';

                new TomSelect(el, {
                    create: false,
                    allowEmptyOption: true,
                    openOnFocus: true,
                    closeAfterSelect: true,
                    maxOptions: 500,
                    searchField: ['text'],
                    placeholder: placeholder,
                    dropdownParent: 'body',
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    },
                    render: {
                        no_results: function(data, escape) {
                            return '<div class="no-results p-3 text-sm text-gray-400 dark:text-gray-500">Tidak ada hasil untuk "' + escape(data.input) + '"</div>';
                        }
                    },
                    onInitialize: function () {
                        if (!this.getValue()) {
                            this.clear(true);
                            this.inputState();
                        }
                    }
                });
            });
        });
    </script>
@endpush
