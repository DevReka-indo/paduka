<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('durability', function (Blueprint $table) {
            $table->date('tgl_kerusakan')->nullable()->change();
            $table->date('tgl_terbit_lppb')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('durability', function (Blueprint $table) {
            $table->date('tgl_kerusakan')->nullable(false)->change();
            $table->date('tgl_terbit_lppb')->nullable(false)->change();
        });
    }
};
