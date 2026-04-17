<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ncr', function (Blueprint $table) {
            // Timestamp saat NCR pertama kali dibuat/dibuka (oleh pembuat NCR)
            $table->timestamp('signed_at_open')->nullable()->after('keterangan');

            // Timestamp saat NCR direspons oleh PIC / penanggung jawab (status: process)
            $table->timestamp('signed_at_process')->nullable()->after('signed_at_open');

            // Timestamp saat NCR diverifikasi dan ditutup oleh pembuat (status: close)
            $table->timestamp('signed_at_close')->nullable()->after('signed_at_process');
        });
    }

    public function down(): void
    {
        Schema::table('ncr', function (Blueprint $table) {
            $table->dropColumn(['signed_at_open', 'signed_at_process', 'signed_at_close']);
        });
    }
};
