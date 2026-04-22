<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ncr_change_logs', function (Blueprint $table) {
            // 1. Tambah kolom revisi
            $table->string('revision', 20)->nullable()->after('user_id');
            $table->unsignedInteger('revision_index')->nullable()->after('revision');

            // 2. Pastikan nomor_ncr ada index
            $table->index('nomor_ncr');
            $table->index(['nomor_ncr', 'revision_index']);
        });

        // 3. Hapus foreign key ncr_id lama jika ada
        // Nama constraint biasanya: ncr_change_logs_ncr_id_foreign
        try {
            Schema::table('ncr_change_logs', function (Blueprint $table) {
                $table->dropForeign(['ncr_id']);
            });
        } catch (\Throwable $e) {
            // abaikan jika foreign key tidak ada / nama berbeda
        }

        // 4. Hapus kolom ncr_id lama jika ada
        if (Schema::hasColumn('ncr_change_logs', 'ncr_id')) {
            Schema::table('ncr_change_logs', function (Blueprint $table) {
                $table->dropColumn('ncr_id');
            });
        }

        // 5. Isi default untuk data lama kalau sudah ada record
        DB::table('ncr_change_logs')
            ->whereNull('revision')
            ->update([
                'revision' => 'Rev A',
                'revision_index' => 1,
            ]);

        // 6. Setelah data lama aman, ubah jadi not nullable
        DB::statement("ALTER TABLE ncr_change_logs MODIFY revision VARCHAR(20) NOT NULL");
        DB::statement("ALTER TABLE ncr_change_logs MODIFY revision_index INT UNSIGNED NOT NULL");
    }

    public function down(): void
    {
        // rollback minimal
        Schema::table('ncr_change_logs', function (Blueprint $table) {
            if (Schema::hasColumn('ncr_change_logs', 'revision_index')) {
                $table->dropIndex(['nomor_ncr', 'revision_index']);
            }

            if (Schema::hasColumn('ncr_change_logs', 'nomor_ncr')) {
                $table->dropIndex(['nomor_ncr']);
            }

            if (Schema::hasColumn('ncr_change_logs', 'revision')) {
                $table->dropColumn('revision');
            }

            if (Schema::hasColumn('ncr_change_logs', 'revision_index')) {
                $table->dropColumn('revision_index');
            }
        });

        Schema::table('ncr_change_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('ncr_change_logs', 'ncr_id')) {
                $table->unsignedBigInteger('ncr_id')->nullable()->after('id');
            }
        });
    }
};
