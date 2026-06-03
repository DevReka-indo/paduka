<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DurabilityKomponen extends Model
{
    use HasFactory;

    protected $table = 'durability_komponen';

    protected $fillable = [
        'produk_id',
        'nama_komponen',
    ];

    public function produk()
    {
        return $this->belongsTo(DurabilityProduk::class, 'produk_id');
    }

    public function durability()
    {
        return $this->hasMany(Durability::class, 'komponen_id');
    }
}
