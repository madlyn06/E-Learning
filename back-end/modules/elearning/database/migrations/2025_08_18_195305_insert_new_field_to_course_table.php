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
        Schema::table('elearning__courses', function (Blueprint $table) {
            $table->dropColumn('video');
            $table->dropColumn('video_type');
            $table->dropColumn('video_url');
            $table->dropColumn('course_purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__courses', function (Blueprint $table) {
            $table->dropColumn('requirements');
            $table->string('video')->nullable();
            $table->string('video_type')->nullable();
            $table->string('video_url')->nullable();
            $table->text('course_purpose')->nullable();
        });
    }
};
