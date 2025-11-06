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
            $table->string('duration_minutes')->nullable()->change();
            $table->string('original_name')->nullable();
            $table->string('size')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__lessons', function (Blueprint $table) {
            $table->string('duration_minutes')->change();
            $table->dropColumn('original_name');
            $table->dropColumn('size');
            $table->dropColumn('extension');
            $table->dropColumn('mime_type');
        });
    }
};
