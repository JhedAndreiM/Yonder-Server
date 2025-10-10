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
        Schema::table('cancelled_cart_items', function (Blueprint $table) {
            // Drop existing foreign keys if they exist
            $table->dropForeign(['original_cart_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['seller_id']);

            // Re-add foreign key constraints
            $table->foreign('original_cart_id')
                ->references('id')
                ->on('cart_items')
                ->onDelete('cascade');
                
            $table->foreign('product_id')
                ->references('product_id')
                ->on('product')
                ->onDelete('cascade');
                
            $table->foreign('buyer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                
            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cancelled_cart_items', function (Blueprint $table) {
            $table->dropForeign(['original_cart_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['seller_id']);
        });
    }
};