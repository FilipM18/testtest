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
            // Add email verification column
            $table->timestamp('email_verified_at')->nullable();
            
            // Add remember token for "remember me" functionality
            $table->rememberToken();
            
            // Change address to nullable as we have separate addresses table
            $table->text('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropRememberToken();
            // We don't revert the address change to avoid data loss
        });
    }
};