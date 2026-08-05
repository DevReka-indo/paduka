<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_indicator_id',
        'year',
        'period_number',
        'percentage',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'period_number' => 'integer',
            'percentage' => 'decimal:2',
        ];
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(
            WorkIndicator::class,
            'work_indicator_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function scopeForYear(
        Builder $query,
        int $year
    ): Builder {
        return $query->where('year', $year);
    }

    public function scopeUntilPeriod(
        Builder $query,
        int $period
    ): Builder {
        return $query->where(
            'period_number',
            '<=',
            $period
        );
    }

    public function periodLabel(): string
    {
        if ($this->indicator?->isQuarterly()) {
            return match ($this->period_number) {
                1 => 'Januari–Maret',
                2 => 'April–Juni',
                3 => 'Juli–September',
                4 => 'Oktober–Desember',
                default => '-',
            };
        }

        return match ($this->period_number) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => '-',
        };
    }
}
