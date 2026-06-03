<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DurabilityProyek extends Model
{
    use HasFactory;

    protected $table = 'durability_proyek';

    protected $fillable = [
        'nomor_po',
        'customer',
        'nama_proyek',
    ];

    public function durability()
    {
        return $this->hasMany(Durability::class, 'proyek_id');
    }
}
