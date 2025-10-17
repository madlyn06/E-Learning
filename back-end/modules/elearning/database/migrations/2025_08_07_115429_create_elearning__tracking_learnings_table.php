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
        Schema::create('elearning__tracking_learnings', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
      $table->string('lesson_uuid');
      $table->unsignedBigInteger('user_id');
  
      $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
      $table->foreign('lesson_uuid')->references('uuid')->on('elearning__lessons')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__tracking_learnings');
    }
};
