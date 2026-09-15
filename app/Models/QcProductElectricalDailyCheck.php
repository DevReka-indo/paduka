<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcProductElectricalDailyCheck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'qc_product_electrical_checks';

    public const RESULT_PENDING = 'pending';
    public const RESULT_OK = 'ok';
    public const RESULT_NOK = 'nok';

    public const NCR_VISUAL = 'visual';
    public const NCR_DIMENSI = 'dimensi';
    public const NCR_FUNGSI = 'fungsi';

    protected $fillable = [
        'check_date',
        'project_id',
        'project_name',
        'document_check',
        'inspection_gate',
        'product_name',
        'check_category',
        'batch_reference',
        'oil_description',

        'visual_qty',
        'skun_qty',
        'cramping_qty',
        'marking_qty',
        'belltest_qty',
        'function_qty',

        'product_ok_qty',
        'product_nok_qty',
        'cable_ok_qty',
        'cable_nok_qty',

        'remarks',
        'closing_oil_date',
        'status_is',
        'result',
        'inspector',
        'cycle_time_minutes',

        'ncr_number',
        'ncr_category',
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
            'skun_qty' => 'integer',
            'cramping_qty' => 'integer',
            'marking_qty' => 'integer',
            'belltest_qty' => 'integer',
            'function_qty' => 'integer',

            'product_ok_qty' => 'integer',
            'product_nok_qty' => 'integer',
            'cable_ok_qty' => 'integer',
            'cable_nok_qty' => 'integer',

            'cycle_time_minutes' => 'decimal:2',
        ];
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
     |--------------------------------------------------------------------------
     | Computed attributes
     |--------------------------------------------------------------------------
     */

    public function getTotalFindingsAttribute(): int
    {
        return
            $this->visual_qty +
            $this->skun_qty +
            $this->cramping_qty +
            $this->marking_qty +
            $this->belltest_qty +
            $this->function_qty;
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

    public function getTotalProductQtyAttribute(): int
    {
        return $this->product_ok_qty + $this->product_nok_qty;
    }

    public function getTotalCableQtyAttribute(): int
    {
        return $this->cable_ok_qty + $this->cable_nok_qty;
    }

    /*
     |--------------------------------------------------------------------------
     | Query scopes
     |--------------------------------------------------------------------------
     */

    public function scopeInYear(Builder $query, int $year): Builder
    {
        return $query->whereYear('check_date', $year);
    }

    public function scopeInMonth(
        Builder $query,
        int $year,
        int $month
    ): Builder {
        return $query
            ->whereYear('check_date', $year)
            ->whereMonth('check_date', $month);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('result', self::RESULT_NOK);
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('result', self::RESULT_OK);
    }

    /*
     |--------------------------------------------------------------------------
     | Options
     |--------------------------------------------------------------------------
     */

    public static function resultOptions(): array
    {
        return [
            self::RESULT_PENDING => 'Belum Ditentukan',
            self::RESULT_OK => 'OK',
            self::RESULT_NOK => 'NOK',
        ];
    }

    public static function ncrCategoryOptions(): array
    {
        return [
            self::NCR_VISUAL => 'Visual',
            self::NCR_DIMENSI => 'Dimensi',
            self::NCR_FUNGSI => 'Fungsi',
        ];
    }

    public static function documentCheckOptions(): array
    {
        return [
            'IS' => 'IS',
            'WT' => 'WT',
            '-' => 'Tidak Ada',
        ];
    }

    public static function inspectionGateOptions(): array
    {
        return [
            'Unit Area 1' => 'Unit Area 1',
            'Unit Area 2' => 'Unit Area 2',
            'Unit Area 3' => 'Unit Area 3',
            'Ws. INKA' => 'Workshop INKA',
        ];
    }

    public static function checkCategoryOptions(): array
    {
        return [
            'Function' => 'Function',
            'Harness Panel' => 'Harness Panel',
            'Harness Kereta' => 'Harness Kereta',
        ];
    }

    public static function statusIsOptions(): array
    {
        return [
            'Sudah ada' => 'Sudah Ada',
            'Belum Ada' => 'Belum Ada',
            'Tanpa IS' => 'Tanpa IS',
            '-' => 'Tidak Ditentukan',
        ];
    }
}
