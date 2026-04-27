@extends('layouts.app')

@section('header')
    Bantuan & Informasi
@endsection

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 w-full max-w-[1600px] mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-gray-100">Bantuan & Informasi</h1>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">
                Panduan penggunaan sistem, dokumentasi, dan riwayat pembaruan.
            </p>
        </div>

        {{-- Tab Navigation --}}
        <div x-data="{ tab: 'panduan' }">

            <div class="flex flex-wrap gap-1 border-b border-slate-200 dark:border-gray-700">
                <button @click="tab = 'panduan'"
                    :class="tab === 'panduan'
                        ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400'
                        : 'text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200'"
                    class="px-4 py-2.5 text-sm font-medium transition-colors duration-150 -mb-px">
                    Panduan
                </button>

                <button @click="tab = 'dokumentasi'"
                    :class="tab === 'dokumentasi'
                        ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400'
                        : 'text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200'"
                    class="px-4 py-2.5 text-sm font-medium transition-colors duration-150 -mb-px">
                    Dokumentasi
                </button>

                <button @click="tab = 'changelog'"
                    :class="tab === 'changelog'
                        ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400'
                        : 'text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200'"
                    class="px-4 py-2.5 text-sm font-medium transition-colors duration-150 -mb-px">
                    Update Log
                </button>
            </div>

            {{-- Tab: Panduan --}}
            <div x-show="tab === 'panduan'" x-transition class="pt-6 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Card: User Manual --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-5 flex items-start gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-gray-100">User Manual Sistem</p>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                                Panduan lengkap penggunaan sistem PADUKA untuk semua role.
                            </p>
                            <a href="{{ asset('storage/dokumen/FULL.pdf') }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 mt-3 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>

                    {{-- Card: Panduan NCR --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-5 flex items-start gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-gray-100">Panduan Proses NCR</p>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                                Alur registrasi, tanggapan, dan verifikasi NCR secara lengkap.
                            </p>
                            <a href="#"
                                class="inline-flex items-center gap-1.5 mt-3 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                {{-- FAQ --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-5">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-gray-100 mb-4">Pertanyaan Umum (FAQ)</h3>

                    <div x-data="{ open: null }" class="space-y-2">
                        @foreach ([
                            ['q' => 'Bagaimana cara membuat NCR baru?', 'a' => 'Klik menu NCR pada sidebar, lalu pilih Registrasi NCR. Pilih "Tambah NCR". Isi data NCR pada form yang tersedia.'],
                            ['q' => 'Siapa yang bisa melakukan verifikasi NCR?', 'a' => 'Verifikasi NCR hanya dapat dilakukan oleh Inspektor QC / user pembuat NCR.'],
                            ['q' => 'Bagaimana cara melihat riwayat perubahan NCR?', 'a' => 'Buka halaman detail NCR, jika terdapat revisi maka akan muncul versi revisi di sebelah nomor NCR. Klik pada versi tersebut untuk masuk ke halaman detail revisi NCR'],
                            ['q' => 'Bagaimana cara mengubah password akun PADUKA?', 'a' => 'Masuk ke menu profile dengan mengklik nama pengguna di pojok kanan atas, lalu pilih "Profile". Pada halaman profile, klik tombol "Ubah Password" untuk mengakses form perubahan password.'],
                            ['q' => 'Apa yang harus dilakukan jika menemukan bug atau masalah pada sistem?', 'a' => 'Silakan laporkan bug atau masalah yang ditemukan kepada tim IT melalui email atau fitur pelaporan yang tersedia di sistem. Sertakan detail langkah-langkah untuk mereproduksi masalah, tangkapan layar jika perlu, dan informasi lainnya yang dapat membantu tim IT dalam menyelesaikan masalah tersebut.'],
                        ] as $i => $faq)
                            <div class="border border-slate-100 dark:border-gray-700 rounded-xl overflow-hidden">
                                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors text-left">
                                    <span>{{ $faq['q'] }}</span>
                                    <svg class="w-4 h-4 flex-shrink-0 text-slate-400 transition-transform duration-200"
                                        :class="open === {{ $i }} ? 'rotate-180' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open === {{ $i }}" x-collapse>
                                    <p class="px-4 py-3 text-sm text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-gray-700">
                                        {{ $faq['a'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tab: Dokumentasi --}}
            <div x-show="tab === 'dokumentasi'" x-transition class="pt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ([
                        ['judul' => 'SOP NCR', 'desc' => 'Standar Operasional Prosedur penanganan Non-Conformance Report.', 'warna' => 'indigo'],
                        ['judul' => 'Kebijakan Mutu', 'desc' => 'Dokumen kebijakan mutu perusahaan yang menjadi acuan sistem.', 'warna' => 'emerald'],
                        ['judul' => 'Instruksi Kerja', 'desc' => 'Instruksi kerja teknis untuk tiap divisi terkait proses NCR.', 'warna' => 'amber'],
                    ] as $doc)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl mb-3
                                {{ $doc['warna'] === 'indigo' ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600' : '' }}
                                {{ $doc['warna'] === 'emerald' ? 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600' : '' }}
                                {{ $doc['warna'] === 'amber' ? 'bg-amber-50 dark:bg-amber-900/40 text-amber-600' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            <p class="text-sm font-semibold text-slate-800 dark:text-gray-100">{{ $doc['judul'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $doc['desc'] }}</p>

                            <a href="#"
                                class="inline-flex items-center gap-1.5 mt-3 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab: Changelog --}}
            <div x-show="tab === 'changelog'" x-transition class="pt-6">
                @if ($changelogs->count())
                    <div class="relative space-y-0">
                        @foreach ($changelogs as $i => $log)
                            @php
                                $badgeClass = match($log->tipe) {
                                    'feature'     => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
                                    'improvement' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                    'release'     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                    'fix'         => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                    default       => 'bg-slate-100 text-slate-600',
                                };

                                $badgeLabel = match($log->tipe) {
                                    'feature'     => 'Fitur Baru',
                                    'improvement' => 'Peningkatan',
                                    'release'     => 'Rilis',
                                    'fix'         => 'Perbaikan',
                                    default       => ucfirst($log->tipe),
                                };
                            @endphp

                            <div class="flex gap-4 pb-6 relative">
                                @if (!$loop->last)
                                    <div class="absolute left-[19px] top-10 bottom-0 w-px bg-slate-200 dark:bg-gray-700"></div>
                                @endif

                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                    {{ $i === 0 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-indigo-900' : 'bg-white dark:bg-gray-800 border-2 border-slate-200 dark:border-gray-600 text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($i === 0)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                </div>

                                <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <span class="text-sm font-bold text-slate-800 dark:text-gray-100">{{ $log->versi }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass }}">{{ $badgeLabel }}</span>

                                        @if ($i === 0)
                                            <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-indigo-600 text-white">Terbaru</span>
                                        @endif

                                        <span class="text-xs text-slate-400 dark:text-gray-500 ml-auto">
                                            {{ $log->tanggal_rilis?->format('d M Y') }}
                                        </span>
                                    </div>

                                    @if (!empty($log->deskripsi))
                                        <p class="text-sm text-slate-500 dark:text-gray-400 mb-3">
                                            {{ $log->deskripsi }}
                                        </p>
                                    @endif

                                    <ul class="space-y-1.5">
                                        @forelse ($log->items as $item)
                                            <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-gray-300">
                                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-gray-600 flex-shrink-0"></span>
                                                {{ $item->isi }}
                                            </li>
                                        @empty
                                            <li class="text-sm text-slate-400 dark:text-gray-500">
                                                Tidak ada poin perubahan.
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-8 text-center">
                        <p class="text-sm text-slate-500 dark:text-gray-400">
                            Belum ada update log yang dipublikasikan.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer info --}}
        <div class="text-center text-xs text-slate-400 dark:text-gray-600 pt-2 pb-4">
            Sistem PADUKA &mdash; {{ config('app.name') }} &bull; Hubungi administrator untuk bantuan lebih lanjut.
        </div>

    </div>
@endsection
