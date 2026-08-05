<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkIndicator extends Model
{
    use HasFactory;

    public const TYPE_PROGRAM_KERJA = 'program_kerja';

    public const TYPE_KPI = 'kpi';

    public const PERIOD_MONTHLY = 'monthly';

    public const PERIOD_QUARTERLY = 'quarterly';

    protected $fillable = [
        'type',
        'input_period',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(WorkAchievement::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeProgramKerja(Builder $query): Builder
    {
        return $query->where(
            'type',
            self::TYPE_PROGRAM_KERJA
        );
    }

    public function scopeKpi(Builder $query): Builder
    {
        return $query->where(
            'type',
            self::TYPE_KPI
        );
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function isProgramKerja(): bool
    {
        return $this->type === self::TYPE_PROGRAM_KERJA;
    }

    public function isKpi(): bool
    {
        return $this->type === self::TYPE_KPI;
    }

    public function isMonthly(): bool
    {
        return $this->input_period === self::PERIOD_MONTHLY;
    }

    public function isQuarterly(): bool
    {
        return $this->input_period === self::PERIOD_QUARTERLY;
    }

    public function maximumPeriod(): int
    {
        return $this->isQuarterly() ? 4 : 12;
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_PROGRAM_KERJA => 'Program Kerja',
            self::TYPE_KPI => 'KPI',
        ];
    }

    public static function inputPeriodOptions(): array
    {
        return [
            self::PERIOD_MONTHLY => 'Bulanan',
            self::PERIOD_QUARTERLY => 'Triwulan',
        ];
    }
}
