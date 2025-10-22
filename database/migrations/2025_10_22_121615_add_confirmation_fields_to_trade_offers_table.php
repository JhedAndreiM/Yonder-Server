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
        Schema::table('trade_offers', function (Blueprint $table) {
            $table->boolean('sender_confirmed')->default(false)->after('completed_at');
            $table->boolean('recipient_confirmed')->default(false)->after('sender_confirmed');
            $table->timestamp('sender_confirmed_at')->nullable()->after('recipient_confirmed');
            $table->timestamp('recipient_confirmed_at')->nullable()->after('sender_confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trade_offers', function (Blueprint $table) {
            $table->dropColumn(['sender_confirmed', 'recipient_confirmed', 'sender_confirmed_at', 'recipient_confirmed_at']);
        });
    }
};
