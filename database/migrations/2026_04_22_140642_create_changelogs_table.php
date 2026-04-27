<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('changelogs', function (Blueprint $table) {
            $table->id();
            $table->string('versi', 20)->unique();
            $table->enum('tipe', ['release', 'feature', 'improvement', 'fix']);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_rilis');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('changelog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changelog_id')->constrained()->cascadeOnDelete();
            $table->string('isi');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('changelog_items');
        Schema::dropIfExists('changelogs');
    }
};
