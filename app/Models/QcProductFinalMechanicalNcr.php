<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcProductFinalMechanicalNcr extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_OK = 'ok';

    public const STATUS_NOK = 'nok';

    protected $table = 'qc_product_final_mechanical_ncrs';

    protected $fillable = [
        'daily_check_id',

        'reporting_year',
        'reporting_month',

        'ncr_number',

        'project_id',
        'project_name',

        'issued_date',

        'product_name',

        'nonconformity_location',
        'target_unit',
        'nonconformity_description',

        'visual_qty',
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
            'reporting_year' =>
                'integer',

            'reporting_month' =>
                'integer',

            'issued_date' =>
                'date',

            'visual_qty' =>
                'integer',

            'dimension_qty' =>
                'integer',

            'function_qty' =>
                'integer',

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
            QcProductFinalMechanicalDailyCheck::class,
            'daily_check_id'
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
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
    | Accessors
    |--------------------------------------------------------------------------
    */

    protected function totalFindings(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                (int) $this->visual_qty
                + (int) $this->dimension_qty
                + (int) $this->function_qty
        );
    }

    protected function hasFinding(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->total_findings > 0
        );
    }

    protected function ncrStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match (
                $this->component_status
            ) {
                self::STATUS_OK =>
                    'close',

                self::STATUS_NOK =>
                    'open',

                default =>
                    'pending',
            }
        );
    }

    protected function ncrStatusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match (
                $this->ncr_status
            ) {
                'close' =>
                    'Close',

                'open' =>
                    'Open',

                default =>
                    'Belum Ditentukan',
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeReportingYear(
        Builder $query,
        int $year
    ): Builder {
        return $query->where(
            'reporting_year',
            $year
        );
    }

    public function scopeReportingMonth(
        Builder $query,
        int $month
    ): Builder {
        return $query->where(
            'reporting_month',
            $month
        );
    }

    public function scopeReportingPeriod(
        Builder $query,
        int $year,
        ?int $month = null
    ): Builder {
        $query->where(
            'reporting_year',
            $year
        );

        if ($month !== null) {
            $query->where(
                'reporting_month',
                $month
            );
        }

        return $query;
    }

    public function scopeOpen(
        Builder $query
    ): Builder {
        return $query->where(
            'component_status',
            self::STATUS_NOK
        );
    }

    public function scopeClosed(
        Builder $query
    ): Builder {
        return $query->where(
            'component_status',
            self::STATUS_OK
        );
    }

    public function scopePending(
        Builder $query
    ): Builder {
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

    public static function monthOptions(): array
    {
        return [
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
        ];
    }

    public static function findingLabels(): array
    {
        return [
            'visual_qty' =>
                'Visual',

            'dimension_qty' =>
                'Dimensi',

            'function_qty' =>
                'Fungsi',
        ];
    }
}
