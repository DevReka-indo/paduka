<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_facilities', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('category_id')
                ->constrained('qc_facility_categories')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            $table->longText('technical_specifications')->nullable();

            $table->string('inventory_number')->nullable()->unique();
            $table->string('serial_number')->nullable()->unique();

            $table->string('location')->nullable();
            $table->string('condition', 50)->default('baik');

            $table->date('calibration_date')->nullable();
            $table->date('calibration_valid_until')->nullable();

            $table->string('photo_path')->nullable();
            $table->text('description')->nullable();

            $table
                ->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table
                ->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('condition');
            $table->index('calibration_valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_facilities');
    }
};
