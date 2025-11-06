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
            $table->string('twitter')->nullable()->after('facebook');
            $table->string('instagram')->nullable()->after('twitter');
            $table->string('website')->nullable()->after('instagram');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__users', function (Blueprint $table) {
            $table->dropColumn('twitter');
            $table->dropColumn('instagram');
            $table->dropColumn('website');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone_number');
        });
    }
};
