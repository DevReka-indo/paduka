<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_achievements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('work_indicator_id')
                ->constrained('work_indicators')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('year');

            /*
             * Program Kerja:
             * 1 sampai 12 mewakili Januari sampai Desember.
             *
             * KPI:
             * 1 sampai 4 mewakili Triwulan I sampai IV.
             */
            $table->unsignedTinyInteger('period_number');

            /*
             * Nilai persentase dapat lebih dari 100
             * apabila capaian melampaui target.
             */
            $table->decimal('percentage', 7, 2)
                ->default(0);

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

            $table->unique(
                [
                    'work_indicator_id',
                    'year',
                    'period_number',
                ],
                'work_achievement_period_unique'
            );

            $table->index(
                [
                    'year',
                    'period_number',
                ],
                'work_achievement_period_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_achievements');
    }
};
