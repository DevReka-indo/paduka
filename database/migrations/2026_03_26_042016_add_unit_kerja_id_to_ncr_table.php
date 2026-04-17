<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ncr', function (Blueprint $table) {
            $table->foreignId('unit_kerja_id')
                ->nullable()
                ->after('unit_tujuan')
                ->constrained('unit_kerja')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ncr', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_kerja_id');
        });
    }
};
