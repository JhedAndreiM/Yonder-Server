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
        Schema::create('trade_offer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trade_offer_id');
            $table->enum('offered_by', ['sender', 'recipient']); // Who is offering this item in the trade
            $table->unsignedBigInteger('product_id');
            $table->integer('variant_index')->nullable(); // Index of variant if product has variants
            $table->string('variant_name')->nullable(); // Name of the variant (e.g., "Small", "Red")
            $table->decimal('price', 12, 2)->default(0); // Price at time of offer
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('trade_offer_id')->references('id')->on('trade_offers')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            
            // Indexes
            $table->index(['trade_offer_id', 'offered_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_offer_items');
    }
};
