<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangelogItem extends Model
{
    protected $fillable = [
        'changelog_id',
        'isi',
        'urutan',
    ];

    public function changelog()
    {
        return $this->belongsTo(Changelog::class);
    }
}
