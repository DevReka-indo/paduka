<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_final_electrical_ncrs', function (Blueprint $table) {
            $table->id();

            /*
             * Relasi opsional ke Daily Check PADUKA.
             *
             * Legacy workbook tidak selalu mempunyai relasi
             * satu-banding-satu dengan Daily Check, sehingga nullable.
             */
            $table->foreignId('daily_check_id')
                ->nullable()
                ->constrained('qc_final_electrical_checks')
                ->nullOnDelete();

            /*
             * Nomor NCR tidak unique.
             *
             * Workbook legacy menunjukkan satu nomor NCR dapat
             * mempunyai lebih dari satu detail ketidaksesuaian.
             */
            $table->string('ncr_number', 100);

            /*
             * Project master + snapshot.
             */
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete();

            $table->string('project_name');

            /*
             * Informasi NCR.
             */
            $table->date('issued_date');

            $table->string('product_name');

            $table->string(
                'nonconformity_location',
                255
            )->nullable();

            $table->string(
                'target_unit',
                255
            )->nullable();

            $table->longText(
                'nonconformity_description'
            );

            /*
             * Kategori defect.
             */
            $table->unsignedInteger('visual_qty')
                ->default(0);

            $table->unsignedInteger('completeness_qty')
                ->default(0);

            $table->unsignedInteger('specification_qty')
                ->default(0);

            $table->unsignedInteger('dimension_qty')
                ->default(0);

            $table->unsignedInteger('function_qty')
                ->default(0);

            /*
             * Workbook:
             *
             * OK  -> NCR CLOSE
             * NOK -> NCR OPEN
             * -   -> belum ditentukan
             *
             * Status NCR tidak perlu disimpan karena merupakan
             * derived value dari status komponen.
             */
            $table->enum('component_status', [
                'pending',
                'ok',
                'nok',
            ])->default('pending');

            $table->string(
                'inspector',
                150
            )->nullable();

            $table->decimal(
                'cycle_time_minutes',
                10,
                2
            )->nullable();

            /*
             * Source tracking.
             */
            $table->enum('source', [
                'paduka',
                'legacy_xlsx',
            ])->default('paduka');

            $table->string(
                'legacy_reference',
                255
            )->nullable();

            /*
             * Audit.
             */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            /*
             * Index untuk dashboard/filter.
             */
            $table->index('ncr_number');
            $table->index('issued_date');
            $table->index('project_id');
            $table->index('component_status');
            $table->index('source');

            $table->unique(
                'legacy_reference',
                'qc_final_electrical_ncr_legacy_reference_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'qc_final_electrical_ncrs'
        );
    }
};
