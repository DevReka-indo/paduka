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
        Schema::table('feedback_pelanggan', function (Blueprint $table) {
            $table->foreignId('feedback_project_item_id')
                ->nullable()
                ->after('feedback_project_id')
                ->constrained('feedback_project_items')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_pelanggan', function (Blueprint $table) {
            //
        });
    }
};
