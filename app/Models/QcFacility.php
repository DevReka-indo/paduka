<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QcFacility extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'model',
        'technical_specifications',
        'inventory_number',
        'serial_number',
        'location',
        'condition',
        'calibration_date',
        'calibration_valid_until',
        'photo_path',
        'description',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'calibration_date' => 'date',
            'calibration_valid_until' => 'date',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            QcFacilityCategory::class,
            'category_id'
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

                if ($this->calibration_valid_until->isBefore($today)) {
                    return 'expired';
                }

                if (
                    $this->calibration_valid_until->lessThanOrEqualTo(
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
            get: fn (): string => match ($this->calibration_status) {
                'valid' => 'Masih Berlaku',
                'expiring' => 'Akan Kedaluwarsa',
                'expired' => 'Kedaluwarsa',
                default => 'Belum Ada Data Kalibrasi',
            }
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

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->photo_path
                ? asset('storage/' . $this->photo_path)
                : null
        );
    }
}
