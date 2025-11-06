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
        Schema::create('elearning__quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['started', 'in_progress', 'completed', 'abandoned'])->default('started');
            $table->integer('score')->nullable();
            $table->integer('max_score')->nullable();
            $table->decimal('percentage', 5, 2)->nullable(); // percentage score
            $table->boolean('passed')->nullable(); // whether user passed the quiz
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('elearning__quizzes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->unique(['quiz_id', 'user_id']); // one attempt per user per quiz
            $table->index(['quiz_id', 'user_id']);
            $table->index('status');
            $table->index('passed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__quiz_attempts');
    }
};
