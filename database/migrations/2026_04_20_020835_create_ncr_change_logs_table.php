<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ncr_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ncr_id')->constrained('ncr')->cascadeOnDelete();
            $table->string('nomor_ncr');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->json('changes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ncr_change_logs');
    }
};
