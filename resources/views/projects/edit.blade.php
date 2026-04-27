@extends('layouts.app')

@section('header')
    Edit Project
@endsection

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

    <a href="{{ route('projects.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke daftar project
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Edit Project — {{ $project->nomor_proyek }}</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Perbarui data project</p>
            </div>
        </div>

        <form method="POST" action="{{ route('projects.update', $project) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="nomor_proyek_preview" value="Nomor Proyek" />
                <x-text-input id="nomor_proyek_preview" type="text"
                    class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600 cursor-not-allowed"
                    :value="$project->nomor_proyek"
                    readonly />
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    Nomor proyek dibuat otomatis oleh sistem dan tidak dapat diubah.
                </p>
            </div>

            <div>
                <x-input-label for="kode_proyek" value="Kode Proyek" />
                <x-text-input id="kode_proyek" name="kode_proyek" type="text"
                    class="mt-1 block w-full font-mono bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-500"
                    :value="old('kode_proyek', $project->kode_proyek)"
                    placeholder="Contoh: PROJ-001-2026"
                    required maxlength="50" />
                <x-input-error :messages="$errors->get('kode_proyek')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    Mengubah kode proyek akan mempengaruhi relasi data NCR.
                </p>
            </div>

            <div>
                <x-input-label for="nama_proyek" value="Nama Proyek" />
                <textarea id="nama_proyek" name="nama_proyek" rows="3" maxlength="175"
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 rounded-lg shadow-sm text-sm focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 resize-none"
                    placeholder="Nama lengkap proyek..."
                    required>{{ old('nama_proyek', $project->nama_proyek) }}</textarea>
                <div class="flex justify-between mt-1">
                    <x-input-error :messages="$errors->get('nama_proyek')" />
                    <p class="text-xs text-gray-400 dark:text-gray-500 ml-auto">Maksimal 175 karakter.</p>
                </div>
            </div>

            @if($project->ncrs()->exists())
                <div class="flex items-start gap-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/40 rounded-xl p-4">
                    <svg class="w-4 h-4 text-orange-500 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                    </svg>
                    <p class="text-xs text-orange-700 dark:text-orange-300">
                        Project ini memiliki <strong>{{ $project->ncrs()->count() }} NCR</strong> terkait.
                        Perubahan pada <em>kode proyek</em> akan mempengaruhi relasi data NCR yang ada.
                    </p>
                </div>
            @endif

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('projects.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
