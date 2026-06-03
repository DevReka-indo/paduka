<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('durability_trainset', function (Blueprint $table) {
            $table->integer('nomor_trainset')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('durability_trainset', function (Blueprint $table) {
            $table->integer('nomor_trainset')->nullable(false)->change();
        });
    }
};
