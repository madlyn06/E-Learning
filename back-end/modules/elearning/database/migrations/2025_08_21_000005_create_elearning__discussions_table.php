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
        Schema::create('elearning__discussions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['question', 'discussion', 'announcement'])->default('discussion');
            $table->boolean('is_solved')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('reply_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->unsignedBigInteger('best_answer_id')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->index(['course_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index('is_solved');
            $table->index('is_pinned');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__discussions');
    }
};
