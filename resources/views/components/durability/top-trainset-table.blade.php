@props([
    'topTrainsetPenggantian' => collect(),
    'produkList' => collect(),
    'selectedTrainsetProduk' => null,
])

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

        <form method="GET" action="{{ route('durability.index') }}" class="flex flex-wrap items-center gap-2">
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

            <select name="trainset_produk_id"
                onchange="this.form.submit()"
                class="rounded-xl border-gray-200 bg-white text-xs text-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Semua Produk</option>

                @foreach($produkList as $produk)
                    <option value="{{ $produk->id }}" @selected((string) $selectedTrainsetProduk === (string) $produk->id)>
                        {{ $produk->nama_produk }}
                    </option>
                @endforeach
            </select>

            @if($selectedTrainsetProduk)
                <a href="{{ route('durability.index', request()->except(['trainset_produk_id', 'page'])) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if($selectedTrainsetProduk)
        @php
            $selectedProdukName = $produkList->firstWhere('id', (int) $selectedTrainsetProduk)?->nama_produk;
        @endphp

        <div class="mt-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs text-blue-700 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300">
            Menampilkan top trainset untuk produk
            <strong>{{ $selectedProdukName ?? 'terpilih' }}</strong>
        </div>
    @endif

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
