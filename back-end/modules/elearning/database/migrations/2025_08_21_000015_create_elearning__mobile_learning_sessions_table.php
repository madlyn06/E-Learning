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
        Schema::create('elearning__mobile_learning_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->enum('device_type', ['mobile', 'tablet'])->default('mobile');
            $table->string('app_version')->nullable();
            $table->string('os_version')->nullable();
            $table->json('session_data')->nullable(); // additional session information
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('set null');
            $table->foreign('lesson_id')->references('id')->on('elearning__lessons')->onDelete('set null');
            
            $table->index(['user_id', 'started_at']);
            $table->index(['course_id', 'started_at']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__mobile_learning_sessions');
    }
};
