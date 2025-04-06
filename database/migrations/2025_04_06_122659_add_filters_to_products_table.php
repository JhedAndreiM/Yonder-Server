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
        Schema::table('product', function (Blueprint $table) {
            $table->string('supplier_type')->nullable(); 
            $table->string('mode_of_transaction')->nullable(); 
            $table->string('condition')->nullable();
            $table->decimal('price', 10, 2)->nullable(); 
            $table->string('college')->nullable(); 
            $table->string('for')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn([
                'supplier_type',
                'mode_of_transaction',
                'condition',
                'price',
                'college',
                'for',
            ]);
        });
    }
};
