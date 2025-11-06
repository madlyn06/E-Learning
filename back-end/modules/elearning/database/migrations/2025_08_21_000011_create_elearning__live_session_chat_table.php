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
        Schema::create('elearning__live_session_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->enum('type', ['text', 'question', 'answer', 'announcement'])->default('text');
            $table->boolean('is_private')->default(false);
            $table->unsignedBigInteger('reply_to_id')->nullable(); // for replies to specific messages
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('elearning__live_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->index(['session_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__live_session_chat');
    }
};
