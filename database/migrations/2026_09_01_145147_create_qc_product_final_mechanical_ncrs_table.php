<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qc_product_final_mechanical_ncrs', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Optional Daily Check Relation
            |--------------------------------------------------------------------------
            */
            $table->foreignId('daily_check_id')
                ->nullable()
                ->constrained('qc_product_final_mechanical_checks')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Reporting Period
            |--------------------------------------------------------------------------
            */
            $table->unsignedSmallInteger('reporting_year');
            $table->unsignedTinyInteger('reporting_month');

            /*
            |--------------------------------------------------------------------------
            | NCR Identity
            |--------------------------------------------------------------------------
            */
            $table->string('ncr_number', 100);

            /*
            * Tidak dibuat unique karena satu nomor NCR
            * dapat memiliki lebih dari satu baris detail.
            */
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete();

            $table->string('project_name')
                ->nullable();

            $table->date('issued_date');

            $table->string('product_name');

            /*
            |--------------------------------------------------------------------------
            | Nonconformity
            |--------------------------------------------------------------------------
            */
            $table->string('nonconformity_location')
                ->nullable();

            $table->string('target_unit')
                ->nullable();

            $table->longText('nonconformity_description');

            /*
            |--------------------------------------------------------------------------
            | Defect Category
            |--------------------------------------------------------------------------
            |
            | DETAIL NCR MEKANIK hanya:
            | Visual, Dimensi, Fungsi.
            |
            */
            $table->unsignedInteger('visual_qty')
                ->default(0);

            $table->unsignedInteger('dimension_qty')
                ->default(0);

            $table->unsignedInteger('function_qty')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('component_status', [
                'pending',
                'ok',
                'nok',
            ])->default('pending');

            $table->string('inspector', 150)
                ->nullable();

            $table->decimal('cycle_time_minutes', 10, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Source / Audit
            |--------------------------------------------------------------------------
            */
            $table->enum('source', [
                'paduka',
                'legacy_xlsx',
            ])->default('paduka');

            $table->string('legacy_reference', 64)
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
            $table->softDeletes();

            $table->index(
                [
                    'reporting_year',
                    'reporting_month',
                ],
                'qc_pf_mech_ncr_period_idx'
            );

            $table->index(
                'ncr_number',
                'qc_pf_mech_ncr_number_idx'
            );

            $table->index(
                'issued_date',
                'qc_pf_mech_ncr_date_idx'
            );

            $table->index(
                'project_id',
                'qc_pf_mech_ncr_project_idx'
            );

            $table->index(
                'component_status',
                'qc_pf_mech_ncr_status_idx'
            );

            $table->index(
                'source',
                'qc_pf_mech_ncr_source_idx'
            );

            $table->unique(
                'legacy_reference',
                'qc_pf_mech_ncr_legacy_ref_uq'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'qc_product_final_mechanical_ncrs'
        );
    }
};
