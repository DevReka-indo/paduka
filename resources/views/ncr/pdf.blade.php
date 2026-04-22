<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>NCR {{ $ncr->nomor_ncr }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 20mm 18mm 20mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9pt;
            color: #1f2937;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        .text-muted {
            color: #6b7280;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: 700;
        }

        .mt-4 {
            margin-top: 4px;
        }

        .mt-6 {
            margin-top: 6px;
        }

        .mt-8 {
            margin-top: 8px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-12 {
            margin-top: 12px;
        }

        .mb-4 {
            margin-bottom: 4px;
        }

        .mb-6 {
            margin-bottom: 6px;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-12 {
            margin-bottom: 12px;
        }

        .logo-area {
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .logo-area img {
            height: 52px;
            width: auto;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: top;
        }

        .header-title {
            font-size: 15pt;
            font-weight: 700;
            color: #1e3a5f;
            margin: 0;
            padding: 0;
        }

        .header-subtitle {
            font-size: 7.5pt;
            color: #6b7280;
            margin-top: 4px;
        }

        .badge-nomor {
            display: inline-block;
            background: #1e3a5f;
            color: #ffffff;
            font-size: 8pt;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
        }

        .badge-status {
            display: inline-block;
            margin-top: 6px;
            font-size: 7pt;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 999px;
        }

        .badge-open {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-process {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-closed {
            background: #dcfce7;
            color: #166534;
        }

        .section {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #eff6ff;
            border-bottom: 1px solid #d1d5db;
            padding: 6px 12px;
        }

        .section-header span {
            font-size: 7.5pt;
            font-weight: 700;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-body {
            padding: 10px 12px;
        }

        .field-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .field-table td {
            vertical-align: top;
            padding: 0 8px 8px 0;
        }

        .field-table td:last-child {
            padding-right: 0;
        }

        .field-label {
            font-size: 6.8pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }

        .field-value {
            font-size: 8.5pt;
            color: #111827;
            word-break: break-word;
        }

        .field-value.multiline {
            white-space: pre-wrap;
            line-height: 1.55;
        }

        .field-empty {
            color: #9ca3af;
            font-style: italic;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 8px 0;
        }

        .badge-inline {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            font-size: 7pt;
            font-family: DejaVu Sans Mono, monospace;
            padding: 2px 5px;
            border-radius: 3px;
            margin-right: 4px;
        }

        .verif-efektif {
            color: #166534;
            font-weight: 700;
        }

        .verif-tidak {
            color: #b91c1c;
            font-weight: 700;
        }

        .qr-section {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .qr-section-header {
            background: #1e3a5f;
            padding: 6px 12px;
        }

        .qr-section-header span {
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .qr-table {
            width: 100%;
            border-collapse: collapse;
        }

        .qr-table td {
            width: 33.33%;
            vertical-align: top;
            text-align: center;
            padding: 12px 10px;
            border-right: 1px solid #e5e7eb;
        }

        .qr-table td:last-child {
            border-right: none;
        }

        .qr-label {
            font-size: 6.8pt;
            font-weight: 700;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .qr-table img {
            width: 88px;
            height: 88px;
            display: block;
            margin: 0 auto 6px auto;
        }

        .qr-name {
            font-size: 7.8pt;
            font-weight: 700;
            color: #111827;
            margin-top: 4px;
        }

        .qr-jabatan {
            font-size: 6.8pt;
            color: #6b7280;
            margin-top: 2px;
        }

        .qr-placeholder {
            width: 140px;
            height: 140px;
            margin: 0 auto 6px auto;
            border: 1px dashed #d1d5db;
            background: #f9fafb;
            color: #9ca3af;
            font-size: 6.5pt;
            line-height: 140px;
            text-align: center;
            border-radius: 4px;
        }

        .doc-wrapper {
            text-align: center;
            margin-top: 8px;
        }

        .doc-wrapper img {
            max-width: 88%;
            max-height: 520px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }

        .doc-empty {
            border: 1px dashed #d1d5db;
            background: #f9fafb;
            color: #9ca3af;
            border-radius: 4px;
            padding: 14px;
            text-align: center;
            font-size: 7.5pt;
            margin-top: 8px;
        }

        .footer {
            width: 100%;
            border-top: 1px solid #e5e7eb;
            margin-top: 14px;
            padding-top: 6px;
            font-size: 6.8pt;
            color: #9ca3af;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: top;
        }

        .form-no-footer {
            position: fixed;
            bottom: -10mm;
            left: -10mm;
            font-size: 6pt;
            color: #374151;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('img/logo-black.png');
        $logoSrc = null;

        if (file_exists($logoPath)) {
            $logoMime = mime_content_type($logoPath);
            $logoSrc = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="logo-area">
        @if ($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo PT Reka Indo">
        @else
            <div style="font-size:11pt;font-weight:700;color:#1e3a5f;">PT Rekaindo Global Jasa</div>
        @endif
    </div>

    <table class="header-table">
        <tr>
            <td style="width:70%;">
                <div class="header-title">NON-CONFORMANCE REPORT</div>
                <div class="header-subtitle">PT Rekaindo Global Jasa — paduka.ptrekaindo.co.id</div>
            </td>
            <td style="width:30%;" class="text-right">
                <div><span class="badge-nomor">{{ $ncr->nomor_ncr }}</span></div>
                <div>
                    @if ($status === 'open')
                        <span class="badge-status badge-open">● OPEN</span>
                    @elseif ($status === 'process')
                        <span class="badge-status badge-process">● PROCESS</span>
                    @elseif (in_array($status, ['close', 'closed']))
                        <span class="badge-status badge-closed">● CLOSED</span>
                    @else
                        <span class="badge-status" style="background:#f3f4f6;color:#6b7280;">-</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-header"><span>Informasi Dasar</span></div>
        <div class="section-body">
            <table class="field-table">
                <tr>
                    <td style="width:50%;">
                        <div class="field-label">Nomor NCR</div>
                        <div class="field-value">{{ $ncr->nomor_ncr ?? '-' }}</div>
                    </td>
                    <td style="width:50%;">
                        <div class="field-label">Tanggal Masuk</div>
                        <div class="field-value">
                            {{ $ncr->tgl_masuk ? \Carbon\Carbon::parse($ncr->tgl_masuk)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="field-label">Nama Inspektor</div>
                        <div class="field-value">{{ $ncr->user->name ?? '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-header"><span>Detail Temuan</span></div>
        <div class="section-body">
            <table class="field-table">
                <tr>
                    <td style="width:50%;">
                        <div class="field-label">Nama Produk / Proses</div>
                        <div class="field-value">{{ $ncr->nama_proses ?? '-' }}</div>
                    </td>
                    <td style="width:50%;">
                        <div class="field-label">Nama / Kode Proyek</div>
                        <div class="field-value">
                            @if ($ncr->project)
                                <span class="badge-inline">{{ $ncr->project->kode_proyek }}</span>
                                {{ $ncr->project->nama_proyek }}
                            @else
                                <span class="field-empty">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">
                        <div class="field-label">Acuan Pemeriksaan</div>
                        <div class="field-value">{{ $ncr->acuan_periksa ?? '-' }}</div>
                    </td>
                    <td style="width:50%;">
                        <div class="field-label">Surat Jalan (S/N)</div>
                        <div class="field-value">{{ $ncr->surat_jalan ?? '-' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="field-label">Lokasi Temuan</div>
                        <div class="field-value">{{ $ncr->status_temuan ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            <div class="divider"></div>

            <table class="field-table" style="margin-bottom:0;">
                <tr>
                    <td>
                        <div class="field-label">Uraian Ketidaksesuaian</div>
                        <div class="field-value multiline">{{ $ncr->uraian ?? '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-header"><span>Penanganan</span></div>
        <div class="section-body">
            <table class="field-table" style="margin-bottom:0;">
                <tr>
                    <td style="width:50%;">
                        <div class="field-label">Person In Charge (PIC)</div>
                        <div class="field-value">{{ $ncr->penanggungJawab->name ?? '-' }}</div>
                    </td>
                    <td style="width:50%;">
                        <div class="field-label">Target Penyelesaian</div>
                        <div class="field-value">
                            {{ $ncr->tgl_target ? \Carbon\Carbon::parse($ncr->tgl_target)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">
                        <div class="field-label">Disposisi Inspektor</div>
                        <div class="field-value">{{ $ncr->disposisi_inspektor ?? '-' }}</div>
                    </td>
                    <td style="width:50%;">
                        <div class="field-label">Unit Yang Dituju</div>
                        <div class="field-value">
                            @if ($ncr->unitKerja)
                                @if ($ncr->unitKerja->kode_unit)
                                    <span class="badge-inline">{{ $ncr->unitKerja->kode_unit }}</span>
                                @endif
                                {{ $ncr->unitKerja->nama_unit }}
                            @elseif ($ncr->unit_tujuan)
                                {{ $ncr->unit_tujuan }}
                            @else
                                <span class="field-empty">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if (in_array($status, ['process', 'close', 'closed']))
        <div class="section">
            <div class="section-header"><span>Hasil Tanggapan Unit</span></div>
            <div class="section-body">
                <table class="field-table">
                    <tr>
                        <td style="width:50%;">
                            <div class="field-label">Akar Masalah</div>
                            <div class="field-value">{{ $ncr->akar_masalah ?? '-' }}</div>
                        </td>
                        <td style="width:50%;">
                            <div class="field-label">Kategori Ketidaksesuaian</div>
                            <div class="field-value">{{ $ncr->kategori_masalah ?? '-' }}</div>
                        </td>
                    </tr>
                </table>

                <div class="divider"></div>

                <table class="field-table">
                    <tr>
                        <td>
                            <div class="field-label">Uraian Akar Masalah</div>
                            <div class="field-value multiline">{{ $ncr->uraian_masalah ?? '-' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="field-label">Tindakan Perbaikan</div>
                            <div class="field-value multiline">{{ $ncr->uraian_perbaikan ?? '-' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="field-label">Tindakan Pencegahan</div>
                            <div class="field-value multiline">{{ $ncr->uraian_pencegahan ?? '-' }}</div>
                        </td>
                    </tr>
                </table>

                <div class="divider"></div>

                <table class="field-table" style="margin-bottom:0;">
                    <tr>
                        <td style="width:50%;">
                            <div class="field-label">Disposisi Unit</div>
                            <div class="field-value">{{ $ncr->disposisi_unit ?? '-' }}</div>
                        </td>
                        <td style="width:50%;">
                            <div class="field-label">Senior Manager / Manager</div>
                            <div class="field-value">{{ $ncr->senior_manager ?? '-' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:50%;">
                            <div class="field-label">Tanggal Tanggapan</div>
                            <div class="field-value">
                                {{ $ncr->tgl_managers ? \Carbon\Carbon::parse($ncr->tgl_managers)->translatedFormat('d F Y') : '-' }}
                            </div>
                        </td>
                        <td style="width:50%;">
                            <div class="field-label">Deskripsi Dokumen Lampiran</div>
                            <div class="field-value">{{ $ncr->doc_lampiran ?? '-' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    @if (!empty($ncr->hasil_verifikasi))
        <div class="section">
            <div class="section-header"><span>Hasil Verifikasi</span></div>
            <div class="section-body">
                <table class="field-table">
                    <tr>
                        <td style="width:50%;">
                            <div class="field-label">Tanggal Verifikasi</div>
                            <div class="field-value">
                                {{ $ncr->tgl_verifikasi ? \Carbon\Carbon::parse($ncr->tgl_verifikasi)->translatedFormat('d F Y') : '-' }}
                            </div>
                        </td>
                        <td style="width:50%;">
                            <div class="field-label">Hasil Verifikasi</div>
                            <div class="field-value">
                                @if ($ncr->hasil_verifikasi === 'Efektif')
                                    <span class="verif-efektif">● EFEKTIF</span>
                                @elseif ($ncr->hasil_verifikasi === 'Tidak Efektif')
                                    <span class="verif-tidak">● TIDAK EFEKTIF</span>
                                @else
                                    {{ $ncr->hasil_verifikasi }}
                                @endif
                            </div>
                        </td>
                    </tr>

                    @if ($ncr->hasil_verifikasi === 'Tidak Efektif' && !empty($ncr->ncr_baru))
                        <tr>
                            <td colspan="2">
                                <div class="field-label">NCR Baru (Tindak Lanjut)</div>
                                <div class="field-value">{{ $ncr->ncr_baru }}</div>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="2">
                            <div class="field-label">Penjelasan Verifikasi</div>
                            <div class="field-value multiline">{{ $ncr->verifikasi_qc ?? '-' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    <div class="qr-section">
        <div class="qr-section-header"><span>Tanda Tangan Digital</span></div>
        <table class="qr-table">
            <tr>
                <td>
                    <div class="qr-label">Pembuat NCR</div>
                    @if ($qrOpen)
                        {{-- <img src="{{ $qrOpen }}" alt="QR Pembuat"> --}}
                        <div style="text-align: center;">
                            <img src="{{ $qrOpen }}" style="width: 180px; height: 180px;" alt="QR Pembuat">
                        </div>
                    @else
                        <div class="qr-placeholder">-</div>
                    @endif
                    <div class="qr-name">{{ $ncr->user->name ?? '-' }}</div>
                    <div class="qr-jabatan">{{ $ncr->user->jabatan ?? '-' }}</div>
                </td>
                <td>
                    <div class="qr-label">Unit Yang dituju</div>
                    @if ($qrProcess)
                        {{-- <img src="{{ $qrProcess }}" alt="QR PIC"> --}}
                        <div style="text-align: center;">
                            <img src="{{ $qrProcess }}" style="width: 180px; height: 180px;" alt="QR PIC">
                        </div>
                    @else
                        <div class="qr-placeholder">Belum Tersedia</div>
                    @endif
                    <div class="qr-name">{{ $ncr->managerTgp->name ?? '-' }}</div>
                    <div class="qr-jabatan">{{ $ncr->managerTgp->jabatan ?? '-' }}</div>
                </td>
                <td>
                    <div class="qr-label">Verifikasi NCR</div>
                    @if ($qrClose)
                        {{-- <img src="{{ $qrClose }}" alt="QR Verifikasi"> --}}
                        <div style="text-align: center;">
                            <img src="{{ $qrClose }}" style="width: 180px; height: 180px;" alt="QR Verifikasi">
                        </div>
                    @else
                        <div class="qr-placeholder">Belum Tersedia</div>
                    @endif
                    <div class="qr-name">{{ $ncr->user->name ?? '-' }}</div>
                    <div class="qr-jabatan">{{ $ncr->user->jabatan ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if (!empty($ncr->doc_pendukung) || !empty($ncr->up_file))
        <div class="section">
            <div class="section-header"><span>Dokumen Pendukung</span></div>
            <div class="section-body">
                @if (!empty($ncr->doc_pendukung))
                    <table class="field-table">
                        <tr>
                            <td>
                                <div class="field-label">Deskripsi Dokumen</div>
                                <div class="field-value">{{ $ncr->doc_pendukung }}</div>
                            </td>
                        </tr>
                    </table>
                @endif

                @if (!empty($ncr->up_file))
                    @php
                        $filePath = storage_path('app/public/' . $ncr->up_file);
                        $imgBase64 = null;

                        if (file_exists($filePath)) {
                            $mime = mime_content_type($filePath);
                            $imgBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                        }
                    @endphp

                    @if ($imgBase64)
                        <div class="doc-wrapper">
                            <img src="{{ $imgBase64 }}" alt="Dokumen Pendukung">
                        </div>
                    @else
                        <div class="doc-empty">File tidak ditemukan di server.</div>
                    @endif
                @else
                    <div class="doc-empty">Belum ada gambar dokumen pendukung.</div>
                @endif
            </div>
        </div>
    @endif

    @if (in_array($status, ['process', 'close', 'closed']))
        @if (!empty($ncr->up_filee) && $ncr->up_filee !== 'gambar_default.png')
            <div class="section">
                <div class="section-header"><span>Dokumen Lampiran Tanggapan</span></div>
                <div class="section-body">
                    @php
                        $filePathE = storage_path('app/public/' . $ncr->up_filee);
                        $imgBase64E = null;

                        if (file_exists($filePathE)) {
                            $mimeE = mime_content_type($filePathE);
                            $imgBase64E = 'data:' . $mimeE . ';base64,' . base64_encode(file_get_contents($filePathE));
                        }
                    @endphp

                    @if ($imgBase64E)
                        <div class="doc-wrapper">
                            <img src="{{ $imgBase64E }}" alt="Dokumen Lampiran Tanggapan">
                        </div>
                    @else
                        <div class="doc-empty">File tidak ditemukan di server.</div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <div class="form-no-footer">
        Form No. : IV-QCAS.003. Rev. B
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width:60%;">
                    Dicetak: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}
                    WIB
                    • paduka.ptrekaindo.co.id
                </td>
                <td style="width:40%;" class="text-right">
                    Dokumen ini digenerate secara otomatis oleh sistem.
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
