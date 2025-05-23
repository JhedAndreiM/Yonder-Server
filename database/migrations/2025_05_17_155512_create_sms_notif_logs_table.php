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
        Schema::create('sms_notifLogs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('from_id');
            $table->unsignedBigInteger('to_id');

            $table->text('message');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_notif_logs');
    }
};
