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
        Schema::create('elearning__discussion_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->morphs('likeable'); // can like discussions or replies
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');

            // Use a shorter unique constraint name to avoid MySQL identifier length limit
            $table->unique(['user_id', 'likeable_type', 'likeable_id'], 'discussion_likes_unique');
            // $table->index(['likeable_type', 'likeable_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__discussion_likes');
    }
};
