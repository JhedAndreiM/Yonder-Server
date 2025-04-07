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
        Schema::dropIfExists('product');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('product', function ($table) {
            $table->id();
            // Add other columns here if you want rollback support
            $table->timestamps();
        });
    }
};
