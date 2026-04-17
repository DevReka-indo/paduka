@extends('layouts.app')

@section('title', 'Tanggapi NCR')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200">
                <h1 class="text-2xl font-bold text-slate-800">Tanggapi NCR</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Form ini diisi oleh pimpinan unit yang dituju pada NCR.
                </p>
            </div>

            <div class="px-6 py-4 bg-emerald-50 border-b border-emerald-200">
                <p class="text-sm font-semibold text-emerald-800">
                    * DIISI OLEH PIMPINAN UNIT YANG DI NCR (MIN. SUPERVISOR)
                </p>
            </div>

            <form action="{{ route('ncr.tanggapi.store', $ncr->nomor_ncr) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="gambarLama" value="{{ $ncr->up_filee }}">

                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <tbody class="divide-y divide-slate-200 bg-white text-sm">
                                <tr>
                                    <td class="w-[32%] px-4 py-4 text-slate-700 font-medium align-top">Akar Masalah <span class="text-red-500">*</span></td>
                                    <td class="w-[4%] px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="text"
                                            name="akar_masalah"
                                            value="{{ old('akar_masalah', $ncr->akar_masalah) }}"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Contoh: Metode"
                                        >
                                        @error('akar_masalah')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Uraian Akar Masalah <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <textarea
                                            name="uraian_masalah"
                                            rows="3"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Tuliskan uraian akar masalah"
                                        >{{ old('uraian_masalah', $ncr->uraian_masalah) }}</textarea>
                                        @error('uraian_masalah')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Tindakan Perbaikan <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <textarea
                                            name="uraian_perbaikan"
                                            rows="3"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Tuliskan tindakan perbaikan"
                                        >{{ old('uraian_perbaikan', $ncr->uraian_perbaikan) }}</textarea>
                                        @error('uraian_perbaikan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Tindakan Pencegahan <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <textarea
                                            name="uraian_pencegahan"
                                            rows="3"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Tuliskan tindakan pencegahan"
                                        >{{ old('uraian_pencegahan', $ncr->uraian_pencegahan) }}</textarea>
                                        @error('uraian_pencegahan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Kategori Ketidaksesuaian <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <select
                                            name="kategori_masalah"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                            <option value="">-- Pilih kategori --</option>
                                            <option value="Minor" {{ old('kategori_masalah', $ncr->kategori_masalah) == 'Minor' ? 'selected' : '' }}>Minor</option>
                                            <option value="Mayor" {{ old('kategori_masalah', $ncr->kategori_masalah) == 'Mayor' ? 'selected' : '' }}>Mayor</option>
                                            <option value="Observasi" {{ old('kategori_masalah', $ncr->kategori_masalah) == 'Observasi' ? 'selected' : '' }}>Observasi</option>
                                        </select>
                                        @error('kategori_masalah')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Disposisi Unit yang Bertanggung Jawab <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="text"
                                            name="disposisi_unit"
                                            value="{{ old('disposisi_unit', $ncr->disposisi_unit) }}"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Contoh: Repair"
                                        >
                                        @error('disposisi_unit')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Senior Manager / Manager <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="text"
                                            name="senior_manager"
                                            value="{{ old('senior_manager', $ncr->senior_manager) }}"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Masukkan nama manager / senior manager"
                                        >
                                        @error('senior_manager')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Tanggal <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="date"
                                            name="tgl_manager"
                                            value="{{ old('tgl_manager', $ncr->tgl_manager ? \Carbon\Carbon::parse($ncr->tgl_manager)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                            class="w-full max-w-xs rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                        @error('tgl_manager')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Dokumen Lampiran <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="file"
                                            name="up_filee"
                                            accept=".jpg,.jpeg,.png"
                                            class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
                                        >

                                        <p class="mt-2 text-xs text-slate-500">
                                            Format: JPG, JPEG, PNG. Maksimal 4 MB.
                                        </p>

                                        @if(!empty($ncr->up_filee))
                                            <div class="mt-3">
                                                <img
                                                    src="{{ Storage::url($ncr->up_filee) }}"
                                                    alt="Lampiran Tanggapan"
                                                    class="h-36 w-auto rounded-xl border border-slate-200 object-cover"
                                                >
                                            </div>
                                        @endif

                                        @error('up_filee')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Deskripsi Dokumen Lampiran <span class="text-red-500">*</span></td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <textarea
                                            name="doc_lampiran"
                                            rows="3"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Tuliskan deskripsi dokumen lampiran"
                                        >{{ old('doc_lampiran', $ncr->doc_lampiran) }}</textarea>
                                        @error('doc_lampiran')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">Status Tanggapan</td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input type="hidden" name="keterangan" value="process">

                                        <div class="inline-flex items-center gap-2 rounded-lg bg-amber-50 border border-amber-200 px-3 py-2 text-sm text-amber-800">
                                            <span class="font-semibold">Process</span>
                                            <span class="text-xs text-amber-600">(otomatis setelah disimpan)</span>
                                        </div>

                                        <p class="mt-2 text-xs text-slate-500">
                                            Status akan otomatis menjadi <span class="font-medium">Process</span> setelah tanggapan disimpan dan siap diverifikasi.
                                        </p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition"
                    >
                        Simpan Tanggapan
                    </button>

                    <a
                        href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                    >
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
