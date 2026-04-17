<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Temuan extends Model
{
    protected $fillable = [
        'nomor_temuan',
        'status_temuan',
        'detail_temuan',
    ];

    protected static function booted()
    {
        static::creating(function ($temuan) {
            if (empty($temuan->nomor_temuan)) {
                $temuan->nomor_temuan = self::generateNomorTemuan();
            }
        });
    }

    public function ncrs()
    {
        return $this->hasMany(Ncr::class, 'status_temuan', 'status_temuan');
    }

    public static function generateNomorTemuan()
    {
        $tahun = now('Asia/Jakarta')->format('Y');

        $maxNomor = DB::table('temuans')
            ->selectRaw('MAX(RIGHT(nomor_temuan, 4)) as nomor_urut')
            ->whereRaw('LEFT(nomor_temuan, 4) = ?', [$tahun])
            ->value('nomor_urut');

        $kode = $maxNomor
            ? sprintf('%04d', ((int) $maxNomor) + 1)
            : '0001';

        return now('Asia/Jakarta')->format('Ymd') . $kode;
    }

    public static function generateNomorUrutTemuan()
    {
        $tahun = now('Asia/Jakarta')->format('Y');

        $maxNomor = DB::table('temuans')
            ->selectRaw('MAX(RIGHT(nomor_temuan, 4)) as nomor_urut')
            ->whereRaw('LEFT(nomor_temuan, 4) = ?', [$tahun])
            ->value('nomor_urut');

        return $maxNomor
            ? sprintf('%04d', ((int) $maxNomor) + 1)
            : '0001';
    }
}
