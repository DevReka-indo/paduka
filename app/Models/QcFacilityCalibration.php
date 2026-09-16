<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QcFacilityCalibration extends Model
{
    protected $fillable = [
        'qc_facility_unit_id',
        'calibration_date',
        'calibration_valid_until',
        'certificate_number',
        'calibration_laboratory',
        'certificate_path',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'qc_facility_unit_id' => 'integer',
            'calibration_date' => 'date',
            'calibration_valid_until' => 'date',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            QcFacilityUnit::class,
            'qc_facility_unit_id'
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

    protected function calibrationStatus(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (!$this->calibration_valid_until) {
                    return 'not_available';
                }

                $today = now()->startOfDay();

                if (
                    $this->calibration_valid_until
                        ->isBefore($today)
                ) {
                    return 'expired';
                }

                if (
                    $this->calibration_valid_until
                        ->lessThanOrEqualTo(
                            $today->copy()->addDays(30)
                        )
                ) {
                    return 'expiring';
                }

                return 'valid';
            }
        );
    }

    protected function calibrationStatusLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match (
                $this->calibration_status
            ) {
                'valid' => 'Masih Berlaku',
                'expiring' => 'Akan Kedaluwarsa',
                'expired' => 'Kedaluwarsa',
                default => 'Belum Ada Data Kalibrasi',
            }
        );
    }
}
