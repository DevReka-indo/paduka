<div>
    <x-input-label for="{{ $inputId ?? 'unit_kerja' }}" :value="__('Unit Kerja')" />
    <select id="{{ $inputId ?? 'unit_kerja' }}" name="unit_kerja"
        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
        <option value="">— Pilih Unit Kerja —</option>
        @foreach($unitKerja as $item)
            <option value="{{ $item->kode_unit ?? $item->nama_unit }}"
                @selected(old('unit_kerja', $selectedUnitKerja ?? '') == ($item->kode_unit ?? $item->nama_unit))>
                @if($item->kode_unit)
                    {{ $item->kode_unit }} — {{ $item->nama_unit }}
                @else
                    {{ $item->nama_unit }}
                @endif
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('unit_kerja')" class="mt-2" />
</div>
