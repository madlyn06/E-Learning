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
        Schema::create('elearning__discussion_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discussion_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_reply_id')->nullable(); // for nested replies
            $table->text('content');
            $table->boolean('is_best_answer')->default(false);
            $table->integer('like_count')->default(0);
            $table->timestamps();

            $table->foreign('discussion_id')->references('id')->on('elearning__discussions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('parent_reply_id')->references('id')->on('elearning__discussion_replies')->onDelete('cascade');
            
            $table->index(['discussion_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('parent_reply_id');
            $table->index('is_best_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__discussion_replies');
    }
};
