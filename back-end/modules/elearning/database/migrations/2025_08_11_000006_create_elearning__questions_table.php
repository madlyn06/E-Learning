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
        Schema::create('elearning__questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->text('question');
            $table->json('choices');
            $table->integer('correct');
            $table->text('explanation')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('elearning__lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__questions');
    }
};
