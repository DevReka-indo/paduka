@extends('layouts.app')

@section('header')
    Detail Revisi NCR {{ $ncr->nomor_ncr }} — {{ $log->revision }}
@endsection

@section('content')
    @php
        $latestRevision = $revisions->first();
        $latestRevisionIndex = $latestRevision?->revision_index;
        $isCurrentLatest = (int) $log->revision_index === (int) $latestRevisionIndex;
        $changedCount = is_array($log->changes) ? count($log->changes) : 0;

        function isImageValue(?string $value): bool
        {
            if (empty($value)) return false;
            $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
        }
    @endphp

    <style>
        .diff-table {
            width: 100%;
            border-collapse: collapse;
            font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, monospace;
            font-size: 12px;
        }
        .diff-table td {
            padding: 3px 10px;
            vertical-align: top;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .diff-table .ln {
            width: 40px;
            text-align: right;
            color: #6e7781;
            user-select: none;
            border-right: 1px solid #d0d7de;
            background: transparent;
            font-size: 11px;
        }
        .diff-table .sign { width: 18px; padding-left: 6px; }
        .diff-table tr.hunk td { background: #ddf4ff; color: #0969da; font-size: 11px; padding: 3px 12px; }
        .diff-table tr.row-removed td { background: #fff8f8; }
        .diff-table tr.row-removed td.ln { background: #ffd7d5; color: #cf222e; }
        .diff-table tr.row-removed .sign { color: #cf222e; }
        .diff-table tr.row-removed .code { color: #cf222e; background: #ffebe9; }
        .diff-table tr.row-added td { background: #f0fff4; }
        .diff-table tr.row-added td.ln { background: #ccffd8; color: #1a7f37; }
        .diff-table tr.row-added .sign { color: #1a7f37; }
        .diff-table tr.row-added .code { color: #1a7f37; background: #dafbe1; }

        /* Lightbox */
        .lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.85);
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        }
        .lightbox-overlay.active { display: flex; }
        .lightbox-overlay img {
            max-width: 90vw;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
        }
        .lightbox-close {
            position: fixed;
            top: 16px;
            right: 20px;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
            z-index: 10000;
        }

        .timeline-line { position: relative; padding-left: 24px; }
        .timeline-line::before {
            content: '';
            position: absolute;
            left: 6px; top: 10px; bottom: 10px;
            width: 1.5px;
            background: #d0d7de;
        }
        .timeline-dot {
            position: absolute;
            left: -24px; top: 8px;
            width: 14px; height: 14px;
            border-radius: 50%;
            border: 2px solid #d0d7de;
            background: #fff;
        }
        .timeline-dot.active { background: #0969da; border-color: #0969da; }

        /* Dark mode */
        .dark .diff-table .ln { color: #9ca3af; border-right-color: #4b5563; }
        .dark .diff-table tr.hunk td { background: rgba(30,64,175,0.18); color: #93c5fd; }
        .dark .diff-table tr.row-removed td { background: rgba(127,29,29,0.10); }
        .dark .diff-table tr.row-removed td.ln { background: rgba(220,38,38,0.18); color: #fca5a5; }
        .dark .diff-table tr.row-removed .sign { color: #f87171; }
        .dark .diff-table tr.row-removed .code { color: #fecaca; background: rgba(220,38,38,0.14); }
        .dark .diff-table tr.row-added td { background: rgba(20,83,45,0.12); }
        .dark .diff-table tr.row-added td.ln { background: rgba(34,197,94,0.18); color: #86efac; }
        .dark .diff-table tr.row-added .sign { color: #4ade80; }
        .dark .diff-table tr.row-added .code { color: #bbf7d0; background: rgba(34,197,94,0.14); }
        .dark .timeline-line::before { background: #4b5563; }
        .dark .timeline-dot { border-color: #4b5563; background: #111827; }
        .dark .timeline-dot.active { background: #60a5fa; border-color: #60a5fa; }
    </style>

    {{-- Lightbox --}}
    <div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img id="lightbox-img" src="" alt="Preview" onclick="event.stopPropagation()" />
    </div>

    <div class="py-6 max-w-7xl mx-auto space-y-4">

        <a href="{{ route('ncr.show', $ncr->nomor_ncr) }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke detail NCR
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- SIDEBAR --}}
            <div class="space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden lg:sticky lg:top-6">

                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Pilih Revisi</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Lompat ke versi revisi lain</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <select
                            onchange="if(this.value) window.location.href=this.value"
                            class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm py-1.5 px-2.5 text-gray-700 dark:text-gray-200 focus:border-blue-400 dark:focus:border-blue-500 focus:ring-blue-400 dark:focus:ring-blue-500">
                            @foreach ($revisions as $item)
                                <option
                                    value="{{ route('ncr.revision.show', [$ncr->nomor_ncr, $item->revision_index]) }}"
                                    {{ (int) $item->revision_index === (int) $log->revision_index ? 'selected' : '' }}>
                                    {{ $item->revision }} — {{ $item->created_at->format('d-m-Y H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Riwayat Revisi</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Terbaru di atas, klik untuk membuka</p>
                    </div>

                    <div class="p-5">
                        <ol class="timeline-line space-y-4">
                            @foreach ($revisions as $item)
                                @php
                                    $isLatest   = (int) $item->revision_index === (int) $latestRevisionIndex;
                                    $isSelected = (int) $item->revision_index === (int) $log->revision_index;
                                    $itemCount  = is_array($item->changes) ? count($item->changes) : 0;
                                @endphp
                                <li class="relative">
                                    <span class="timeline-dot {{ $isLatest ? 'active' : '' }}"></span>
                                    <a href="{{ route('ncr.revision.show', [$ncr->nomor_ncr, $item->revision_index]) }}"
                                        class="block rounded-lg border px-3 py-2.5 transition-all
                                        {{ $isSelected
                                            ? 'border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20'
                                            : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-200 dark:hover:border-blue-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                                                    <span class="text-sm font-semibold {{ $isSelected ? 'text-blue-700 dark:text-blue-400' : 'text-gray-800 dark:text-gray-100' }}">
                                                        {{ $item->revision }}
                                                    </span>
                                                    @if ($isLatest)
                                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/40 px-1.5 py-0.5 rounded-full">
                                                            <span class="w-1 h-1 bg-green-500 rounded-full"></span>
                                                            aktif
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->created_at->format('d M Y, H:i') }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">oleh {{ $item->user->name ?? '-' }}</p>
                                            </div>
                                            <span class="flex-shrink-0 text-[11px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full whitespace-nowrap">
                                                {{ $itemCount }} field
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                </div>
            </div>

            {{-- PANEL UTAMA --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Commit Header --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $log->revision }}</h2>
                            @if ($isCurrentLatest)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/40 px-2.5 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    aktif
                                </span>
                            @endif
                            <span class="text-xs font-mono bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded">
                                NCR {{ $ncr->nomor_ncr }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-[10px] font-bold flex-shrink-0">
                                {{ strtoupper(substr($log->user->name ?? 'U', 0, 2)) }}
                            </span>
                            <strong class="text-gray-700 dark:text-gray-300">{{ $log->user->name ?? '-' }}</strong>
                            <span>melakukan revisi</span>
                            <span class="ml-auto text-xs text-gray-400 dark:text-gray-500">{{ $log->created_at->format('d-m-Y H:i:s') }}</span>
                        </div>
                    </div>

                    <div class="px-5 py-2.5 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center gap-3 text-sm">
                        <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $changedCount }} field</span>
                        <span class="text-gray-500 dark:text-gray-400">diubah</span>
                        <span class="text-green-600 dark:text-green-400 font-medium">+{{ $changedCount }} nilai baru</span>
                        <span class="text-red-500 dark:text-red-400 font-medium">-{{ $changedCount }} nilai lama</span>
                    </div>

                    <div class="px-5 py-3 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Nomor NCR</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $ncr->nomor_ncr }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Diubah Oleh</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $log->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Waktu Revisi</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $log->created_at->format('d-m-Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Diff blocks per field --}}
                @if (!empty($log->changes) && is_array($log->changes))
                    @foreach ($log->changes as $field => $change)
                        @php
                            // Untuk label teks (ditampilkan di diff teks), pakai old_label/new_label
                            $oldLabel = $change['old_label'] ?? $change['old'] ?? null;
                            $newLabel = $change['new_label'] ?? $change['new'] ?? null;

                            // Untuk deteksi gambar & URL, selalu pakai old/new (path asli di storage)
                            $oldVal   = $change['old'] ?? null;
                            $newVal   = $change['new'] ?? null;

                            $isOldImg = isImageValue($oldVal);
                            $isNewImg = isImageValue($newVal);
                            $isAnyImg = $isOldImg || $isNewImg;
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                            {{-- Field header --}}
                            <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-sm font-semibold text-gray-800 dark:text-gray-100">
                                        {{ $change['label'] ?? $field }}
                                    </span>
                                    @if ($isAnyImg)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/40 px-1.5 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            gambar
                                        </span>
                                    @endif
                                </div>
                                <span class="flex items-center gap-1.5 text-xs font-semibold">
                                    <span class="bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 px-2 py-0.5 rounded">+1</span>
                                    <span class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-2 py-0.5 rounded">-1</span>
                                </span>
                            </div>

                            @if ($isAnyImg)
                                {{-- MODE PREVIEW GAMBAR — sama persis dengan ncr.show --}}
                                <div class="grid grid-cols-2 divide-x divide-gray-200 dark:divide-gray-700">

                                    {{-- Sebelum --}}
                                    <div>
                                        <div class="px-3 py-1.5 bg-red-50/60 dark:bg-red-900/10 border-b border-gray-200 dark:border-gray-700 text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wide">
                                            Sebelum
                                        </div>
                                        <div class="p-4">
                                            @if ($isOldImg && $oldVal)
                                                @php $oldUrl = Storage::disk('public')->url($oldVal); @endphp
                                                <img
                                                    src="{{ $oldUrl }}"
                                                    alt="Gambar sebelum"
                                                    class="w-full max-h-56 object-contain rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 cursor-zoom-in"
                                                    onclick="openLightbox('{{ $oldUrl }}')"
                                                    onerror="this.style.display='none'; document.getElementById('err-old-{{ $loop->index }}').style.display='flex';"
                                                />
                                                <div id="err-old-{{ $loop->index }}"
                                                    style="display:none"
                                                    class="h-24 rounded-xl border border-dashed border-red-200 dark:border-red-800/40 bg-red-50 dark:bg-red-900/10 flex items-center justify-center">
                                                    <p class="text-xs text-red-400 dark:text-red-500">Gambar tidak tersedia</p>
                                                </div>
                                                <p class="mt-2 text-[11px] font-mono text-red-400 dark:text-red-500 truncate">{{ basename($oldVal) }}</p>
                                            @else
                                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">—</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Sesudah --}}
                                    <div>
                                        <div class="px-3 py-1.5 bg-green-50/60 dark:bg-green-900/10 border-b border-gray-200 dark:border-gray-700 text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wide">
                                            Sesudah
                                        </div>
                                        <div class="p-4">
                                            @if ($isNewImg && $newVal)
                                                @php $newUrl = Storage::disk('public')->url($newVal); @endphp
                                                <img
                                                    src="{{ $newUrl }}"
                                                    alt="Gambar sesudah"
                                                    class="w-full max-h-56 object-contain rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 cursor-zoom-in"
                                                    onclick="openLightbox('{{ $newUrl }}')"
                                                    onerror="this.style.display='none'; document.getElementById('err-new-{{ $loop->index }}').style.display='flex';"
                                                />
                                                <div id="err-new-{{ $loop->index }}"
                                                    style="display:none"
                                                    class="h-24 rounded-xl border border-dashed border-green-200 dark:border-green-800/40 bg-green-50 dark:bg-green-900/10 flex items-center justify-center">
                                                    <p class="text-xs text-green-400 dark:text-green-500">Gambar tidak tersedia</p>
                                                </div>
                                                <p class="mt-2 text-[11px] font-mono text-green-500 dark:text-green-400 truncate">{{ basename($newVal) }}</p>
                                            @else
                                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">—</p>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                            @else
                                {{-- MODE TEKS (diff biasa) --}}
                                <div class="grid grid-cols-2 divide-x divide-gray-200 dark:divide-gray-700">
                                    <div>
                                        <div class="px-3 py-1.5 bg-gray-50 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Sebelum
                                        </div>
                                        <table class="diff-table">
                                            <tr class="hunk"><td colspan="3">@@ nilai lama</td></tr>
                                            <tr class="row-removed">
                                                <td class="ln">1</td>
                                                <td class="sign">-</td>
                                                <td class="code">{{ $oldLabel ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div>
                                        <div class="px-3 py-1.5 bg-gray-50 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Sesudah
                                        </div>
                                        <table class="diff-table">
                                            <tr class="hunk"><td colspan="3">@@ nilai baru</td></tr>
                                            <tr class="row-added">
                                                <td class="ln">1</td>
                                                <td class="sign">+</td>
                                                <td class="code">{{ $newLabel ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="h-28 bg-gray-50 dark:bg-gray-900 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center">
                            <p class="text-sm text-gray-400 dark:text-gray-500">Tidak ada detail perubahan untuk revisi ini</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        function openLightbox(url) {
            document.getElementById('lightbox-img').src = url;
            document.getElementById('lightbox').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
            document.getElementById('lightbox-img').src = '';
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
    </script>
@endsection
