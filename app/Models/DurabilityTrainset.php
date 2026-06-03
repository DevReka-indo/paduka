<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DurabilityTrainset extends Model
{
    use HasFactory;

    protected $table = 'durability_trainset';

    protected $fillable = [
        'nomor_trainset',
        'tipe_car',
    ];

    public function durability()
    {
        return $this->hasMany(Durability::class, 'trainset_id');
    }
}
