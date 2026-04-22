<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NcrChangeLog extends Model
{
    protected $fillable = [
        'nomor_ncr',
        'user_id',
        'revision',
        'revision_index',
        'action',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function ncr()
    {
        return $this->belongsTo(Ncr::class, 'nomor_ncr', 'nomor_ncr');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
