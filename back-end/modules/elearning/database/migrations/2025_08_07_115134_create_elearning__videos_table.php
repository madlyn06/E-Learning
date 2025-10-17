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
        Schema::create('elearning__videos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('video_id');
            $table->text('description')->nullable();
            $table->integer('viewed')->nullable();
            $table->integer('like')->nullable();
            $table->integer('comment')->nullable();
            $table->boolean('is_enable')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__videos');
    }
};
