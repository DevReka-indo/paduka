<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackPelanggan extends Model
{
    protected $table = 'feedback_pelanggan';

    protected $fillable = [
        'nama_lengkap',
        'perusahaan',
        'jabatan_unit_kerja',
        'proyek',
        'identitas_barang',

        'q1_pengiriman_tepat_waktu',
        'q2_kemudahan_pengoperasian_produk',
        'q3_kemudahan_perawatan',
        'q4_pendampingan_support_trial',
        'q5_responsif_penanganan_complain',
        'q6_teknisi_ramah_sopan',
        'q7_penanganan_complain_tepat_cepat',
        'q8_media_complain_mudah_diakses',
        'q9_produk_sesuai_standar_po',

        'saran_masukan',
        'tanda_tangan',
    ];

    public function getTotalNilaiAttribute()
    {
        return collect([
            $this->q1_pengiriman_tepat_waktu,
            $this->q2_kemudahan_pengoperasian_produk,
            $this->q3_kemudahan_perawatan,
            $this->q4_pendampingan_support_trial,
            $this->q5_responsif_penanganan_complain,
            $this->q6_teknisi_ramah_sopan,
            $this->q7_penanganan_complain_tepat_cepat,
            $this->q8_media_complain_mudah_diakses,
            $this->q9_produk_sesuai_standar_po,
        ])->sum();
    }

    public function getRataRataAttribute()
    {
        return round($this->total_nilai / 9, 2);
    }
}
