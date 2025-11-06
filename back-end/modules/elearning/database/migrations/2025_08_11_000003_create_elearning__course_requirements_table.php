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
        Schema::create('elearning__course_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->text('requirement');
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__course_requirements');
    }
};
