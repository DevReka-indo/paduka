@props([
    'topTrainsetPenggantian' => collect(),
    'produkList' => collect(),
    'selectedTrainsetProduk' => [],
])

@php
    // Pastikan selalu array
    $selectedTrainsetProduk = is_array($selectedTrainsetProduk)
        ? $selectedTrainsetProduk
        : array_filter([$selectedTrainsetProduk]);
    $selectedTrainsetProduk = array_map('strval', $selectedTrainsetProduk);
@endphp

<div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                Top 10 Trainset Penggantian Terbanyak
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Trainset dengan total jumlah penggantian tertinggi.
            </p>
        </div>

        <form method="GET" action="{{ route('durability.index') }}" id="trainset-filter-form">
            {{-- Pertahankan filter utama --}}
            @foreach(request()->except(['trainset_produk_id', 'page']) as $key => $value)
                @if(is_array($value))
                    @foreach($value as $nestedValue)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $nestedValue }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            {{-- Dropdown Multi-select --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button
                    type="button"
                    @click="open = !open"
                    class="flex min-w-[160px] items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <span>
                        @if(empty($selectedTrainsetProduk))
                            Semua Produk
                        @elseif(count($selectedTrainsetProduk) === 1)
                            {{ $produkList->firstWhere('id', (int) $selectedTrainsetProduk[0])?->nama_produk ?? 'Semua Produk' }}
                        @else
                            {{ count($selectedTrainsetProduk) }} Produk dipilih
                        @endif
                    </span>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div
                    x-show="open"
                    x-transition
                    class="absolute right-0 z-50 mt-1 w-64 rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">

                    {{-- Search --}}
                    <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                        <input
                            type="text"
                            placeholder="Cari produk..."
                            class="w-full rounded-lg border border-gray-200 px-3 py-1.5 text-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                            x-on:input="
                                const q = $event.target.value.toLowerCase();
                                $el.closest('.absolute').querySelectorAll('[data-produk-item]').forEach(el => {
                                    el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
                                });
                            ">
                    </div>

                    {{-- List produk --}}
                    <div class="max-h-56 overflow-y-auto p-1">
                        @foreach($produkList as $produk)
                            <label
                                data-produk-item
                                data-name="{{ strtolower($produk->nama_produk) }}"
                                class="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700">
                                <input
                                    type="checkbox"
                                    name="trainset_produk_id[]"
                                    value="{{ $produk->id }}"
                                    {{ in_array((string) $produk->id, $selectedTrainsetProduk) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                {{ $produk->nama_produk }}
                            </label>
                        @endforeach
                    </div>

                    {{-- Footer tombol --}}
                    <div class="flex items-center justify-between border-t border-gray-100 p-2 dark:border-gray-700">
                        <button
                            type="button"
                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                            onclick="
                                this.closest('.absolute').querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
                            ">
                            Reset
                        </button>
                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Badge produk yang dipilih --}}
    @if(!empty($selectedTrainsetProduk))
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($selectedTrainsetProduk as $pid)
                @php $pName = $produkList->firstWhere('id', (int) $pid)?->nama_produk; @endphp
                @if($pName)
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $pName }}
                    </span>
                @endif
            @endforeach
        </div>
    @endif

    {{-- Tabel --}}
    <div class="mt-5 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-3 py-3 text-left font-bold">#</th>
                    <th class="px-3 py-3 text-left font-bold">Trainset</th>
                    <th class="px-3 py-3 text-left font-bold">Car</th>
                    <th class="px-3 py-3 text-left font-bold">Produk</th>
                    <th class="px-3 py-3 text-right font-bold">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($topTrainsetPenggantian as $index => $item)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-3 py-3">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-3 py-3 font-semibold text-gray-900 dark:text-white">
                            {{ $item->nomor_trainset ? 'TS-' . $item->nomor_trainset : '-' }}
                        </td>
                        <td class="px-3 py-3 text-gray-600 dark:text-gray-300">
                            {{ $item->tipe_car ?? '-' }}
                        </td>
                        <td class="max-w-[220px] truncate px-3 py-3 text-gray-600 dark:text-gray-300"
                            title="{{ $item->nama_produk ?? '-' }}">
                            {{ $item->nama_produk ?? '-' }}
                        </td>
                        <td class="px-3 py-3 text-right font-bold text-gray-900 dark:text-white">
                            {{ number_format($item->total_penggantian ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-12 text-center text-gray-400">
                            Belum ada data trainset untuk produk ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
