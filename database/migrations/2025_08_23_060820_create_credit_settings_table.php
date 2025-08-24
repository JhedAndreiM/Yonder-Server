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
        Schema::create('credit_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('percentage', 5, 2)->default(5.00);
            $table->timestamps();
        });

        DB::table('credit_settings')->insert([
            'percentage' => 5.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_settings');
    }
};
