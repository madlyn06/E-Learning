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
        Schema::table('cms__story_items', function (Blueprint $table) {
            $table->smallInteger('auto_play_after')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms__story_items', function (Blueprint $table) {
            $table->dropColumn('auto_play_after');
        });
    }
};
