@extends('layouts.app')

@section('header')
    Tambah Lokasi Temuan
@endsection

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

    {{-- Back Link --}}
    <a href="{{ route('temuan.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar temuan
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    Form Tambah Lokasi Temuan
                </h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Isi data lokasi temuan baru
                </p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('temuan.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Nomor --}}
            <div>
                <x-input-label for="nomor_temuan_preview" value="Nomor Temuan" />
                <x-text-input id="nomor_temuan_preview" type="text"
                    class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600 cursor-not-allowed"
                    :value="$nomorPreview"
                    readonly />
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    Nomor temuan akan dibuat otomatis saat data disimpan.
                </p>
            </div>

            {{-- Lokasi --}}
            <div>
                <x-input-label for="status_temuan" value="Lokasi Temuan" />
                <x-text-input id="status_temuan" name="status_temuan" type="text"
                    class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-500"
                    :value="old('status_temuan')"
                    placeholder="Contoh: Lokasi A"
                    required maxlength="25" />
                <x-input-error :messages="$errors->get('status_temuan')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    Maksimal 25 karakter.
                </p>
            </div>

            {{-- Kategori --}}
            <div>
                <x-input-label for="detail_temuan" value="Kategori Temuan" />
                <x-text-input id="detail_temuan" name="detail_temuan" type="text"
                    class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-500"
                    :value="old('detail_temuan')"
                    placeholder="Deskripsikan detail kategori temuan..."
                    maxlength="175" />
                <div class="flex justify-between mt-1">
                    <x-input-error :messages="$errors->get('detail_temuan')" />
                    <p class="text-xs text-gray-400 dark:text-gray-500 ml-auto">
                        Maksimal 175 karakter.
                    </p>
                </div>
            </div>

            {{-- Action --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <x-primary-button>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Temuan
                </x-primary-button>

                <a href="{{ route('temuan.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
