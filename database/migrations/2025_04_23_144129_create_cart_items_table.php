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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');    // Buyer
            $table->unsignedBigInteger('seller_id');  // Seller
            $table->unsignedBigInteger('product_id'); // Product
        
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('voucher_applied', 8, 2)->default(0);
            $table->string('status')->default('in_cart');
        
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        
            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        
            $table->foreign('product_id')
                ->references('product_id') 
                ->on('product')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
