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
        Schema::create('elearning__quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->text('user_answer');
            $table->boolean('is_correct');
            $table->integer('points_earned')->default(0);
            $table->text('feedback')->nullable(); // optional feedback for the answer
            $table->timestamps();

            $table->foreign('attempt_id')->references('id')->on('elearning__quiz_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('elearning__quiz_questions')->onDelete('cascade');
            
            $table->index(['attempt_id', 'question_id']);
            $table->index('is_correct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__quiz_answers');
    }
};
