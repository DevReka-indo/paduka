<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $fillable = [
        'nomor_proyek',
        'kode_proyek',
        'nama_proyek',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->nomor_proyek)) {
                $project->nomor_proyek = self::generateNomorProyek();
            }
        });
    }

    public function ncrs()
    {
        return $this->hasMany(Ncr::class, 'kode_proyek', 'kode_proyek');
    }

    public static function generateNomorProyek()
    {
        $tahun = now('Asia/Jakarta')->format('Y');

        $maxNomor = DB::table('projects')
            ->selectRaw('MAX(RIGHT(nomor_proyek, 4)) as nomor_urut')
            ->whereRaw('LEFT(nomor_proyek, 4) = ?', [$tahun])
            ->value('nomor_urut');

        $kode = $maxNomor
            ? sprintf('%04d', ((int) $maxNomor) + 1)
            : '0001';

        return now('Asia/Jakarta')->format('Ymd') . $kode;
    }

    public static function generateNomorUrutProyek()
    {
        $tahun = now('Asia/Jakarta')->format('Y');

        $maxNomor = DB::table('projects')
            ->selectRaw('MAX(RIGHT(nomor_proyek, 4)) as nomor_urut')
            ->whereRaw('LEFT(nomor_proyek, 4) = ?', [$tahun])
            ->value('nomor_urut');

        return $maxNomor
            ? sprintf('%04d', ((int) $maxNomor) + 1)
            : '0001';
    }
}
