@extends('layouts.app')

@section('header')
    Edit Unit Kerja
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
            <div class="w-8 h-8 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    Edit Unit Kerja — {{ $unitKerja->nama_unit }}
                </h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Perbarui data unit kerja</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('unit-kerja.update', $unitKerja) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <x-input-label for="kode_unit" value="Kode Unit" />
                    <x-text-input id="kode_unit" name="kode_unit" type="text"
                        class="mt-1 block w-full font-mono uppercase"
                        :value="old('kode_unit', $unitKerja->kode_unit)"
                        placeholder="Contoh: IT, HR, QC"
                        maxlength="20" />
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Opsional. Maks 20 karakter, harus unik.</p>
                    <x-input-error :messages="$errors->get('kode_unit')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nama_unit" value="Nama Unit Kerja" />
                    <x-text-input id="nama_unit" name="nama_unit" type="text"
                        class="mt-1 block w-full"
                        :value="old('nama_unit', $unitKerja->nama_unit)"
                        required maxlength="100" />
                    <x-input-error :messages="$errors->get('nama_unit')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="deskripsi" value="Deskripsi" />
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        placeholder="Deskripsi singkat unit kerja...">{{ old('deskripsi', $unitKerja->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label value="Status" />
                    <div class="mt-2 flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="keterangan" value="1"
                                class="text-indigo-600 focus:ring-indigo-500"
                                @checked(old('keterangan', $unitKerja->keterangan ? '1' : '0') == '1') />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="keterangan" value="0"
                                class="text-indigo-600 focus:ring-indigo-500"
                                @checked(old('keterangan', $unitKerja->keterangan ? '1' : '0') == '0') />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Nonaktif</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Warning jika masih ada user --}}
            @if($unitKerja->users_count ?? $unitKerja->users()->count() > 0)
                <div class="flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-xl p-4">
                    <svg class="w-4 h-4 text-amber-500 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                    </svg>
                    <p class="text-xs text-amber-700 dark:text-amber-300">
                        Unit kerja ini digunakan oleh <strong>{{ $unitKerja->users()->count() }} pengguna</strong>.
                        Perubahan <em>kode unit</em> akan mempengaruhi data pengguna yang terkait.
                    </p>
                </div>
            @endif

            {{-- Action --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('unit-kerja.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
