<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackProject extends Model
{
    protected $fillable = [
        'nama_project',
        'deskripsi',
        'is_active',
    ];

    public function items()
    {
        return $this->hasMany(FeedbackProjectItem::class);
    }
}
