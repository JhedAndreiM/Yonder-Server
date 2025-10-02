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
        // First, drop the column if it exists (from failed migration)
        if (Schema::hasColumn('featured_images', 'product_id')) {
            Schema::table('featured_images', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        // Then add it properly with the correct foreign key
        Schema::table('featured_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('image_path');
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('featured_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};