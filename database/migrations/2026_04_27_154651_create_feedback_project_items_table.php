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
        Schema::create('feedback_project_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feedback_project_id')
                ->constrained('feedback_projects')
                ->cascadeOnDelete();

            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_project_items');
    }
};
