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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('variant_name'); 
            $table->string('variant_option'); 
            $table->integer('stock')->default(0);
            $table->integer('critical_level')->nullable();
            $table->integer('lead_time')->nullable();
            $table->integer('safety_stock')->nullable();
            $table->enum('critical_mode', ['automatic', 'manual'])->default('automatic');
            $table->timestamps();


            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
