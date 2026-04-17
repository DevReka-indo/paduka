<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ncr', function (Blueprint $table) {

            $table->id();

            $table->string('nomor_ncr', 15)->unique();

            // relasi user
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->date('tgl_masuk');
            $table->date('tgl_target');
            $table->date('tgl_managers')->nullable();

            $table->string('nama_proses', 100);
            $table->string('kode_proyek', 50);

            $table->string('status_temuan', 100);
            $table->string('acuan_periksa', 100);

            $table->string('surat_jalan', 255)->nullable();

            $table->string('unit_tujuan', 100);

            $table->string('kategori_masalah', 15);
            $table->string('akar_masalah', 15)->nullable();

            $table->text('uraian_masalah');
            $table->text('uraian_perbaikan');
            $table->text('uraian_pencegahan');

            $table->text('doc_pendukung');
            $table->text('doc_lampiran');

            $table->string('penanggung_jawab', 100);

            $table->string('up_file', 100)->nullable();
            $table->string('up_filee', 100)->nullable();

            $table->string('disposisi_inspektor', 100);
            $table->string('disposisi_unit', 100);

            $table->string('senior_manager', 100);

            // manager relasi
            $table->foreignId('manager_tgp_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('manager_qc_id')->nullable()->constrained('users')->nullOnDelete();

            $table->text('verifikasi_qc');

            $table->date('tgl_verifikasi')->nullable();

            $table->string('hasil_verifikasi', 18);

            $table->enum('keterangan', ['open', 'process', 'close', 'closed'])->default('open');

            $table->date('tgl_notif_terakhir')->nullable();

            $table->string('ncr_baru', 15)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ncr');
    }
};
