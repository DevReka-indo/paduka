<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackProjectItem extends Model
{
    protected $fillable = [
        'feedback_project_id',
        'nama_barang',
        'deskripsi',
        'is_active',
    ];

    public function project()
    {
        return $this->belongsTo(FeedbackProject::class, 'feedback_project_id');
    }
}
