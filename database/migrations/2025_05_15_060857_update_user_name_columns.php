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
        Schema::table('users', function (Blueprint $table) {
            // Drop the old 'name' column
            $table->dropColumn('name');

            // Rename 'first_name' to 'name'
            $table->renameColumn('first_name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback: Rename 'name' back to 'first_name'
            $table->renameColumn('name', 'first_name');

            // Add the 'name' column back
            $table->string('name')->nullable();
        });
    }
};
