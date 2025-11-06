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
        Schema::create('elearning__live_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('instructor_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('scheduled_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->enum('status', ['scheduled', 'live', 'ended', 'cancelled'])->default('scheduled');
            $table->string('meeting_url')->nullable();
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->integer('max_participants')->nullable();
            $table->boolean('is_recording_enabled')->default(false);
            $table->boolean('is_chat_enabled')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->index(['course_id', 'status']);
            $table->index(['instructor_id', 'scheduled_at']);
            $table->index('scheduled_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__live_sessions');
    }
};
