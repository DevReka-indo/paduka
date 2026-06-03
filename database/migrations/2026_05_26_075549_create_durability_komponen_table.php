<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('durability_komponen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produk_id')
                ->constrained('durability_produk')
                ->cascadeOnDelete();

            $table->string('nama_komponen', 200);

            $table->timestamps();

            $table->unique(['produk_id', 'nama_komponen'], 'durability_komponen_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('durability_komponen');
    }
};
