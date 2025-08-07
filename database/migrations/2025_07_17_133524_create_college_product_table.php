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
        Schema::create('college_product', function (Blueprint $table) {
        $table->id();
    
    $table->foreignId('college_id')
          ->constrained('colleges')
          ->onDelete('cascade');

    $table->unsignedBigInteger('product_id');
    $table->foreign('product_id')
          ->references('product_id')  
          ->on('product')             
          ->onDelete('cascade');

    $table->timestamps();
    $table->unique(['college_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_product');
    }
};
