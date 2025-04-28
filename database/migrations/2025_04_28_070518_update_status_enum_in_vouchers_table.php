<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            DB::statement("ALTER TABLE vouchers MODIFY COLUMN status ENUM('available', 'in_cart', 'used', 'pending') DEFAULT 'available'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            DB::statement("ALTER TABLE vouchers MODIFY COLUMN status ENUM('available', 'in_cart', 'used') DEFAULT 'available'");
        });
    }
};
