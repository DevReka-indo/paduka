<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    protected $fillable = [
        'versi',
        'tipe',
        'deskripsi',
        'tanggal_rilis',
        'is_published',
    ];

    protected $casts = [
        'tanggal_rilis' => 'date',
        'is_published'  => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(ChangelogItem::class)->orderBy('urutan');
    }
}
