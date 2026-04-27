@extends('layouts.app')

@section('header')
    Tanggapi NCR
@endsection

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-4">
    <div class="bg-white border border-gray-200 shadow-sm rounded-sm">
        <div class="px-4 py-3 border-b border-gray-200 bg-blue-50">
            <h3 class="text-sm font-semibold text-blue-700 uppercase">Tanggapi NCR</h3>
        </div>

        <div class="p-4">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded px-4 py-3 text-sm">
                    <div class="font-semibold mb-1">Terdapat kesalahan:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ncr.tanggapi.store', $ncr->nomor_ncr) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Nomor NCR --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor NCR</label>
                        <input type="text"
                               value="{{ $ncr->nomor_ncr }}"
                               readonly
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-gray-100">
                    </div>

                    {{-- Tanggal Masuk --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Masuk</label>
                        <input type="text"
                               value="{{ $ncr->tgl_masuk ? \Carbon\Carbon::parse($ncr->tgl_masuk)->translatedFormat('l, d F Y') : '-' }}"
                               readonly
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-gray-100">
                    </div>

                    {{-- Nama Produk / Proses --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Produk / Proses</label>
                        <input type="text"
                               value="{{ $ncr->nama_proses ?? '-' }}"
                               readonly
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-gray-100">
                    </div>

                    {{-- Proyek --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama / Kode Proyek</label>
                        <input type="text"
                               value="{{ $ncr->project ? (($ncr->project->kode_proyek ?? '') . (($ncr->project->kode_proyek ?? false) && ($ncr->project->nama_proyek ?? false) ? ' - ' : '') . ($ncr->project->nama_proyek ?? '')) : '-' }}"
                               readonly
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-gray-100">
                    </div>

                    {{-- Lokasi Temuan --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Temuan</label>
                        <input type="text"
                               value="{{ $ncr->status_temuan ?? '-' }}"
                               readonly
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-gray-100">
                    </div>

                    {{-- Uraian Ketidaksesuaian --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Uraian Ketidaksesuaian</label>
                        <textarea rows="4"
                                  readonly
                                  class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-gray-100">{{ $ncr->uraian_masalah ?? '-' }}</textarea>
                    </div>

                    {{-- Manager TGP --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Manager TGP</label>
                        <select name="manager_tgp_id"
                                class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">
                            <option value="">-- Pilih Manager TGP --</option>
                            @foreach($pengguna as $item)
                                <option value="{{ $item->id }}"
                                    @selected(old('manager_tgp_id', $ncr->manager_tgp_id) == $item->id)>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori Masalah --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Masalah</label>
                        <input type="text"
                               name="kategori_masalah"
                               value="{{ old('kategori_masalah', $ncr->kategori_masalah) }}"
                               placeholder="Kategori masalah"
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">
                    </div>

                    {{-- Akar Masalah --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Akar Masalah</label>
                        <textarea name="akar_masalah"
                                  rows="4"
                                  placeholder="Jelaskan akar masalah"
                                  class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">{{ old('akar_masalah', $ncr->akar_masalah) }}</textarea>
                    </div>

                    {{-- Uraian Masalah --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Uraian Masalah</label>
                        <textarea name="uraian_masalah"
                                  rows="4"
                                  placeholder="Jelaskan uraian masalah"
                                  class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">{{ old('uraian_masalah', $ncr->uraian_masalah) }}</textarea>
                    </div>

                    {{-- Uraian Perbaikan --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Uraian Perbaikan</label>
                        <textarea name="uraian_perbaikan"
                                  rows="4"
                                  placeholder="Jelaskan tindakan perbaikan"
                                  class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">{{ old('uraian_perbaikan', $ncr->uraian_perbaikan) }}</textarea>
                    </div>

                    {{-- Uraian Pencegahan --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Uraian Pencegahan</label>
                        <textarea name="uraian_pencegahan"
                                  rows="4"
                                  placeholder="Jelaskan tindakan pencegahan"
                                  class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">{{ old('uraian_pencegahan', $ncr->uraian_pencegahan) }}</textarea>
                    </div>

                    {{-- Disposisi Unit --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Disposisi Unit</label>
                        <input type="text"
                               name="disposisi_unit"
                               value="{{ old('disposisi_unit', $ncr->disposisi_unit) }}"
                               placeholder="Disposisi unit"
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">
                    </div>

                    {{-- Senior Manager --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Senior Manager</label>
                        <input type="text"
                               name="senior_manager"
                               value="{{ old('senior_manager', $ncr->senior_manager) }}"
                               placeholder="Nama senior manager"
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">
                    </div>

                    {{-- Tanggal Manager --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Manager</label>
                        <input type="date"
                               name="tgl_managers"
                               value="{{ old('tgl_managers', optional($ncr->tgl_managers)->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">
                    </div>

                    {{-- Dokumen Lampiran --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Dokumen Lampiran</label>
                        <input type="text"
                               name="doc_lampiran"
                               value="{{ old('doc_lampiran', $ncr->doc_lampiran) }}"
                               placeholder="Deskripsi / nama dokumen lampiran"
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm">
                    </div>

                    {{-- Upload Lampiran Tanggapan --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Lampiran Tanggapan</label>
                        <input type="file"
                               name="up_filee"
                               accept=".jpg,.jpeg,.png"
                               class="w-full border border-gray-300 rounded-sm px-2 py-2 text-sm bg-white">

                        @if(!empty($ncr->up_filee) && $ncr->up_filee !== 'gambar_default.png')
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 mb-1">Lampiran saat ini:</p>
                                <img src="{{ asset('gambar/ncr/' . $ncr->up_filee) }}"
                                     alt="Lampiran Tanggapan"
                                     class="h-24 rounded border border-gray-200">
                            </div>
                        @endif
                    </div>

                </div>

                <div class="mt-6 flex items-center gap-2">
                    <a href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-500 hover:bg-gray-600 rounded-sm">
                        ← Kembali
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-sm">
                        ✔ Simpan Tanggapan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
