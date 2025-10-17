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
        Schema::create('elearning__users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('code')->nullable();
            $table->string('social_platform', 20)->nullable();
            $table->string('social_avatar')->nullable();
            $table->string('skill')->nullable();
            $table->text('introduce')->nullable();
            $table->string('bio')->nullable();
            $table->string('video_intro')->nullable();
            $table->string('star')->nullable();
            $table->string('phone')->nullable();
            $table->string('facebook')->nullable();
            $table->string('github')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('topic')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_enable')->default(false);
            $table->boolean('is_teacher')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__users');
    }
};
