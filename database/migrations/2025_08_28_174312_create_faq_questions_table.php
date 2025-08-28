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
        Schema::create('faq_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('faq_categories')
                ->onDelete('cascade'); // deletes questions if category is deleted
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_questions');
    }
};
