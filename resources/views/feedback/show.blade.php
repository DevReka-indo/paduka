@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --bg: #f4f5f7;
        --surface: #ffffff;
        --surface-2: #f9fafb;
        --border: #e5e7eb;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        --accent: #1d4ed8;
        --accent-light: #dbeafe;
        --success: #059669;
        --success-light: #d1fae5;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --warning: #d97706;
        --warning-light: #fef3c7;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow: 0 4px 16px rgba(0,0,0,.06);
        --radius: 10px;
    }

    .fb-wrapper {
        font-family: 'DM Sans', sans-serif;
        background: var(--bg);
        min-height: 100vh;
        padding: 32px 24px;
        color: var(--text-primary);
        max-width: 860px;
        margin: 0 auto;
    }

    /* ── Back + Header ── */
    .fb-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        text-decoration: none;
        margin-bottom: 20px;
        transition: color .15s;
    }
    .fb-back:hover { color: var(--accent); }

    .fb-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .fb-eyebrow {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 4px;
    }

    .fb-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -.02em;
        line-height: 1.25;
    }

    .fb-meta {
        font-size: 13px;
        color: var(--text-secondary);
        margin-top: 4px;
    }

    .fb-header-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-shrink: 0;
    }

    .fb-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all .15s;
        text-decoration: none;
        white-space: nowrap;
    }
    .fb-btn-primary { background: var(--success); color: #fff; box-shadow: var(--shadow-sm); }
    .fb-btn-primary:hover { background: #047857; }
    .fb-btn-ghost { background: var(--surface); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-sm); }
    .fb-btn-ghost:hover { background: var(--surface-2); color: var(--text-primary); }

    /* ── Card ── */
    .fb-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .fb-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        background: var(--surface-2);
        border-bottom: 1px solid var(--border);
    }

    .fb-card-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fb-card-icon.blue  { background: var(--accent-light); color: var(--accent); }
    .fb-card-icon.green { background: var(--success-light); color: var(--success); }

    .fb-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -.01em;
    }

    /* ── Info grid ── */
    .fb-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 0;
    }

    .fb-info-item {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        border-right: 1px solid var(--border);
    }

    .fb-info-item:last-child,
    .fb-info-item:nth-last-child(-n+2):nth-child(odd):last-child { border-bottom: none; }

    .fb-info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .fb-info-value {
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .fb-info-empty { color: var(--text-muted); font-style: italic; }

    /* ── Score table ── */
    .fb-score-table { width: 100%; border-collapse: collapse; }

    .fb-score-table thead {
        background: var(--surface-2);
        border-bottom: 1px solid var(--border);
    }

    .fb-score-table th {
        padding: 10px 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        text-align: left;
    }
    .fb-score-table th:last-child { text-align: center; width: 110px; }

    .fb-score-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .1s;
    }
    .fb-score-table tbody tr:last-child { border-bottom: none; }
    .fb-score-table tbody tr:hover { background: #f8fafd; }

    .fb-score-table td {
        padding: 13px 20px;
        font-size: 13.5px;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .fb-score-table td:first-child {
        width: 36px;
        font-family: 'DM Mono', monospace;
        font-size: 11.5px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .fb-score-table td:last-child { text-align: center; }

    /* ── Star visual ── */
    .fb-stars { display: flex; align-items: center; gap: 3px; justify-content: center; }
    .fb-star { font-size: 14px; }
    .fb-star.on  { color: #f59e0b; }
    .fb-star.off { color: #e5e7eb; }

    .fb-score-num {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        font-family: 'DM Mono', monospace;
        margin-left: 6px;
    }
    .fb-score-4 { background: var(--success-light); color: #065f46; }
    .fb-score-3 { background: #ecfdf5; color: #047857; }
    .fb-score-2 { background: var(--warning-light); color: #92400e; }
    .fb-score-1 { background: var(--danger-light);  color: #991b1b; }

    /* ── Average banner ── */
    .fb-avg-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-top: 1px solid #bfdbfe;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fb-avg-label { font-size: 13px; font-weight: 600; color: var(--accent); }
    .fb-avg-value {
        font-size: 28px;
        font-weight: 700;
        font-family: 'DM Mono', monospace;
        color: var(--accent);
        letter-spacing: -.04em;
    }
    .fb-avg-sub { font-size: 11.5px; color: #3b82f6; margin-top: 2px; }

    .fb-avg-right { text-align: right; }

    /* ── Progress bar ── */
    .fb-progress-wrap { flex: 1; min-width: 160px; }
    .fb-progress-bar-bg {
        height: 8px;
        background: #bfdbfe;
        border-radius: 99px;
        overflow: hidden;
    }
    .fb-progress-bar-fill {
        height: 100%;
        background: var(--accent);
        border-radius: 99px;
        transition: width .4s ease;
    }
    .fb-progress-label {
        font-size: 11px;
        color: #3b82f6;
        margin-top: 5px;
        font-weight: 500;
    }

    /* ── Saran ── */
    .fb-saran-body {
        padding: 18px 20px;
        font-size: 14px;
        color: var(--text-secondary);
        line-height: 1.7;
        font-style: italic;
    }
    .fb-saran-empty { color: var(--text-muted); font-style: italic; font-size: 13.5px; }

    /* ── Tanda tangan ── */
    .fb-ttd-wrap {
        padding: 18px 20px;
    }
    .fb-ttd-img {
        max-width: 220px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
        padding: 6px;
    }

    @media (max-width: 600px) {
        .fb-wrapper { padding: 16px; }
        .fb-header { flex-direction: column; }
        .fb-title { font-size: 20px; }
        .fb-info-grid { grid-template-columns: 1fr; }
    }

    /* ================= DARK MODE ================= */

    .dark .fb-wrapper {
        background: #111827;
        color: #e5e7eb;
    }

    .dark .fb-title,
    .dark .fb-card-title,
    .dark .fb-info-value,
    .dark .fb-score-table td {
        color: #f3f4f6;
    }

    .dark .fb-meta,
    .dark .fb-info-label,
    .dark .fb-score-table th,
    .dark .fb-jabatan {
        color: #9ca3af;
    }

    .dark .fb-card {
        background: #1f2937;
        border-color: #374151;
        box-shadow: none;
    }

    .dark .fb-card-header {
        background: rgba(55, 65, 81, 0.5);
        border-color: #374151;
    }

    .dark .fb-info-item {
        border-color: #374151;
    }

    .dark .fb-score-table thead {
        background: rgba(55, 65, 81, 0.5);
        border-color: #374151;
    }

    .dark .fb-score-table tbody tr {
        border-color: #374151;
    }

    .dark .fb-score-table tbody tr:hover {
        background: rgba(55, 65, 81, 0.4);
    }

    .dark .fb-star.off {
        color: #4b5563;
    }

    /* tombol */
    .dark .fb-btn-ghost {
        background: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }

    .dark .fb-btn-ghost:hover {
        background: #374151;
        color: #fff;
    }

    /* avg banner */
    .dark .fb-avg-banner {
        background: linear-gradient(135deg, #1e3a8a20 0%, #1e40af30 100%);
        border-color: #1e40af40;
    }

    .dark .fb-avg-label,
    .dark .fb-avg-value {
        color: #93c5fd;
    }

    .dark .fb-avg-sub,
    .dark .fb-progress-label {
        color: #60a5fa;
    }

    .dark .fb-progress-bar-bg {
        background: #1e3a8a40;
    }

    .dark .fb-progress-bar-fill {
        background: #3b82f6;
    }

    /* saran */
    .dark .fb-saran-body {
        color: #d1d5db;
    }

    .dark .fb-saran-empty {
        color: #6b7280;
    }

    /* tanda tangan */
    .fb-ttd-img {
        max-width: 220px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #ffffff; /* ini penting */
        padding: 6px;
    }

    /* back link */
    .dark .fb-back {
        color: #9ca3af;
    }

    .dark .fb-back:hover {
        color: #60a5fa;
    }
</style>

<div class="fb-wrapper">

    {{-- Back link --}}
    <a href="{{ route('feedback.index') }}" class="fb-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
        Kembali ke Daftar
    </a>

    {{-- Header --}}
    <div class="fb-header">
        <div>
            <div class="fb-eyebrow">Detail Survei</div>
            <h1 class="fb-title">{{ $feedback->nama_lengkap ?: 'Tanpa Nama' }}</h1>
            <p class="fb-meta">{{ $feedback->perusahaan ?: '' }}{{ $feedback->perusahaan && $feedback->proyek ? ' · ' : '' }}{{ $feedback->proyek ?: '' }}</p>
        </div>
        <div class="fb-header-actions">
            <a href="{{ route('feedback.pdf', $feedback->id) }}" class="fb-btn fb-btn-primary" target="_blank">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/>
                    <polyline points="9,15 12,18 15,15"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Info Pelanggan --}}
    <div class="fb-card">
        <div class="fb-card-header">
            <div class="fb-card-icon blue">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <span class="fb-card-title">Informasi Pelanggan</span>
        </div>
        <div class="fb-info-grid">
            <div class="fb-info-item">
                <div class="fb-info-label">Nama Lengkap</div>
                <div class="fb-info-value">{{ $feedback->nama_lengkap ?: '—' }}</div>
            </div>
            <div class="fb-info-item">
                <div class="fb-info-label">Perusahaan</div>
                <div class="fb-info-value">{{ $feedback->perusahaan ?: '—' }}</div>
            </div>
            <div class="fb-info-item">
                <div class="fb-info-label">Jabatan / Unit Kerja</div>
                <div class="fb-info-value">{{ $feedback->jabatan_unit_kerja ?: '—' }}</div>
            </div>
            <div class="fb-info-item">
                <div class="fb-info-label">Proyek</div>
                <div class="fb-info-value">{{ $feedback->proyek }}</div>
            </div>
            <div class="fb-info-item" style="border-right:none;">
                <div class="fb-info-label">Identitas Barang</div>
                <div class="fb-info-value">{{ $feedback->identitas_barang }}</div>
            </div>
            <div class="fb-info-item" style="border-right:none;border-bottom:none;">
                <div class="fb-info-label">Tanggal Pengisian</div>
                <div class="fb-info-value">{{ $feedback->created_at?->format('d M Y, H:i') ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Penilaian --}}
    @php
        $questions = [
            'q1_pengiriman_tepat_waktu'            => 'Pengiriman tepat waktu',
            'q2_kemudahan_pengoperasian_produk'    => 'Kemudahan pengoperasian produk',
            'q3_kemudahan_perawatan'               => 'Kemudahan perawatan produk',
            'q4_pendampingan_support_trial'        => 'Pendampingan / support saat trial',
            'q5_responsif_penanganan_complain'     => 'Responsif dalam penanganan komplain',
            'q6_teknisi_ramah_sopan'               => 'Teknisi ramah dan sopan',
            'q7_penanganan_complain_tepat_cepat'   => 'Penanganan komplain tepat dan cepat',
            'q8_media_complain_mudah_diakses'      => 'Media komplain mudah diakses',
            'q9_produk_sesuai_standar_po'          => 'Produk sesuai standar / PO',
        ];

        $labels = [1 => 'Kurang', 2 => 'Cukup', 3 => 'Baik', 4 => 'Sangat Baik'];
    @endphp

    <div class="fb-card">
        <div class="fb-card-header">
            <div class="fb-card-icon green">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/>
                </svg>
            </div>
            <span class="fb-card-title">Penilaian Kepuasan (1–4)</span>
        </div>

        <table class="fb-score-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Aspek Penilaian</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $key => $label)
                @php $score = $feedback->$key; @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $label }}</td>
                    <td>
                        <div style="display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;">
                            <div class="fb-stars">
                                @for($s = 1; $s <= 4; $s++)
                                    <span class="fb-star {{ $s <= $score ? 'on' : 'off' }}">★</span>
                                @endfor
                            </div>
                            <span class="fb-score-num fb-score-{{ $score }}">
                                {{ $score }} — {{ $labels[$score] ?? '?' }}
                            </span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Average banner --}}
        @php
            $avg = $feedback->rata_rata;
            $pct = ($avg / 4) * 100;
        @endphp
        <div class="fb-avg-banner">
            <div>
                <div class="fb-avg-label">Rata-rata Keseluruhan</div>
                <div class="fb-avg-sub">dari 9 aspek penilaian</div>
            </div>
            <div class="fb-progress-wrap">
                <div class="fb-progress-bar-bg">
                    <div class="fb-progress-bar-fill" style="width: {{ $pct }}%;"></div>
                </div>
                <div class="fb-progress-label">{{ number_format($pct, 0) }}% dari skor maksimal</div>
            </div>
            <div class="fb-avg-right">
                <div class="fb-avg-value">{{ number_format($avg, 2) }}</div>
                <div class="fb-avg-sub">/ 4.00</div>
            </div>
        </div>
    </div>

    {{-- Saran & Masukan --}}
    <div class="fb-card">
        <div class="fb-card-header">
            <div class="fb-card-icon blue">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <span class="fb-card-title">Saran &amp; Masukan</span>
        </div>
        <div class="fb-saran-body">
            @if($feedback->saran_masukan)
                "{{ $feedback->saran_masukan }}"
            @else
                <span class="fb-saran-empty">Tidak ada saran atau masukan yang disampaikan.</span>
            @endif
        </div>
    </div>

    {{-- Tanda Tangan --}}
    @if($feedback->tanda_tangan)
    <div class="fb-card">
        <div class="fb-card-header">
            <div class="fb-card-icon green">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
            </div>
            <span class="fb-card-title">Tanda Tangan</span>
        </div>
        <div class="fb-ttd-wrap">
            <img src="{{ $feedback->tanda_tangan }}" alt="Tanda Tangan" class="fb-ttd-img">
        </div>
    </div>
    @endif

</div>
@endsection
