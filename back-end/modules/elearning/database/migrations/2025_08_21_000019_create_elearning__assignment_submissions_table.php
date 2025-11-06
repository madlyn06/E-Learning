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
        Schema::create('elearning__assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('user_id');
            $table->text('submission_text')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('file_type')->nullable();
            $table->enum('status', ['submitted', 'graded', 'late', 'overdue'])->default('submitted');
            $table->integer('score')->nullable();
            $table->integer('max_score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('graded_at')->nullable();
            $table->unsignedBigInteger('graded_by')->nullable();
            $table->integer('attempt_number')->default(1);
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('elearning__assignments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('graded_by')->references('id')->on('elearning__users')->onDelete('set null');
            
            // indexes với tên ngắn gọn
            $table->index(['assignment_id', 'user_id', 'attempt_number'], 'assign_user_attempt_idx');
            $table->index(['assignment_id', 'status'], 'assign_status_idx');
            $table->index(['user_id', 'submitted_at'], 'user_submitted_idx');
            $table->index('status', 'status_idx');
            $table->index('submitted_at', 'submitted_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__assignment_submissions');
    }
};
