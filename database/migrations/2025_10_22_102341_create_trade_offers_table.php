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
        Schema::create('trade_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // User who sends the offer
            $table->unsignedBigInteger('recipient_id'); // User who receives the offer
            $table->unsignedBigInteger('parent_offer_id')->nullable(); // Links to original offer if this is a counter-offer
            $table->enum('status', ['pending', 'accepted', 'declined', 'cancelled', 'completed', 'countered'])->default('pending');
            $table->text('message')->nullable(); // Optional message from sender
            $table->timestamp('responded_at')->nullable(); // When recipient responded
            $table->timestamp('completed_at')->nullable(); // When trade was completed
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_offer_id')->references('id')->on('trade_offers')->onDelete('set null');
            
            // Indexes
            $table->index(['sender_id', 'status']);
            $table->index(['recipient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_offers');
    }
};
