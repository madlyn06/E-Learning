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
            $table->integer('has_runned_cron_twice')->default(0)->after('append_internal_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms__posts', function (Blueprint $table) {
            $table->dropColumn('has_runned_cron_twice');
        });
    }
};
