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
            $table->unsignedBigInteger('trade_offer_id'); // The trade offer this item belongs to
            $table->unsignedBigInteger('product_id'); // The product being traded
            $table->enum('side', ['sender', 'recipient']); // Who is offering this item
            $table->integer('quantity')->default(1); // Quantity of this item
            $table->integer('variant_index')->nullable(); // Index of selected variant (if applicable)
            $table->string('variant_name')->nullable(); // Name of selected variant (for display)
            $table->decimal('price_at_time', 10, 2); // Price when trade was created (for history)
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('trade_offer_id')->references('id')->on('trade_offers')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
            
            // Indexes
            $table->index('trade_offer_id');
            $table->index(['trade_offer_id', 'side']);
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
