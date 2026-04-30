@extends('layouts.survey')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', sans-serif;
        color: #1C1C1C;
        -webkit-font-smoothing: antialiased;
    }

    .page-wrap { width: 100%; }

    /* ── Hero Header ── */
    .survey-hero {
        background: #1E1A5E;
        border-radius: 20px;
        padding: 2.5rem 2.5rem 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .survey-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .survey-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: 30%;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.03);
    }
    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.1);
        border: 0.5px solid rgba(255,255,255,0.15);
        border-radius: 100px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.7);
        margin-bottom: 1rem;
    }
    .hero-eyebrow span {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #7B72F0;
        display: inline-block;
    }
    .survey-hero h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 28px;
        font-weight: 400;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 1rem;
    }
    .survey-hero p {
        font-size: 14px;
        color: rgba(255,255,255,0.6);
        line-height: 1.7;
        max-width: 520px;
    }
    .hero-logo {
        position: absolute;
        top: 1.75rem;
        right: 2rem;
        height: 36px;
        width: auto;
        object-fit: contain;
        opacity: 0.9;
        filter: brightness(0) invert(1);
        z-index: 2;
    }

    /* ── Alert ── */
    .alert-success {
        background: #EAF3DE;
        color: #3B6D11;
        border: 0.5px solid #C0DD97;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 14px;
        margin-bottom: 1.25rem;
    }

    /* ── Card ── */
    .card {
        background: #fff;
        border-radius: 18px;
        border: 0.5px solid #E3E0D9;
        padding: 2rem;
        margin-bottom: 1.25rem;
    }
    .card-label {
        font-size: 10.5px;
        font-weight: 600;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: #B0ACA4;
        margin-bottom: 1.5rem;
    }

    /* ── Fields ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .form-grid .span-2 { grid-column: 1 / -1; }

    .field { display: flex; flex-direction: column; gap: 6px; }
    .field > label {
        font-size: 12.5px;
        font-weight: 500;
        color: #5A5754;
    }
    .field > label .req { color: #D44; margin-left: 2px; }

    .field input,
    .field textarea {
        background: #FAFAF8;
        border: 0.5px solid #E3E0D9;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: #1C1C1C;
        transition: border-color 0.15s, background 0.15s;
        outline: none;
        width: 100%;
    }
    .field input:focus,
    .field textarea:focus {
        border-color: #4A3FD4;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(74,63,212,0.07);
    }
    .field input::placeholder,
    .field textarea::placeholder { color: #C8C5BF; }
    .field textarea {
        resize: vertical;
        min-height: 100px;
        line-height: 1.6;
    }

    /* ── Rating Legend Table ── */
    .rating-legend {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 0.25rem;
    }
    .legend-item {
        background: #F7F6F2;
        border: 0.5px solid #E3E0D9;
        border-radius: 10px;
        padding: 12px 8px;
        text-align: center;
    }
    .legend-score {
        font-size: 22px;
        font-weight: 600;
        color: #4A3FD4;
        line-height: 1;
        margin-bottom: 4px;
    }
    .legend-label {
        font-size: 11.5px;
        color: #888;
        line-height: 1.3;
    }

    /* ── Questions ── */
    .question-item {
        padding: 1.1rem 0;
        border-bottom: 0.5px solid #F0EDE7;
    }
    .question-item:last-child { border-bottom: none; padding-bottom: 0; }

    .q-label {
        font-size: 14px;
        color: #1C1C1C;
        margin-bottom: 0.9rem;
        line-height: 1.55;
    }
    .q-num {
        display: inline-block;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #ECEAFC;
        color: #4A3FD4;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
        line-height: 22px;
        margin-right: 8px;
        flex-shrink: 0;
    }
    .q-label-inner { display: flex; align-items: flex-start; gap: 0; }

    /* ── Radio tiles ── */
    .radio-group {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 7px;
    }
    .radio-tile { position: relative; }
    .radio-tile input[type="radio"] {
        position: absolute; opacity: 0; width: 0; height: 0;
    }
    .radio-tile label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        padding: 10px 6px 9px;
        border-radius: 10px;
        border: 0.5px solid #E3E0D9;
        background: #FAFAF8;
        transition: all 0.15s;
        text-align: center;
        user-select: none;
    }
    .tile-dot {
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 1.5px solid #CCCAC4;
        background: #fff;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .tile-score {
        font-size: 15px;
        font-weight: 600;
        color: #C8C5BF;
        transition: color 0.15s;
        line-height: 1;
    }
    .tile-text {
        font-size: 11px;
        color: #999;
        line-height: 1.3;
        transition: color 0.15s;
    }
    .radio-tile input:checked + label {
        border-color: #4A3FD4;
        background: #F0EEFF;
    }
    .radio-tile input:checked + label .tile-dot {
        background: #4A3FD4;
        border-color: #4A3FD4;
        box-shadow: inset 0 0 0 3px #fff;
    }
    .radio-tile input:checked + label .tile-score { color: #4A3FD4; }
    .radio-tile input:checked + label .tile-text  { color: #4A3FD4; }
    .radio-tile label:hover {
        border-color: #A29FEA;
        background: #F5F4FF;
    }

    /* ── E-Signature ── */
    .sig-wrap {
        position: relative;
        border: 0.5px solid #E3E0D9;
        border-radius: 12px;
        background: #FAFAF8;
        overflow: hidden;
    }
    .sig-canvas {
        display: block;
        width: 100%;
        height: 180px;
        cursor: crosshair;
        touch-action: none;
    }
    .sig-placeholder {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-size: 13px;
        color: #C8C5BF;
        pointer-events: none;
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .sig-placeholder svg { opacity: 0.5; }
    .sig-baseline {
        position: absolute;
        bottom: 44px; left: 20px; right: 20px;
        height: 0.5px;
        background: #E3E0D9;
        pointer-events: none;
    }
    .sig-clear {
        position: absolute;
        bottom: 10px; right: 14px;
        background: none;
        border: 0.5px solid #E3E0D9;
        border-radius: 7px;
        padding: 5px 12px;
        font-size: 12px;
        font-family: 'DM Sans', sans-serif;
        color: #888;
        cursor: pointer;
        transition: all 0.15s;
    }
    .sig-clear:hover {
        background: #FEF0F0;
        border-color: #E24B4A;
        color: #E24B4A;
    }
    #sig_data { display: none; }

    /* ── Submit ── */
    .submit-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 0.5px solid #F0EDE7;
    }
    .submit-note { font-size: 12px; color: #B0ACA4; }
    .btn-submit {
        background: #1E1A5E;
        color: #fff;
        border: none;
        padding: 13px 36px;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
        letter-spacing: 0.01em;
    }
    .btn-submit:hover  { background: #2D2880; }
    .btn-submit:active { transform: scale(0.98); }

    /* ── Responsive ── */
    @media (max-width: 540px) {
        .survey-hero { padding: 1.75rem 1.5rem; }
        .card { padding: 1.5rem; }
        .form-grid { grid-template-columns: 1fr; }
        .radio-group { grid-template-columns: repeat(2, 1fr); }
        .rating-legend { grid-template-columns: repeat(2, 1fr); }
    }

    .field select {
        background: #FAFAF8;
        border: 0.5px solid #E3E0D9;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: #1C1C1C;
        width: 100%;

        /* samakan behavior */
        outline: none;
        transition: border-color 0.15s, background 0.15s;

        /* hilangkan style bawaan */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;

        /* IMPORTANT: fix tinggi biar sama */
        height: 44px;

        /* arrow custom */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M5 7l5 5 5-5' stroke='%23999' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;

        /* spacing kanan biar ga ketiban arrow */
        padding-right: 36px;
    }

    .field select:focus {
        border-color: #4A3FD4;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(74,63,212,0.07);
    }

    .field select:disabled {
        color: #B0ACA4;
        cursor: not-allowed;
    }
</style>
<div class="page-wrap">

    {{-- Hero --}}
    <div class="survey-hero">
        <img src="{{ asset('img/logo-black.png') }}" alt="Logo PT. Rekaindo Global Jasa" class="hero-logo">
        {{-- <div class="hero-eyebrow"><span></span> PT. Rekaindo Global Jasa</div> --}}
        <h1>Form Kepuasan Pelanggan</h1>
        <p>Terimakasih atas partisipasi Anda untuk mengisi Form Kepuasan Pelanggan ini yang akan digunakan untuk meningkatkan pelayanan dan kinerja bagi perusahaan PT. REKAINDO GLOBAL JASA.</p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('feedback.store') }}">
        @csrf

        {{-- ── Data Pelanggan ── --}}
        <div class="card">
            <div class="card-label">Data Pelanggan</div>
            <div class="form-grid">
                <div class="field">
                    <label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap"
                        placeholder="Nama lengkap Anda"
                        value="{{ old('nama_lengkap') }}" required>
                </div>
                <div class="field">
                    <label for="perusahaan">Perusahaan</label>
                    <input type="text" id="perusahaan" name="perusahaan"
                        placeholder="Nama perusahaan"
                        value="{{ old('perusahaan') }}">
                </div>
                <div class="field">
                    <label for="jabatan_unit_kerja">Jabatan / Unit Kerja</label>
                    <input type="text" id="jabatan_unit_kerja" name="jabatan_unit_kerja"
                        placeholder="Contoh: Manajer Operasional"
                        value="{{ old('jabatan_unit_kerja') }}">
                </div>

                <div class="field">
                    <label for="feedback_project_id">
                        Proyek <span class="req">*</span>
                    </label>

                    <select id="feedback_project_id"
                            name="feedback_project_id"
                            required>
                        <option value="">-- Pilih Proyek --</option>

                        @foreach($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ old('feedback_project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->nama_project }}
                            </option>
                        @endforeach
                    </select>

                    @error('feedback_project_id')
                        <span style="color:#D44;font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field span-2">
                    <label for="feedback_project_item_id">
                        Identitas Barang / Produk REKA <span class="req">*</span>
                    </label>

                    <select id="feedback_project_item_id"
                            name="feedback_project_item_id"
                            required
                            disabled>
                        <option value="">-- Pilih proyek terlebih dahulu --</option>
                    </select>

                    @error('feedback_project_item_id')
                        <span style="color:#D44;font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Kuesioner Penilaian ── --}}
        @php
            $pertanyaan = [
                'q1_pengiriman_tepat_waktu'            => 'Pengiriman barang tepat waktu',
                'q2_kemudahan_pengoperasian_produk'    => 'Kemudahan cara pengoperasian produk',
                'q3_kemudahan_perawatan'               => 'Kemudahan perawatan selama pemakaian',
                'q4_pendampingan_support_trial'        => 'Pendampingan / Support teknis trial produk',
                'q5_responsif_penanganan_complain'     => 'Responsif dalam penanganan complain',
                'q6_teknisi_ramah_sopan'               => 'Teknisi REKA ramah dan sopan dalam penyelesaian pekerjaan',
                'q7_penanganan_complain_tepat_cepat'   => 'Teknisi REKA dalam penanganan complain sudah tepat dan cepat',
                'q8_media_complain_mudah_diakses'      => 'Media penyampaian complain mudah diakses (email / telp / WA)',
                'q9_produk_sesuai_standar_po'          => 'Produk yang dihasilkan sudah sesuai standart PO (Purchase Order)',
            ];
        @endphp

        <div class="card">
            <div class="card-label">Kategori Penilaian</div>

            {{-- Legend --}}
            <div class="rating-legend" style="margin-bottom: 2rem;">
                @foreach([1 => 'Perlu Perbaikan', 2 => 'Cukup', 3 => 'Baik', 4 => 'Sangat Baik'] as $score => $desc)
                    <div class="legend-item">
                        <div class="legend-score">{{ $score }}</div>
                        <div class="legend-label">{{ $desc }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Questions --}}
            @foreach($pertanyaan as $name => $label)
                <div class="question-item">
                    <p class="q-label">
                        <span class="q-num">{{ $loop->iteration }}</span>{{ $label }}
                    </p>
                    <div class="radio-group">
                        @foreach([1 => 'Perlu Perbaikan', 2 => 'Cukup', 3 => 'Baik', 4 => 'Sangat Baik'] as $val => $text)
                            <div class="radio-tile">
                                <input type="radio"
                                    name="{{ $name }}"
                                    id="{{ $name }}_{{ $val }}"
                                    value="{{ $val }}"
                                    required
                                    {{ old($name) == $val ? 'checked' : '' }}>
                                <label for="{{ $name }}_{{ $val }}">
                                    <span class="tile-dot"></span>
                                    <span class="tile-score">{{ $val }}</span>
                                    <span class="tile-text">{{ $text }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Saran & Tanda Tangan ── --}}
        <div class="card">
            <div class="card-label">Saran & Tanda Tangan</div>

            <div class="field" style="margin-bottom: 1.5rem;">
                <label for="saran_masukan">Saran / Masukan <span class="req">*</span></label>
                <textarea id="saran_masukan" name="saran_masukan"
                    placeholder="Tuliskan saran atau masukan Anda di sini...">{{ old('saran_masukan') }}</textarea>
            </div>

            <div class="field">
                <label>Tanda Tangan <span class="req">*</span></label>
                <div class="sig-wrap">
                    <canvas id="sig-canvas" class="sig-canvas"></canvas>
                    <div class="sig-baseline"></div>
                    <div class="sig-placeholder" id="sig-placeholder">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                        Tanda tangani di sini
                    </div>
                    <button type="button" class="sig-clear" id="sig-clear">Hapus</button>
                </div>
                <input type="hidden" name="tanda_tangan" id="sig_data">
            </div>

            <div class="submit-row">
                <span class="submit-note"><span style="color:#D44">*</span> Wajib diisi</span>
                <button type="submit" class="btn-submit">Kirim Penilaian</button>
            </div>
        </div>

    </form>
</div>

<script>
(function () {
    const canvas    = document.getElementById('sig-canvas');
    const ctx       = canvas.getContext('2d');
    const clearBtn  = document.getElementById('sig-clear');
    const hidden    = document.getElementById('sig_data');
    const hint      = document.getElementById('sig-placeholder');
    let drawing     = false;
    let hasSig      = false;

    function resize() {
        const ratio = window.devicePixelRatio || 1;
        const rect  = canvas.getBoundingClientRect();
        canvas.width  = rect.width  * ratio;
        canvas.height = rect.height * ratio;
        ctx.scale(ratio, ratio);
        ctx.strokeStyle = '#1C1C1C';
        ctx.lineWidth   = 2;
        ctx.lineCap     = 'round';
        ctx.lineJoin    = 'round';
    }

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const src  = e.touches ? e.touches[0] : e;
        return { x: src.clientX - rect.left, y: src.clientY - rect.top };
    }

    function start(e) {
        e.preventDefault();
        drawing = true;
        const { x, y } = getPos(e);
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function draw(e) {
        if (!drawing) return;
        e.preventDefault();
        const { x, y } = getPos(e);
        ctx.lineTo(x, y);
        ctx.stroke();
        if (!hasSig) {
            hasSig = true;
            hint.style.opacity = '0';
        }
    }

    function stop(e) {
        if (!drawing) return;
        drawing = false;
        ctx.closePath();
        hidden.value = canvas.toDataURL('image/png');
    }

    canvas.addEventListener('mousedown',  start);
    canvas.addEventListener('mousemove',  draw);
    canvas.addEventListener('mouseup',    stop);
    canvas.addEventListener('mouseleave', stop);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove',  draw,  { passive: false });
    canvas.addEventListener('touchend',   stop);

    clearBtn.addEventListener('click', function () {
        const rect = canvas.getBoundingClientRect();
        ctx.clearRect(0, 0, rect.width, rect.height);
        hidden.value = '';
        hasSig = false;
        hint.style.opacity = '1';
    });

    resize();
    window.addEventListener('resize', resize);
})();

const projectItems = @json(
    $projects->mapWithKeys(function ($project) {
        return [
            $project->id => $project->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                ];
            })->values()
        ];
    })
);

const oldProjectId = "{{ old('feedback_project_id') }}";
const oldItemId = "{{ old('feedback_project_item_id') }}";

function renderProjectItems(projectId, selectedItemId = null) {
    const itemSelect = document.getElementById('feedback_project_item_id');
    const items = projectItems[projectId] || [];

    itemSelect.innerHTML = '';

    if (!projectId) {
        itemSelect.disabled = true;
        itemSelect.innerHTML = '<option value="">-- Pilih proyek terlebih dahulu --</option>';
        return;
    }

    if (items.length === 0) {
        itemSelect.disabled = true;
        itemSelect.innerHTML = '<option value="">Belum ada barang untuk proyek ini</option>';
        return;
    }

    itemSelect.disabled = false;
    itemSelect.innerHTML = '<option value="">-- Pilih Barang / Produk --</option>';

    items.forEach(function (item) {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nama_barang;

        if (String(selectedItemId) === String(item.id)) {
            option.selected = true;
        }

        itemSelect.appendChild(option);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const projectSelect = document.getElementById('feedback_project_id');

    renderProjectItems(oldProjectId || projectSelect.value, oldItemId);

    projectSelect.addEventListener('change', function () {
        renderProjectItems(this.value);
    });
});
</script>
@endsection
