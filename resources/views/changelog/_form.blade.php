<div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-6 space-y-5">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-1.5">
                Versi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="versi"
                value="{{ old('versi', $changelog->versi ?? '') }}"
                placeholder="v1.4.0"
                class="w-full rounded-xl border border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2.5 text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
            @error('versi')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-1.5">
                Tanggal Rilis <span class="text-red-500">*</span>
            </label>
            <input type="date" name="tanggal_rilis"
                value="{{ old('tanggal_rilis', isset($changelog) ? $changelog->tanggal_rilis?->format('Y-m-d') : '') }}"
                class="w-full rounded-xl border border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2.5 text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
            @error('tanggal_rilis')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-1.5">
                Tipe <span class="text-red-500">*</span>
            </label>
            <select name="tipe"
                class="w-full rounded-xl border border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2.5 text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                @foreach (['release' => 'Rilis', 'feature' => 'Fitur Baru', 'improvement' => 'Peningkatan', 'fix' => 'Perbaikan'] as $val => $label)
                    <option value="{{ $val }}" {{ old('tipe', $changelog->tipe ?? '') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('tipe')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-end pb-1">
            <label class="flex items-center gap-2.5 text-sm text-slate-700 dark:text-gray-300 cursor-pointer select-none">
                <input type="checkbox" name="is_published" value="1"
                    {{ old('is_published', $changelog->is_published ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                Langsung publish
            </label>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-1.5">
            Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
        </label>
        <textarea name="deskripsi" rows="2"
            placeholder="Ringkasan singkat pembaruan ini..."
            class="w-full rounded-xl border border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2.5 text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition resize-none">{{ old('deskripsi', $changelog->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
            Poin Perubahan <span class="text-red-500">*</span>
        </label>

        <div class="space-y-2">
            <template x-for="(item, index) in items" :key="index">
                <div class="flex gap-2 items-center">
                    <span
                        class="w-5 h-5 flex-shrink-0 flex items-center justify-center rounded-full bg-slate-100 dark:bg-gray-700 text-slate-400 text-xs font-medium"
                        x-text="index + 1"></span>

                    <input type="text"
                        :name="`items[${index}]`"
                        x-model="items[index]"
                        placeholder="Contoh: Tambah fitur export PDF"
                        class="flex-1 rounded-xl border border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2.5 text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">

                    <button type="button"
                        @click="items.splice(index, 1)"
                        x-show="items.length > 1"
                        class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <button type="button" @click="items.push('')"
            class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah poin
        </button>

        @error('items')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror

        @error('items.*')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="flex justify-end gap-3 mt-4">
    <a href="{{ route('changelog.index') }}"
        class="px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-gray-600 text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
        Batal
    </a>

    <button type="submit"
        class="px-5 py-2.5 text-sm rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition font-medium">
        {{ isset($changelog) ? 'Simpan Perubahan' : 'Tambah Changelog' }}
    </button>
</div>
