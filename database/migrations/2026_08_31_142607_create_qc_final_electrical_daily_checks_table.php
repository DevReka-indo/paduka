<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_final_electrical_checks', function (Blueprint $table) {
            $table->id();

            /*
             * Informasi pemeriksaan.
             */
            $table->date('check_date');

            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete();

            /*
             * Snapshot nama proyek supaya histori tetap
             * terbaca meskipun master project berubah.
             */
            $table->string('project_name');

            $table->string('document_check', 100);
            $table->string('inspection_gate', 150);

            $table->string('product_name');
            $table->string('check_category', 100);

            /*
             * Detail pemeriksaan.
             */
            $table->longText('oil_description')
                ->nullable();

            $table->string('serial_number', 150)
                ->nullable();

            $table->string('car_reference', 150)
                ->nullable();

            $table->string('batch_reference', 150)
                ->nullable();

            /*
             * Jumlah temuan berdasarkan jenis pengecekan.
             */
            $table->unsignedInteger('visual_qty')
                ->default(0);

            $table->unsignedInteger('completeness_qty')
                ->default(0);

            $table->unsignedInteger('belltest_qty')
                ->default(0);

            $table->unsignedInteger('function_qty')
                ->default(0);

            $table->unsignedInteger('torque_qty')
                ->default(0);

            /*
             * Penyelesaian OIL.
             */
            $table->date('closing_oil_date')
                ->nullable();

            $table->unsignedInteger('oil_count')
                ->default(0);

            /*
             * NCR pada Daily Check.
             * Detail NCR nantinya memiliki tabel tersendiri.
             */
            $table->string('ncr_number', 100)
                ->nullable();

            /*
             * Hasil pemeriksaan.
             */
            $table->enum('result', [
                'pending',
                'ok',
                'nok',
            ])->default('pending');

            $table->string('inspector', 150);

            $table->string('status_is', 100)
                ->nullable();

            /*
             * Link OIL dari Google Sheet / sumber legacy.
             */
            $table->text('oil_link')
                ->nullable();

            /*
             * Source tracking.
             */
            $table->enum('source', [
                'paduka',
                'legacy_xlsx',
            ])->default('paduka');

            $table->string('legacy_reference', 255)
                ->nullable();

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
             * Index dashboard/filter.
             */
            $table->index('check_date');
            $table->index('inspection_gate');
            $table->index('check_category');
            $table->index('result');
            $table->index('inspector');
            $table->index('source');

            $table->unique(
                'legacy_reference',
                'qc_final_electrical_legacy_reference_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'qc_final_electrical_checks'
        );
    }
};
