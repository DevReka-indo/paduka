<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('feedback_pelanggan', function (Blueprint $table) {
            $table->string('proyek')->nullable()->change();
            $table->string('identitas_barang')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('feedback_pelanggan', function (Blueprint $table) {
            $table->string('proyek')->nullable(false)->change();
            $table->string('identitas_barang')->nullable(false)->change();
        });
    }
};
