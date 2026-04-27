<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback_pelanggan', function (Blueprint $table) {
            $table->id();

            $table->string('nama_lengkap')->nullable();
            $table->string('perusahaan')->nullable();
            $table->string('jabatan_unit_kerja')->nullable();

            $table->string('proyek');
            $table->string('identitas_barang');

            $table->unsignedTinyInteger('q1_pengiriman_tepat_waktu');
            $table->unsignedTinyInteger('q2_kemudahan_pengoperasian_produk');
            $table->unsignedTinyInteger('q3_kemudahan_perawatan');
            $table->unsignedTinyInteger('q4_pendampingan_support_trial');
            $table->unsignedTinyInteger('q5_responsif_penanganan_complain');
            $table->unsignedTinyInteger('q6_teknisi_ramah_sopan');
            $table->unsignedTinyInteger('q7_penanganan_complain_tepat_cepat');
            $table->unsignedTinyInteger('q8_media_complain_mudah_diakses');
            $table->unsignedTinyInteger('q9_produk_sesuai_standar_po');

            $table->text('saran_masukan')->nullable();

            // tanda tangan base64 dari signature pad
            $table->longText('tanda_tangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_pelanggan');
    }
};
