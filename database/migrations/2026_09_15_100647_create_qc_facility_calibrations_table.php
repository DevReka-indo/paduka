<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'qc_facility_calibrations',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('qc_facility_unit_id')
                    ->constrained('qc_facility_units')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->date('calibration_date')
                    ->nullable();

                $table->date('calibration_valid_until')
                    ->nullable();

                $table->string('certificate_number')
                    ->nullable();

                $table->string('calibration_laboratory')
                    ->nullable();

                $table->string('certificate_path')
                    ->nullable();

                $table->text('notes')
                    ->nullable();

                $table->foreignId('created_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->foreignId('updated_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamps();

                $table->index(
                    [
                        'qc_facility_unit_id',
                        'calibration_date',
                    ],
                    'qc_facility_calibration_unit_date_index'
                );

                $table->index(
                    'calibration_valid_until',
                    'qc_facility_calibration_valid_index'
                );

                $table->index('certificate_number');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'qc_facility_calibrations'
        );
    }
};
