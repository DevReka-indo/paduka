<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcFinalElectricalNcr extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table =
        'qc_final_electrical_ncrs';

    public const STATUS_PENDING = 'pending';

    public const STATUS_OK = 'ok';

    public const STATUS_NOK = 'nok';

    protected $fillable = [
        'daily_check_id',

        'ncr_number',

        'project_id',
        'project_name',

        'issued_date',
        'product_name',

        'nonconformity_location',
        'target_unit',
        'nonconformity_description',

        'visual_qty',
        'completeness_qty',
        'specification_qty',
        'dimension_qty',
        'function_qty',

        'component_status',

        'inspector',
        'cycle_time_minutes',

        'source',
        'legacy_reference',

        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',

            'visual_qty' => 'integer',
            'completeness_qty' => 'integer',
            'specification_qty' => 'integer',
            'dimension_qty' => 'integer',
            'function_qty' => 'integer',

            'cycle_time_minutes' =>
                'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function dailyCheck(): BelongsTo
    {
        return $this->belongsTo(
            QcFinalElectricalDailyCheck::class,
            'daily_check_id'
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class
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

    /*
    |--------------------------------------------------------------------------
    | Derived Attributes
    |--------------------------------------------------------------------------
    */

    public function getTotalFindingsAttribute(): int
    {
        return
            $this->visual_qty
            + $this->completeness_qty
            + $this->specification_qty
            + $this->dimension_qty
            + $this->function_qty;
    }

    public function getHasFindingAttribute(): bool
    {
        return $this->total_findings > 0;
    }

    public function getNcrStatusAttribute(): string
    {
        return match (
            $this->component_status
        ) {
            self::STATUS_OK =>
                'close',

            self::STATUS_NOK =>
                'open',

            default =>
                'pending',
        };
    }

    public function getNcrStatusLabelAttribute(): string
    {
        return match (
            $this->ncr_status
        ) {
            'close' =>
                'Close',

            'open' =>
                'Open',

            default =>
                'Belum Ditentukan',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeInYear(
        $query,
        int $year
    ) {
        return $query->whereYear(
            'issued_date',
            $year
        );
    }

    public function scopeInMonth(
        $query,
        int $month
    ) {
        return $query->whereMonth(
            'issued_date',
            $month
        );
    }

    public function scopeOpen($query)
    {
        return $query->where(
            'component_status',
            self::STATUS_NOK
        );
    }

    public function scopeClosed($query)
    {
        return $query->where(
            'component_status',
            self::STATUS_OK
        );
    }

    public function scopePending($query)
    {
        return $query->where(
            'component_status',
            self::STATUS_PENDING
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Form Options
    |--------------------------------------------------------------------------
    */

    public static function componentStatusOptions(): array
    {
        return [
            self::STATUS_PENDING =>
                'Belum Ditentukan',

            self::STATUS_OK =>
                'OK',

            self::STATUS_NOK =>
                'NOK',
        ];
    }
}
