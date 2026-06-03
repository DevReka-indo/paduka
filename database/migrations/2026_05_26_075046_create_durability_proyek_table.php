<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('durability_proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po', 20)->unique();
            $table->string('customer', 100);
            $table->string('nama_proyek', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('durability_proyek');
    }
};
