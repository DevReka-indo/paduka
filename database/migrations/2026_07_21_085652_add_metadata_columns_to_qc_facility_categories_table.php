<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_facility_categories', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('qc_facility_categories', function (Blueprint $table) {
            $table->dropUnique(
                'qc_facility_categories_slug_unique'
            );

            $table->dropColumn([
                'name',
                'slug',
                'description',
                'sort_order',
                'is_active',
            ]);
        });
    }
};
