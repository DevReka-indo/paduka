<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QcFacilityUnit extends Model
{
    protected $fillable = [
        'qc_facility_id',
        'inventory_number',
        'serial_number',
        'location',
        'condition',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'qc_facility_id' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(
            QcFacility::class,
            'qc_facility_id'
        );
    }

    public function calibrations(): HasMany
    {
        return $this->hasMany(
            QcFacilityCalibration::class,
            'qc_facility_unit_id'
        );
    }

    public function latestCalibration(): HasOne
    {
        return $this->hasOne(
            QcFacilityCalibration::class,
            'qc_facility_unit_id'
        )->ofMany([
            'calibration_date' => 'max',
            'id' => 'max',
        ]);
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

    protected function conditionLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->condition) {
                'baik' => 'Baik',
                'perlu_perbaikan' => 'Perlu Perbaikan',
                'dalam_perbaikan' => 'Dalam Perbaikan',
                'tidak_layak' => 'Tidak Layak Digunakan',
                default => '-',
            }
        );
    }
}
