<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DurabilityLokasi extends Model
{
    use HasFactory;

    protected $table = 'durability_lokasi';

    protected $fillable = [
        'nama_lokasi',
    ];

    public function durability()
    {
        return $this->hasMany(Durability::class, 'lokasi_id');
    }
}
