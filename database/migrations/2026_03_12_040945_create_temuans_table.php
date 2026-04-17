<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_temuan', 15)->unique();
            $table->string('status_temuan', 25);
            $table->string('detail_temuan', 175);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temuans');
    }
};
