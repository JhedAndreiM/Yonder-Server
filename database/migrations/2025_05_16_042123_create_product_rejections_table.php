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
        Schema::create('product_rejections', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('admin_id')->nullable(); // Optional
        $table->text('message');
        $table->timestamps();

        $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_rejections');
    }
};
