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
        Schema::create('elearning__live_session_recordings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->string('recording_url');
            $table->string('recording_id')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('duration')->nullable(); // duration of recording
            $table->enum('status', ['processing', 'ready', 'failed'])->default('processing');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('elearning__live_sessions')->onDelete('cascade');
            
            $table->index('session_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__live_session_recordings');
    }
};
