<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_indicators', function (Blueprint $table) {
            $table->id();

            $table->string('type', 30)
                ->index()
                ->comment('program_kerja atau kpi');

            $table->string('input_period', 20)
                ->index()
                ->comment('monthly atau quarterly');

            $table->string('name');

            $table->text('description')
                ->nullable();

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->timestamps();

            $table->index(
                [
                    'type',
                    'input_period',
                    'is_active',
                    'sort_order',
                ],
                'work_indicators_listing_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_indicators');
    }
};
