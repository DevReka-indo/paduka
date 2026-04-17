@extends('layouts.app')

@section('header')
    Tambah Project
@endsection

@section('content')
<div class="py-6 max-w-2xl mx-auto space-y-4">

    <a href="{{ route('projects.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar project
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Form Tambah Project</h3>
                <p class="text-xs text-gray-400">Isi data project baru</p>
            </div>
        </div>

        <form method="POST" action="{{ route('projects.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <x-input-label for="nomor_proyek_preview" value="Nomor Proyek" />
                <x-text-input id="nomor_proyek_preview" type="text"
                    class="mt-1 block w-full bg-gray-50 text-gray-500"
                    :value="$nomorPreview"
                    readonly />
                <p class="mt-1 text-xs text-gray-400">Nomor proyek akan dibuat otomatis saat disimpan.</p>
            </div>

            <div>
                <x-input-label for="kode_proyek" value="Kode Proyek" />
                <x-text-input id="kode_proyek" name="kode_proyek" type="text"
                    class="mt-1 block w-full font-mono" :value="old('kode_proyek')"
                    placeholder="Contoh: PROJ-001-2026" required maxlength="50" />
                <x-input-error :messages="$errors->get('kode_proyek')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-400">Maksimal 50 karakter, harus unik. Digunakan sebagai referensi di NCR.</p>
            </div>

            <div>
                <x-input-label for="nama_proyek" value="Nama Proyek" />
                <textarea id="nama_proyek" name="nama_proyek" rows="3" maxlength="175"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                    placeholder="Nama lengkap proyek..." required>{{ old('nama_proyek') }}</textarea>
                <div class="flex justify-between mt-1">
                    <x-input-error :messages="$errors->get('nama_proyek')" />
                    <p class="text-xs text-gray-400 ml-auto">Maksimal 175 karakter.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <x-primary-button>Simpan Project</x-primary-button>
                <a href="{{ route('projects.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
