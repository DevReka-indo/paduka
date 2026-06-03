<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('durability', function (Blueprint $table) {
            $table->year('tahun')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('durability', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
