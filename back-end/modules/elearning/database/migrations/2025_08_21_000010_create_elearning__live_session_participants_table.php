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
        Schema::create('elearning__live_session_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['host', 'co-host', 'participant'])->default('participant');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_video_off')->default(true);
            $table->boolean('is_screen_sharing')->default(false);
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('elearning__live_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->unique(['session_id', 'user_id']); // prevent duplicate participants
            $table->index(['session_id', 'role']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__live_session_participants');
    }
};
