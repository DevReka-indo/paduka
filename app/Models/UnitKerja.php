<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'deskripsi',
        'keterangan',
    ];

    protected $casts = [
        'keterangan' => 'boolean',
    ];

    /**
     * Relasi ke User — satu unit kerja bisa punya banyak user.
     * Dicocokkan lewat kolom unit_kerja (VARCHAR) di tabel users = kode_unit di tabel ini.
     */
    // public function users()
    // {
    //     return $this->hasMany(User::class, 'unit_kerja', 'nama_unit');
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'unit_kerja_user', 'unit_kerja_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Scope hanya unit yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('keterangan', true);
    }

    public function ncr()
    {
        return $this->hasMany(Ncr::class, 'unit_kerja_id');
    }
}
