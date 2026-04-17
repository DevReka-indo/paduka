<div class="md:col-span-2">
    <x-input-label value="Unit Kerja" />
    <p class="text-xs text-gray-400 mt-0.5 mb-2">Pilih satu atau lebih unit kerja yang sesuai</p>

    @if($unitKerja->isEmpty())
        <p class="text-xs text-gray-400 italic">Belum ada data unit kerja aktif.
            <a href="{{ route('unit-kerja.create') }}" class="text-indigo-500 hover:underline">Tambah sekarang</a>
        </p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 border border-gray-200 rounded-lg bg-gray-50 max-h-52 overflow-y-auto">
            @foreach($unitKerja as $item)
                <label class="flex items-start gap-2.5 p-2 rounded-lg hover:bg-white cursor-pointer transition-colors
                    {{ in_array($item->id, $selectedIds ?? []) ? 'bg-white ring-1 ring-indigo-200' : '' }}">
                    <input type="checkbox"
                        name="unit_kerja_ids[]"
                        value="{{ $item->id }}"
                        class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300"
                        {{ in_array($item->id, $selectedIds ?? []) ? 'checked' : '' }} />
                    <div class="leading-tight">
                        <p class="text-sm font-medium text-gray-700">{{ $item->nama_unit }}</p>
                        @if($item->kode_unit)
                            <span class="font-mono text-xs text-gray-400">{{ $item->kode_unit }}</span>
                        @endif
                        @if($item->deskripsi)
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($item->deskripsi, 50) }}</p>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('unit_kerja_ids')" class="mt-2" />
        <x-input-error :messages="$errors->get('unit_kerja_ids.*')" class="mt-1" />
    @endif
</div>
