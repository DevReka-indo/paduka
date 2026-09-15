<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QcFinalElectricalDailyCheck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table =
        'qc_final_electrical_checks';

    public const RESULT_PENDING = 'pending';

    public const RESULT_OK = 'ok';

    public const RESULT_NOK = 'nok';

    protected $fillable = [
        'check_date',

        'project_id',
        'project_name',

        'document_check',
        'inspection_gate',
        'product_name',
        'check_category',

        'oil_description',

        'serial_number',
        'car_reference',
        'batch_reference',

        'visual_qty',
        'completeness_qty',
        'belltest_qty',
        'function_qty',
        'torque_qty',

        'closing_oil_date',
        'oil_count',

        'ncr_number',

        'result',
        'inspector',
        'status_is',

        'oil_link',

        'source',
        'legacy_reference',

        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'check_date' => 'date',
            'closing_oil_date' => 'date',

            'visual_qty' => 'integer',
            'completeness_qty' => 'integer',
            'belltest_qty' => 'integer',
            'function_qty' => 'integer',
            'torque_qty' => 'integer',

            'oil_count' => 'integer',
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

    public function ncrDetails(): HasMany
    {
        return $this->hasMany(
            QcFinalElectricalNcr::class,
            'daily_check_id'
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
            + $this->belltest_qty
            + $this->function_qty
            + $this->torque_qty;
    }

    public function getHasFindingAttribute(): bool
    {
        return $this->total_findings > 0;
    }

    public function getStatusProductAttribute(): string
    {
        return match ($this->result) {
            self::RESULT_OK => 'close',
            self::RESULT_NOK => 'open',
            default => 'pending',
        };
    }

    public function getStatusProductLabelAttribute(): string
    {
        return match ($this->status_product) {
            'close' => 'Close',
            'open' => 'Open',
            default => 'Belum Ditentukan',
        };
    }

    /*
     |--------------------------------------------------------------------------
     | Query Scopes
     |--------------------------------------------------------------------------
     */

    public function scopeInYear(
        $query,
        int $year
    ) {
        return $query->whereYear(
            'check_date',
            $year
        );
    }

    public function scopeInMonth(
        $query,
        int $month
    ) {
        return $query->whereMonth(
            'check_date',
            $month
        );
    }

    public function scopeOpen($query)
    {
        return $query->where(
            'result',
            self::RESULT_NOK
        );
    }

    public function scopeClosed($query)
    {
        return $query->where(
            'result',
            self::RESULT_OK
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
            'WT' => 'WT',
            'Dokumen OIL' => 'Dokumen OIL',
            '-' => 'Tidak Ada',
        ];
    }

    public static function inspectionGateOptions(): array
    {
        return [
            'Unit Area 1' =>
                'Unit Area 1',

            'Unit Area 2' =>
                'Unit Area 2',

            'Unit Area 3' =>
                'Unit Area 3',

            'WS. INKA' =>
                'Workshop INKA',

            'WS. INKA (BWI)' =>
                'Workshop INKA Banyuwangi',

            'WS. Tiron' =>
                'Workshop Tiron',
        ];
    }

    public static function checkCategoryOptions(): array
    {
        return [
            'Final Test' =>
                'Final Test',
        ];
    }

    public static function statusIsOptions(): array
    {
        return [
            'Sudah ada' =>
                'Sudah Ada',

            'Tanpa IS' =>
                'Tanpa IS',

            '-' =>
                'Tidak Ditentukan',
        ];
    }
}
