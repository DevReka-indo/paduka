<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">

<style>
@page {
    size: A4 portrait;
    margin: 15mm 14mm; /* ← ini kunci utama */
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 9pt;
    color: #1a1a2e;
    margin: 0;
}

/* container isi */
.page {
    width: 100%;
    padding: 0 6px; /* ← biar gak nempel tapi aman */
}

/* ─── KOP ─── */
.kop {
    border-bottom: 2px solid #1d4ed8;
    padding-bottom: 8px;
    margin-bottom: 12px;
}

.kop table {
    width: 100%;
    border-collapse: collapse;
}

.kop-logo {
    width: 120px;
    vertical-align: middle;
}

.kop-logo img {
    width: 110px;
    height: auto;
}

.kop-info {
    vertical-align: middle;
    padding-left: 8px;
}

.co-name {
    font-size: 12pt;
    font-weight: bold;
    color: #111827;
    margin-bottom: 2px;
}

.co-sub {
    font-size: 7.5pt;
    color: #374151;
    line-height: 1.4;
}

.logo-box {
    width: 42px;
    height: 42px;
    background: #1d4ed8;
    color: #fff;
    text-align: center;
    line-height: 42px;
    font-weight: bold;
    border-radius: 5px;
}

/* ─── HEADER ─── */
.doc-block {
    background: #1d4ed8;
    color: white;
    margin-bottom: 12px;
    border-radius: 4px;
}
.doc-block table { width: 100%; }

