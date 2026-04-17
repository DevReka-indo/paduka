<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('jabatan', 20)->nullable()->after('name');
            $table->string('unit_kerja', 100)->nullable();
            $table->string('departemen', 100)->nullable();
            $table->string('divisi', 100)->nullable();

            $table->string('username', 15)->unique()->after('email');

            $table->string('no_telp', 15)->nullable();

            $table->string('foto', 100)->nullable();

            $table->enum('level', ['admin','user','manager'])->default('user');

            $table->boolean('keterangan')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'jabatan',
                'unit_kerja',
                'departemen',
                'divisi',
                'username',
                'no_telp',
                'foto',
                'level',
                'keterangan'
            ]);

        });
    }
};
