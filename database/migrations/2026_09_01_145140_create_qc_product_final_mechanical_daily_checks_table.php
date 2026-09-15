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
        Schema::create('qc_product_final_mechanical_checks', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Reporting Period
            |--------------------------------------------------------------------------
            |
            | Periode dashboard/source workbook.
            | Tidak selalu sama dengan bulan check_date.
            |
            */
            $table->unsignedSmallInteger('reporting_year');
            $table->unsignedTinyInteger('reporting_month');

            /*
            |--------------------------------------------------------------------------
            | Main Inspection Data
            |--------------------------------------------------------------------------
            */
            $table->date('check_date');

            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete();

            $table->string('project_name')
                ->nullable();

            $table->string('document_check', 100)
                ->nullable();

            $table->string('inspection_gate', 150)
                ->nullable();

            $table->string('final_assembly_product_name');

            /*
            * Data Sub Part cukup panjang pada workbook,
            * jadi jangan dibatasi 255 karakter.
            */
            $table->text('sub_part_assembly_name')
                ->nullable();

            $table->longText('oil_description')
                ->nullable();

            $table->string('batch_reference', 150)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Finding Method
            |--------------------------------------------------------------------------
            |
            | Acronym dipertahankan sesuai workbook.
            | Kita belum memperluas VT/DM/WG/PT/CP/FT
            | karena workbook hanya memberikan singkatannya.
            |
            */
            $table->unsignedInteger('vt_qty')
                ->default(0);

            $table->unsignedInteger('dm_qty')
                ->default(0);

            $table->unsignedInteger('wg_qty')
                ->default(0);

            $table->unsignedInteger('pt_qty')
                ->default(0);

            $table->unsignedInteger('cp_qty')
                ->default(0);

            $table->unsignedInteger('ft_qty')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Inspection Quantity
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('qty_ok')
                ->default(0);

            $table->unsignedInteger('qty_nok')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Closing / Result
            |--------------------------------------------------------------------------
            */
            $table->text('remarks')
                ->nullable();

            $table->date('closing_oil_date')
                ->nullable();

            $table->string('ncr_number', 100)
                ->nullable();

            $table->enum('result', [
                'pending',
                'ok',
                'nok',
            ])->default('pending');

            $table->string('inspector', 150)
                ->nullable();

            $table->string('status_is', 100)
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

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index(
                [
                    'reporting_year',
                    'reporting_month',
                ],
                'qc_pf_mech_check_period_idx'
            );

            $table->index(
                'check_date',
                'qc_pf_mech_check_date_idx'
            );

            $table->index(
                'project_id',
                'qc_pf_mech_check_project_idx'
            );

            $table->index(
                'inspection_gate',
                'qc_pf_mech_check_gate_idx'
            );

            $table->index(
                'result',
                'qc_pf_mech_check_result_idx'
            );

            $table->index(
                'inspector',
                'qc_pf_mech_check_inspector_idx'
            );

            $table->index(
                'source',
                'qc_pf_mech_check_source_idx'
            );

            $table->unique(
                'legacy_reference',
                'qc_pf_mech_check_legacy_ref_uq'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'qc_product_final_mechanical_checks'
        );
    }
};
