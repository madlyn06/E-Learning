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
        Schema::create('elearning__quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->text('question');
            $table->enum('type', ['multiple_choice', 'single_choice', 'true_false', 'essay']);
            $table->json('options')->nullable(); // for multiple choice questions
            $table->string('correct_answer')->nullable(); // for multiple choice, single choice, true/false
            $table->integer('points')->default(1);
            $table->text('explanation')->nullable(); // explanation of the correct answer
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('elearning__quizzes')->onDelete('cascade');
            
            $table->index('quiz_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__quiz_questions');
    }
};
