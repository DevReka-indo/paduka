<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DurabilityProduk extends Model
{
    use HasFactory;

    protected $table = 'durability_produk';

    protected $fillable = [
        'nama_produk',
    ];

    public function komponen()
    {
        return $this->hasMany(DurabilityKomponen::class, 'produk_id');
    }
}
