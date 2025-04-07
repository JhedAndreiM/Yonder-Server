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
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_id'); 
            $table->longText('description');
            $table->string('name');
            $table->string('stock');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->string('supplier_type')->nullable();
            $table->string('mode_of_transaction')->nullable();
            $table->string('product_condition')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('colleges')->nullable();
            $table->string('forSaleTrade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
