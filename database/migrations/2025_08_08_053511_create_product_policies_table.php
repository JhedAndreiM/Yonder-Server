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
        Schema::create('product_policies', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // 'allowed', 'prohibited', or future policy types
            $table->longText('content'); // stores Quill HTML
            $table->timestamps();
        });

        DB::table('product_policies')->insert([
            [
                'type' => 'allowed',
                'content' => '',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'prohibited',
                'content' => '',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_policies');
    }
};
