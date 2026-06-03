<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('durability_trainset', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_trainset');
            $table->string('tipe_car', 10);
            $table->timestamps();

            $table->unique(['nomor_trainset', 'tipe_car'], 'durability_trainset_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('durability_trainset');
    }
};
