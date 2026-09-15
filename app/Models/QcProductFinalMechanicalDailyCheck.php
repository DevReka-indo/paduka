<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcProductFinalMechanicalDailyCheck extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const RESULT_PENDING = 'pending';

    public const RESULT_OK = 'ok';

    public const RESULT_NOK = 'nok';

    protected $table = 'qc_product_final_mechanical_checks';

    protected $fillable = [
        'reporting_year',
        'reporting_month',

        'check_date',

        'project_id',
        'project_name',

        'document_check',
        'inspection_gate',

        'final_assembly_product_name',
        'sub_part_assembly_name',

        'oil_description',
        'batch_reference',

        'vt_qty',
        'dm_qty',
        'wg_qty',
        'pt_qty',
        'cp_qty',
        'ft_qty',

        'qty_ok',
        'qty_nok',

        'remarks',
        'closing_oil_date',
        'ncr_number',

        'result',
        'inspector',
        'status_is',

        'cycle_time_minutes',

        'source',
        'legacy_reference',

        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'reporting_year' => 'integer',
            'reporting_month' => 'integer',

            'check_date' => 'date',
            'closing_oil_date' => 'date',

            'vt_qty' => 'integer',
            'dm_qty' => 'integer',
            'wg_qty' => 'integer',
            'pt_qty' => 'integer',
            'cp_qty' => 'integer',
            'ft_qty' => 'integer',

            'qty_ok' => 'integer',
            'qty_nok' => 'integer',

            'cycle_time_minutes' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

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

    public function ncrDetails(): HasMany
    {
        return $this->hasMany(
            QcProductFinalMechanicalNcr::class,
            'daily_check_id'
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
                (int) $this->vt_qty
                + (int) $this->dm_qty
                + (int) $this->wg_qty
                + (int) $this->pt_qty
                + (int) $this->cp_qty
                + (int) $this->ft_qty
        );
    }

    protected function hasFinding(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->total_findings > 0
        );
    }

    protected function totalCheckedQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                (int) $this->qty_ok
                + (int) $this->qty_nok
        );
    }

    protected function assemblyStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->result) {
                self::RESULT_OK => 'close',
                self::RESULT_NOK => 'open',
                default => 'pending',
            }
        );
    }

    protected function assemblyStatusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match (
                $this->assembly_status
            ) {
                'close' => 'Close',
                'open' => 'Open',
                default => 'Belum Ditentukan',
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
            'result',
            self::RESULT_NOK
        );
    }

    public function scopeClosed(
        Builder $query
    ): Builder {
        return $query->where(
            'result',
            self::RESULT_OK
        );
    }

    public function scopePending(
        Builder $query
    ): Builder {
        return $query->where(
            'result',
            self::RESULT_PENDING
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Form Options
    |--------------------------------------------------------------------------
    */

    public static function resultOptions(): array
    {
        return [
            self::RESULT_PENDING =>
                'Belum Ditentukan',

            self::RESULT_OK =>
                'OK',

            self::RESULT_NOK =>
                'NOK',
        ];
    }

    public static function documentCheckOptions(): array
    {
        return [
            'IS' => 'IS',
            'MD' => 'MD',
        ];
    }

    public static function inspectionGateOptions(): array
    {
        return [
            'Incoming (by Request)' =>
                'Incoming (by Request)',

            'Laser Cut & Bending' =>
                'Laser Cut & Bending',

            'Welding & Grinding' =>
                'Welding & Grinding',

            'Finishing' =>
                'Finishing',

            'Final Test' =>
                'Final Test',
        ];
    }

    public static function statusIsOptions(): array
    {
        return [
            'Sudah ada' =>
                'Sudah ada',

            'Tanpa IS' =>
                'Tanpa IS',
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
            'vt_qty' => 'VT',
            'dm_qty' => 'DM',
            'wg_qty' => 'WG',
            'pt_qty' => 'PT',
            'cp_qty' => 'CP',
            'ft_qty' => 'FT',
        ];
    }
}
