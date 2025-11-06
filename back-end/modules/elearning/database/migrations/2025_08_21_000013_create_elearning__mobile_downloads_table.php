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
        Schema::create('elearning__mobile_downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->enum('status', ['pending', 'downloading', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->integer('progress')->default(0); // percentage
            $table->bigInteger('total_size')->nullable(); // in bytes
            $table->bigInteger('downloaded_size')->default(0); // in bytes
            $table->string('download_path')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
            
            $table->unique(['user_id', 'course_id']); // one download per user per course
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__mobile_downloads');
    }
};
