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
        Schema::create('elearning__reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('rating', 3, 1);  // Allows for ratings like 4.5
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
            
            // A user can only review a course once
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__reviews');
    }
};
