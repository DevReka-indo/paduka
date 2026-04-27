@extends('layouts.app')

@section('header')
    Edit Lokasi Temuan
@endsection

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">

        <a href="{{ route('temuan.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar temuan
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
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Edit Temuan — {{ $lokasi->nomor_temuan }}
                    </h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Perbarui data lokasi temuan
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('temuan.update', $lokasi) }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nomor_temuan_preview" value="Nomor Temuan" />
                    <x-text-input id="nomor_temuan_preview" type="text"
                        class="mt-1 block w-full bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600 cursor-not-allowed"
                        :value="$lokasi->nomor_temuan"
                        readonly />
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        Nomor temuan dibuat otomatis oleh sistem dan tidak dapat diubah.
                    </p>
                </div>

                <div>
                    <x-input-label for="status_temuan" value="Lokasi Temuan" />
                    <x-text-input id="status_temuan" name="status_temuan" type="text"
                        class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-500"
                        :value="old('status_temuan', $lokasi->status_temuan)"
                        placeholder="Contoh: Lokasi A"
                        required maxlength="25" />
                    <x-input-error :messages="$errors->get('status_temuan')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        Maksimal 25 karakter.
                    </p>
                </div>

                <div>
                    <x-input-label for="detail_temuan" value="Kategori Temuan" />
                    <textarea id="detail_temuan" name="detail_temuan" rows="4" maxlength="175"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 rounded-lg shadow-sm text-sm focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 resize-none"
                        placeholder="Kategori temuan...">{{ old('detail_temuan', $lokasi->detail_temuan) }}</textarea>
                    <div class="flex justify-between mt-1">
                        <x-input-error :messages="$errors->get('detail_temuan')" />
                        <p class="text-xs text-gray-400 dark:text-gray-500 ml-auto">
                            Maksimal 175 karakter.
                        </p>
                    </div>
                </div>

                @if (($lokasi->ncrs_count ?? $lokasi->ncrs()->count()) > 0)
                    <div class="flex items-start gap-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/40 rounded-xl p-4">
                        <svg class="w-4 h-4 text-orange-500 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                        </svg>
                        <p class="text-xs text-orange-700 dark:text-orange-300">
                            Temuan ini memiliki <strong>{{ $lokasi->ncrs()->count() }} NCR</strong> yang terkait.
                            Perubahan pada <em>status temuan</em> akan mempengaruhi relasi data NCR.
                        </p>
                    </div>
                @endif

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                    <a href="{{ route('temuan.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
@endsection
