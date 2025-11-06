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
        Schema::table('elearning__lessons', function (Blueprint $table) {
            $table->renameColumn('is_enable', 'is_enabled');
            $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__lessons', function (Blueprint $table) {
            $table->renameColumn('is_enabled', 'is_enable');
            $table->integer('position')->nullable();
        });
    }
};
