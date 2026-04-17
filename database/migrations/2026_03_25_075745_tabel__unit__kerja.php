<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit', 20)->unique()->nullable();
            $table->string('nama_unit', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('keterangan')->default(true)->comment('true = aktif, false = nonaktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kerja');
    }
};
