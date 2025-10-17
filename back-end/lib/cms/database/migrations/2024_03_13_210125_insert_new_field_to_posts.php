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
        Schema::table('cms__posts', function (Blueprint $table) {
            $table->unsignedInteger('migrate_post_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms__posts', function (Blueprint $table) {
            $table->dropColumn('migrate_post_id');
        });
    }
};
