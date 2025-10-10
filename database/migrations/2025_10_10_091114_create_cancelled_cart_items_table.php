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
        Schema::create('cancelled_cart_items', function (Blueprint $table) {
            $table->id();
            
            // Original cart item data
            $table->unsignedBigInteger('original_cart_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('product_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->string('selected_variant')->nullable();
            $table->decimal('voucher_applied', 10, 2)->default(0);
            $table->string('payment_type');
            $table->string('payment_confirmation')->default('no');
            $table->string('gcash_receipt')->nullable();
            $table->string('seller_qr_image')->nullable();
            $table->string('status')->default('cancelled');
            $table->timestamp('original_created_at');
            $table->timestamp('original_updated_at');
            
            // Cancellation data
            $table->string('cancelled_by'); // 'buyer' or 'seller'
            $table->string('cancel_reason');
            $table->text('custom_reason')->nullable();
            $table->timestamp('cancelled_at');
            $table->text('cancellation_notes')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
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
            
            // Indexes
            $table->index('original_cart_id');
            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('cancelled_at');
            $table->index('cancel_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancelled_cart_items');
    }
};
