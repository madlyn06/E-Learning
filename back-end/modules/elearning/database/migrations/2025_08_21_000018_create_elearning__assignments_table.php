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
        Schema::create('elearning__assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('user_id'); // instructor who created the assignment
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->enum('type', ['essay', 'file_upload', 'multiple_choice', 'mixed'])->default('essay');
            $table->integer('max_score')->default(100);
            $table->integer('max_attempts')->default(1);
            $table->timestamp('due_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_late_submissions')->default(false);
            $table->integer('late_penalty_percentage')->default(0);
            $table->json('rubric')->nullable(); // grading criteria
            $table->json('attachments')->nullable(); // assignment files
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('elearning__lessons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->index(['lesson_id', 'is_active']);
            $table->index(['user_id', 'created_at']);
            $table->index('due_date');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__assignments');
    }
};
