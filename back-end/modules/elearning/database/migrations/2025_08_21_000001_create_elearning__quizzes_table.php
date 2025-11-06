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
        Schema::create('elearning__quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('time_limit')->nullable(); // in minutes
            $table->integer('passing_score'); // percentage required to pass
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('elearning__lessons')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->index(['lesson_id', 'is_active']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__quizzes');
    }
};
