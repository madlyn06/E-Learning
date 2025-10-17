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
        Schema::create('elearning__lessons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('name');
            $table->enum('type', ['video', 'quiz', 'file'])->default('video');
            $table->string('slug')->index()->nullable();
            $table->unsignedBigInteger('chapter_id');
            $table->unsignedBigInteger('user_id');
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->text('lesson_purpose')->nullable();
            $table->boolean('is_free')->default(false);
            $table->boolean('is_enable')->default(true);
            $table->boolean('is_selling')->default(false);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_completed')->default(false);
            $table->string('video')->nullable();
            $table->string('video_id')->nullable();
            $table->string('video_type')->nullable();
            $table->string('video_url')->nullable();
            $table->datetime('end_of_free')->nullable();
            $table->integer('continue_id')->nullable();
            $table->integer('previous_id')->nullable();
            $table->integer('position');
            $table->index('position');

            $table->foreign('chapter_id')->references('id')->on('elearning__chapters')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__lessons');
    }
};
