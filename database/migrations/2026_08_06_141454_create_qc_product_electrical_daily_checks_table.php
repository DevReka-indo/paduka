<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_product_electrical_checks', function (Blueprint $table) {
            $table->id();

            /*
             * Informasi pemeriksaan
             */
            $table->date('check_date');

            $table
                ->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete();

            /*
             * Snapshot nama proyek tetap disimpan karena:
             * - data legacy belum tentu tersedia di master project;
             * - nama/kode proyek pada master dapat berubah;
             * - beberapa nilai Excel berupa kode bebas seperti KCI atau 612.
             */
            $table->string('project_name');

            $table->string('document_check', 50);
            $table->string('inspection_gate', 100);
            $table->string('product_name');
            $table->string('check_category', 100);
            $table->string('batch_reference')->nullable();

            /*
             * OIL / detail temuan
             */
            $table->longText('oil_description')->nullable();

            /*
             * Jumlah temuan berdasarkan kategori
             */
            $table->unsignedInteger('visual_qty')->default(0);
            $table->unsignedInteger('skun_qty')->default(0);
            $table->unsignedInteger('cramping_qty')->default(0);
            $table->unsignedInteger('marking_qty')->default(0);
            $table->unsignedInteger('belltest_qty')->default(0);
            $table->unsignedInteger('function_qty')->default(0);

            /*
             * Hasil pemeriksaan produk dan kabel
             */
            $table->unsignedBigInteger('product_ok_qty')->default(0);
            $table->unsignedBigInteger('product_nok_qty')->default(0);
            $table->unsignedBigInteger('cable_ok_qty')->default(0);
            $table->unsignedBigInteger('cable_nok_qty')->default(0);

            /*
             * Penyelesaian pemeriksaan
             */
            $table->text('remarks')->nullable();
            $table->date('closing_oil_date')->nullable();

            /*
             * Status IS mengikuti teks pada workbook, misalnya:
             * - Sudah ada
             * - Tanpa IS
             * - Belum ada
             */
            $table->string('status_is', 100)->nullable();

            /*
             * Result adalah sumber perhitungan Status Product:
             * pending = belum ditentukan
             * ok      = CLOSE
             * nok     = OPEN
             */
            $table
                ->enum('result', ['pending', 'ok', 'nok'])
                ->default('pending');

            $table->string('inspector');
            $table->decimal('cycle_time_minutes', 10, 2)->nullable();

            /*
             * Informasi NCR bersifat opsional.
             * Kategori diperlukan untuk grafik NCR pada dashboard.
             */
            $table->string('ncr_number')->nullable();

            $table
                ->enum('ncr_category', ['visual', 'dimensi', 'fungsi'])
                ->nullable();

            /*
             * Link OIL lama atau link dokumen eksternal.
             */
            $table->text('oil_link')->nullable();

            /*
             * Sumber data:
             * - paduka      = input dari form PADUKA
             * - legacy_xlsx = hasil import workbook lama
             */
            $table
                ->enum('source', ['paduka', 'legacy_xlsx'])
                ->default('paduka');

            $table->string('legacy_reference')->nullable();

            /*
             * Audit pengguna
             */
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
            $table->softDeletes();

            /*
             * Index untuk filter index dan dashboard.
             */
            $table->index('check_date');
            $table->index('inspection_gate');
            $table->index('check_category');
            $table->index('result');
            $table->index('inspector');
            $table->index('ncr_category');
            $table->index(['check_date', 'result']);
            $table->index(['check_date', 'inspection_gate']);
            $table->index(['check_date', 'check_category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_product_electrical_checks');
    }
};
