@extends('layouts.app')

@section('header')
    Tambah Unit Kerja
@endsection

@section('content')
<div class="py-6 max-w-5xl mx-auto space-y-4">

    {{-- Back Link --}}
    <a href="{{ route('unit-kerja.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar unit kerja
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Form Tambah Unit Kerja</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Isi data unit kerja baru</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('unit-kerja.store') }}" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <x-input-label for="kode_unit" value="Kode Unit" />
                    <x-text-input id="kode_unit" name="kode_unit" type="text"
                        class="mt-1 block w-full font-mono uppercase"
                        :value="old('kode_unit')"
                        placeholder="Contoh: IT, HR, QC"
                        maxlength="20" />
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Opsional. Maks 20 karakter, harus unik.</p>
                    <x-input-error :messages="$errors->get('kode_unit')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nama_unit" value="Nama Unit Kerja" />
                    <x-text-input id="nama_unit" name="nama_unit" type="text"
                        class="mt-1 block w-full"
                        :value="old('nama_unit')"
                        placeholder="Nama lengkap unit kerja"
                        required maxlength="100" />
                    <x-input-error :messages="$errors->get('nama_unit')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="deskripsi" value="Deskripsi" />
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        placeholder="Deskripsi singkat unit kerja...">{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label value="Status" />
                    <div class="mt-2 flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="keterangan" value="1"
                                class="text-indigo-600 focus:ring-indigo-500"
                                @checked(old('keterangan', '1') == '1') />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="keterangan" value="0"
                                class="text-indigo-600 focus:ring-indigo-500"
                                @checked(old('keterangan') == '0') />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Nonaktif</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Action --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <x-primary-button>Simpan Unit Kerja</x-primary-button>
                <a href="{{ route('unit-kerja.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
