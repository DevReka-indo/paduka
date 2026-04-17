<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ncr', function (Blueprint $table) {

            $table->text('uraian')->nullable()->after('uraian_pencegahan');

        });
    }

    public function down(): void
    {
        Schema::table('ncr', function (Blueprint $table) {

            $table->dropColumn('uraian');

        });
    }
};
