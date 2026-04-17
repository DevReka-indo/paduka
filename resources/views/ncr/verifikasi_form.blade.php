@extends('layouts.app')

@section('title', 'Verifikasi NCR')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200">
                <h1 class="text-2xl font-bold text-slate-800">Verifikasi NCR</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Form verifikasi ini hanya diisi oleh pembuat NCR setelah tanggapan unit selesai.
                </p>
            </div>

            <div class="px-6 py-4 bg-sky-50 border-b border-sky-200">
                <p class="text-sm font-semibold text-sky-800">
                    * DIISI OLEH PEMBUAT NCR UNTUK VERIFIKASI HASIL TINDAK LANJUT
                </p>
            </div>

            <div class="px-6 py-5 bg-slate-50 border-b border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500">Nomor NCR</p>
                        <p class="font-semibold text-slate-800">{{ $ncr->nomor_ncr }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Tanggal Masuk</p>
                        <p class="font-semibold text-slate-800">
                            {{ $ncr->tgl_masuk ? \Carbon\Carbon::parse($ncr->tgl_masuk)->format('d-m-Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500">Nama Proses</p>
                        <p class="font-semibold text-slate-800">{{ $ncr->nama_proses ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Proyek</p>
                        <p class="font-semibold text-slate-800">{{ $ncr->project->nama_proyek ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Verifier</p>
                        <p class="font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Unit Tujuan</p>
                        <p class="font-semibold text-slate-800">
                            {{ $ncr->unitKerja->nama_unit ?? $ncr->unit_tujuan ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('ncr.verifikasi.store', $ncr->nomor_ncr) }}" method="POST" class="p-6">
                @csrf

                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <tbody class="divide-y divide-slate-200 bg-white text-sm">
                                <tr>
                                    <td class="w-[32%] px-4 py-4 text-slate-700 font-medium align-top">
                                        Verifikasi atas Tindakan Perbaikan<span class="text-red-500">*</span>
                                    </td>
                                    <td class="w-[4%] px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <textarea
                                            name="verifikasi_qc"
                                            rows="4"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Tuliskan penjelasan hasil verifikasi"
                                        >{{ old('verifikasi_qc', $ncr->verifikasi_qc) }}</textarea>
                                        @error('verifikasi_qc')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">
                                        Tanggal Verifikasi <span class="text-red-500">*</span>
                                    </td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="date"
                                            name="tgl_verifikasi"
                                            value="{{ old('tgl_verifikasi', $ncr->tgl_verifikasi ? \Carbon\Carbon::parse($ncr->tgl_verifikasi)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                            class="w-full max-w-xs rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                        @error('tgl_verifikasi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">
                                        Hasil Verifikasi <span class="text-red-500">*</span>
                                    </td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <select
                                            name="hasil_verifikasi"
                                            class="w-full max-w-xs rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                            <option value="">-- Pilih hasil verifikasi --</option>
                                            <option value="Efektif" {{ old('hasil_verifikasi', $ncr->hasil_verifikasi) == 'Efektif' ? 'selected' : '' }}>
                                                Efektif
                                            </option>
                                            <option value="Tidak Efektif" {{ old('hasil_verifikasi', $ncr->hasil_verifikasi) == 'Tidak Efektif' ? 'selected' : '' }}>
                                                Tidak Efektif
                                            </option>
                                        </select>
                                        @error('hasil_verifikasi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                {{-- <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">
                                        NCR Baru
                                    </td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <input
                                            type="text"
                                            name="ncr_baru"
                                            value="{{ old('ncr_baru', $ncr->ncr_baru) }}"
                                            class="w-full max-w-xs rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Contoh: 0037"
                                        >
                                        @error('ncr_baru')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr> --}}

                                <tr>
                                    <td class="px-4 py-4 text-slate-700 font-medium align-top">
                                        Status Verifikasi <span class="text-red-500">*</span>
                                    </td>
                                    <td class="px-2 py-4 text-center text-slate-500 align-top">:</td>
                                    <td class="px-4 py-4">
                                        <select
                                            name="keterangan"
                                            class="w-full max-w-xs rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                            <option value="">-- Pilih status --</option>
                                            <option value="close" {{ old('keterangan', $ncr->keterangan) == 'close' ? 'selected' : '' }}>
                                                Close
                                            </option>
                                            <option value="open" {{ old('keterangan', $ncr->keterangan) == 'open' ? 'selected' : '' }}>
                                                Open
                                            </option>
                                        </select>

                                        @error('keterangan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror

                                        <p class="mt-2 text-xs text-slate-500">
                                            Pilih <span class="font-medium">Close</span> jika NCR selesai, atau <span class="font-medium">Open</span> jika perlu tindak lanjut lagi.
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
                        Simpan Verifikasi
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
