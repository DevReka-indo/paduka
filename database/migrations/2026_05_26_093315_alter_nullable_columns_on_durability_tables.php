<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('durability_proyek', function (Blueprint $table) {
            $table->string('nama_proyek', 100)->nullable()->change();
        });

        Schema::table('durability', function (Blueprint $table) {
            $table->integer('rentang_penggantian')->nullable()->change();
            $table->integer('jumlah_penggantian')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('durability_proyek', function (Blueprint $table) {
            $table->string('nama_proyek', 100)->nullable(false)->change();
        });

        Schema::table('durability', function (Blueprint $table) {
            $table->integer('rentang_penggantian')->nullable(false)->change();
            $table->integer('jumlah_penggantian')->nullable(false)->change();
        });
    }
};
