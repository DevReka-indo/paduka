<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('durability', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyek_id')
                ->constrained('durability_proyek')
                ->cascadeOnDelete();

            $table->foreignId('komponen_id')
                ->constrained('durability_komponen')
                ->cascadeOnDelete();

            $table->foreignId('trainset_id')
                ->constrained('durability_trainset')
                ->cascadeOnDelete();

            $table->foreignId('lokasi_id')
                ->constrained('durability_lokasi')
                ->cascadeOnDelete();

            $table->date('tgl_kerusakan')->nullable();
            $table->date('tgl_terbit_lppb');

            $table->text('case_keterangan')->nullable();

            $table->integer('rentang_penggantian')->nullable();
            $table->integer('jumlah_penggantian')->nullable();

            $table->timestamps();

            $table->index('tgl_kerusakan');
            $table->index('tgl_terbit_lppb');
            $table->index('rentang_penggantian');
            $table->index('jumlah_penggantian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('durability');
    }
};