.doc-title { font-size: 11pt; font-weight: bold; }
.doc-sub { font-size: 7pt; color: #bfdbfe; }

.meta {
    font-size: 7pt;
    text-align: right;
    padding-right: 4px;
}

/* ─── SECTION ─── */
.section-title {
    font-size: 7pt;
    font-weight: bold;
    color: #1d4ed8;
    border-left: 3px solid #1d4ed8;
    padding-left: 6px;
    margin: 12px 0 6px;
}

/* ─── INFO ─── */
.info-table {
    width: 100%;
    border-collapse: collapse;
}
.info-table td {
    border: 1px solid #ddd;
    padding: 6px 8px; /* tambah napas */
    font-size: 8pt;
}
.label {
    width: 22%;
    font-weight: bold;
    color: #555;
}

/* ─── TABLE ─── */
.table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.table th {
    background: #1e3a8a;
    color: white;
    font-size: 7pt;
    padding: 7px;
}
.table td {
    border: 1px solid #ddd;
    padding: 7px 8px; /* tambah jarak */
    font-size: 8pt;
    word-wrap: break-word;
}
.center { text-align: center; }

/* ─── BADGE ─── */
.badge {
    padding: 3px 7px;
    border-radius: 10px;
    font-size: 7pt;
    font-weight: bold;
}
.s1 { background:#fee2e2; color:#991b1b; }
.s2 { background:#fef3c7; color:#92400e; }
.s3 { background:#ecfdf5; color:#047857; }
.s4 { background:#d1fae5; color:#065f46; }

/* ─── STAR ─── */
.star-on { color:#f59e0b; }
.star-off { color:#ccc; }

/* ─── AVG ─── */
.avg {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #bfdbfe;
    background: #eff6ff;
    border-radius: 4px;
}
.avg strong { font-size: 14pt; }

/* ─── SARAN ─── */
.saran {
    border: 1px solid #ddd;
    padding: 10px;
    font-style: italic;
    margin-top: 6px;
    border-radius: 4px;
}

/* ─── SIGNATURE ─── */
.ttd {
    margin-top: 25px;
    text-align: right;
}
.ttd img {
    max-height: 60px;
}
.line {
    border-top: 1px solid #ccc;
    margin-top: 6px;
    display: inline-block;
    width: 150px;
    text-align: center;
    font-size: 8pt;
}
</style>
</head>

<body>
<div class="page">

{{-- KOP --}}
<div class="kop">
    <table>
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('img/logo-black.png') }}" alt="Logo">
            </td>
            <td class="kop-info">
                <div class="co-name">PT. Rekaindo Global Jasa</div>
                <div class="co-sub">
                    Jl. Candi Sewu No. 30, Madiun 63122 &bull;
                    0351-4773030 &bull;
                    sekretariat@ptrekaindo.co.id
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- HEADER --}}
<div class="doc-block">
    <table>
        <tr>
            <td style="padding:10px">
                <div class="doc-title">Formulir Kepuasan Pelanggan</div>
                <div class="doc-sub">Customer Satisfaction Survey</div>
            </td>
            <td class="meta" style="padding:10px">
                FB-{{ str_pad($feedback->id, 4, '0', STR_PAD_LEFT) }}<br>
                {{ $feedback->created_at?->format('d/m/Y') }}
            </td>
        </tr>
    </table>
</div>

{{-- INFO --}}
<div class="section-title">Informasi Pelanggan</div>
<table class="info-table">
<tr>
<td class="label">Nama</td>
<td>{{ $feedback->nama_lengkap }}</td>
<td class="label">Perusahaan</td>
<td>{{ $feedback->perusahaan }}</td>
</tr>
<tr>
<td class="label">Jabatan</td>
<td>{{ $feedback->jabatan_unit_kerja }}</td>
<td class="label">Proyek</td>
<td>{{ $feedback->proyek }}</td>
</tr>
<tr>
<td class="label">Barang</td>
<td colspan="3">{{ $feedback->identitas_barang }}</td>
</tr>
</table>

@php
$questions = [
'q1_pengiriman_tepat_waktu'=>'Pengiriman tepat waktu',
'q2_kemudahan_pengoperasian_produk'=>'Kemudahan pengoperasian',
'q3_kemudahan_perawatan'=>'Kemudahan perawatan',
'q4_pendampingan_support_trial'=>'Support trial',
'q5_responsif_penanganan_complain'=>'Responsif komplain',
'q6_teknisi_ramah_sopan'=>'Teknisi ramah',
'q7_penanganan_complain_tepat_cepat'=>'Penanganan cepat',
'q8_media_complain_mudah_diakses'=>'Media komplain',
'q9_produk_sesuai_standar_po'=>'Sesuai PO',
];
$class=[1=>'s1',2=>'s2',3=>'s3',4=>'s4'];
$label=[1=>'Perlu Perbaikan',2=>'Cukup',3=>'Baik',4=>'Sangat Baik'];
@endphp

<div class="section-title">Penilaian</div>

<table class="table">
<thead>
<tr>
<th style="width:5%">No</th>
<th style="width:55%">Aspek</th>
<th style="width:20%">Nilai</th>
<th style="width:20%">Keterangan</th>
</tr>
</thead>

<tbody>
@foreach($questions as $k=>$q)
@php $s=$feedback->$k; @endphp
<tr>
<td class="center">{{ $loop->iteration }}</td>
<td>{{ $q }}</td>
<td class="center">
@for($i=1;$i<=4;$i++)
<span class="{{ $i<=$s?'star-on':'star-off' }}">★</span>
@endfor
<br>{{ $s }}/4
</td>
<td class="center">
<span class="badge {{ $class[$s] }}">{{ $label[$s] }}</span>
</td>
</tr>
@endforeach
</tbody>
</table>

<div class="avg">
Rata-rata: <strong>{{ number_format($feedback->rata_rata,2) }}</strong> / 4
</div>

<div class="section-title">Saran</div>
<div class="saran">
{{ $feedback->saran_masukan ?: 'Tidak ada saran' }}
</div>

{{-- TTD --}}
<table style="width:100%; margin-top:30px;">
    <tr>
        <td style="width:65%"></td>
        <td style="width:35%; text-align:center;">

            @if($feedback->tanda_tangan)
                <img src="{{ $feedback->tanda_tangan }}" style="max-height:70px; margin-bottom:5px;">
            @else
                <div style="height:60px;"></div>
            @endif

            <div style="border-top:1px solid #999; width:160px; margin:5px auto 0;"></div>

            <div style="font-size:9pt; margin-top:4px;">
                {{ $feedback->nama_lengkap ?: 'Pelanggan' }}
            </div>

            <div style="font-size:8pt; color:#666;">
                {{ $feedback->created_at?->format('d M Y') }}
            </div>

        </td>
    </tr>
</table>

</div>

{{-- FOOTER --}}
<div style="
    margin-top:25px;
    padding-top:8px;
    border-top:1px solid #ddd;
    font-size:7pt;
    color:#888;
    text-align:center;
">
    Dokumen ini dicetak otomatis dari sistem PADUKA (PT. Rekaindo Global Jasa) •
    {{ now()->format('d M Y, H:i') }}
</div>
</body>
</html>
