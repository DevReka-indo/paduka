@props([
    'durability' => collect(),
    'filterOptions' => [],
])

<div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">
            Data Durability Produk
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Daftar data durability berdasarkan filter yang dipilih.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/70 dark:text-gray-400">
                <tr>
                    @php
                        $headers = [
                            ['label' => 'Nomor PO', 'name' => 'nomor_po'],
                            ['label' => 'Customer', 'name' => 'customer'],
                            ['label' => 'Proyek', 'name' => 'proyek'],
                            ['label' => 'Produk', 'name' => 'produk'],
                            ['label' => 'Komponen', 'name' => 'komponen'],
                            ['label' => 'Trainset', 'name' => 'trainset'],
                            ['label' => 'Car', 'name' => 'tipe_car'],
                            ['label' => 'Lokasi', 'name' => 'lokasi'],
                            ['label' => 'Tgl Kerusakan', 'name' => 'tgl_kerusakan'],
                            ['label' => 'Tgl LPPB', 'name' => 'tgl_lppb'],
                            ['label' => 'Rentang', 'name' => 'rentang'],
                            ['label' => 'Jumlah', 'name' => 'jumlah'],
                        ];
                    @endphp

                    @foreach ($headers as $header)
                        <th class="px-5 py-4 text-left font-bold">
                            <x-durability.column-filter
                                :label="$header['label']"
                                :name="$header['name']"
                                :options="$filterOptions[$header['name']] ?? collect()"
                            />
                        </th>
                    @endforeach

                    <th class="sticky right-0 z-20 min-w-[130px] border-l border-gray-100 bg-gray-50 px-5 py-4 text-center font-bold shadow-[-8px_0_12px_-12px_rgba(0,0,0,0.25)] dark:border-gray-700 dark:bg-gray-800">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($durability as $item)
                    <tr class="group transition hover:bg-gray-50 dark:hover:bg-gray-800/50">

                        {{-- <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->tahun ?? '-' }}
                        </td> --}}

                        <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $item->proyek->nomor_po ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->proyek->customer ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->proyek->nama_proyek ?? '-' }}
                        </td>

                        <td class="max-w-[220px] truncate px-5 py-4 text-gray-600 dark:text-gray-300"
                            title="{{ $item->komponen->produk->nama_produk ?? '-' }}">
                            {{ $item->komponen->produk->nama_produk ?? '-' }}
                        </td>

                        <td class="max-w-[240px] truncate px-5 py-4 text-gray-600 dark:text-gray-300"
                            title="{{ $item->komponen->nama_komponen ?? '-' }}">
                            {{ $item->komponen->nama_komponen ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->trainset->nomor_trainset ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->trainset->tipe_car ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->lokasi->nama_lokasi ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->tgl_kerusakan ? $item->tgl_kerusakan->format('d-m-Y') : '-' }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                            {{ $item->tgl_terbit_lppb ? $item->tgl_terbit_lppb->format('d-m-Y') : '-' }}
                        </td>

                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                                {{ $item->rentang_penggantian !== null ? $item->rentang_penggantian . ' Bulan' : '-' }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-700 dark:bg-orange-900/20 dark:text-orange-300">
                                {{ $item->jumlah_penggantian !== null ? $item->jumlah_penggantian . ' Kali' : '-' }}
                            </span>
                        </td>

                        <td class="sticky right-0 z-10 min-w-[130px] border-l border-gray-100 bg-white px-4 py-4 shadow-[-8px_0_12px_-12px_rgba(0,0,0,0.25)] transition group-hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:group-hover:bg-gray-800">

                            <div class="flex items-center justify-center gap-2">

                                {{-- Detail --}}
                                <a
                                    href="{{ route('durability.show', $item) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-gray-300 hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                    title="Lihat detail"
                                >
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>

                                {{-- Edit --}}
                                <a
                                    href="{{ route('durability.edit', $item) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 shadow-sm transition hover:bg-blue-100 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/30"
                                    title="Edit data"
                                >
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>

                                {{-- Hapus --}}
                                <form
                                    method="POST"
                                    action="{{ route('durability.destroy', $item) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus data durability ini? Data yang sudah dihapus tidak bisa dikembalikan.')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-700 shadow-sm transition hover:bg-red-100 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30"
                                        title="Hapus data"
                                    >
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <img src="{{ asset('img/data-not-found.png') }}" alt="Data tidak ditemukan" class="h-28 w-auto opacity-90">
                                <p class="font-semibold text-gray-500 dark:text-gray-400">
                                    Belum ada data durability produk.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($durability, 'hasPages') && $durability->hasPages())
        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
            {{ $durability->appends(request()->query())->links() }}
        </div>
    @endif
</div>
