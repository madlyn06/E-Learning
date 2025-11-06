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
        Schema::table('elearning__users', function (Blueprint $table) {
            $table->string('email_verified_at')->nullable();
            $table->time('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropColumn('last_login_at');
        });
    }
};
