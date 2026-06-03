<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Durability extends Model
{
    use HasFactory;

    protected $table = 'durability';

    protected $fillable = [
        'tahun',
        'proyek_id',
        'komponen_id',
        'trainset_id',
        'lokasi_id',
        'tgl_kerusakan',
        'tgl_terbit_lppb',
        'case_keterangan',
        'rentang_penggantian',
        'jumlah_penggantian',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tgl_kerusakan' => 'date',
        'tgl_terbit_lppb' => 'date',
        'rentang_penggantian' => 'integer',
        'jumlah_penggantian' => 'integer',
    ];

    public function proyek()
    {
        return $this->belongsTo(DurabilityProyek::class, 'proyek_id');
    }

    public function komponen()
    {
        return $this->belongsTo(DurabilityKomponen::class, 'komponen_id');
    }

    public function trainset()
    {
        return $this->belongsTo(DurabilityTrainset::class, 'trainset_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(DurabilityLokasi::class, 'lokasi_id');
    }
}
