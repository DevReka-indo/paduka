<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_facility_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('qc_facility_id')
                ->constrained('qc_facilities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('inventory_number')
                ->nullable()
                ->unique();

            $table->string('serial_number')
                ->nullable()
                ->unique();

            $table->string('location')
                ->nullable();

            $table->string('condition', 50)
                ->default('baik');

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
                ['qc_facility_id', 'condition'],
                'qc_facility_units_facility_condition_index'
            );

            $table->index('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_facility_units');
    }
};
