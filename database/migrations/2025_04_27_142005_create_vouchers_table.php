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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('seller_id'); 
            $table->decimal('amount', 8, 2); 
            $table->enum('status', ['available', 'in_cart', 'used'])->default('available');
            $table->unsignedBigInteger('cart_item_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cart_item_id')->references('id')->on('cart_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
