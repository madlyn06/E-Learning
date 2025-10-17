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
        Schema::table('seo__ads', function (Blueprint $table) {
            $table->string('btn_name')->nullable();
            $table->string('icon_btn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo__ads', function (Blueprint $table) {
            $table->dropColumn('btn_name');
            $table->dropColumn('icon_btn');
        });
    }
};
