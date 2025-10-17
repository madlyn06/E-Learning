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
        Schema::create('elearning__courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('name');
            $table->string('slug')->index()->nullable();
            $table->string('code');
            $table->string('type')->nullable();
            $table->string('level')->nullable();
            $table->decimal('price', 20, 4)->nullable();
            $table->decimal('sale_price', 20, 4)->nullable();
            $table->decimal('pre_price', 20, 4)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('students_count')->default(0);
            $table->integer('total_lesson')->default(0);
            $table->string('total_hour');
            $table->text('course_purpose')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('video')->nullable();
            $table->string('video_type')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_enable')->default(true);
            $table->boolean('is_selling')->default(false);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_coming_soon')->default(true);
            $table->text('announcement')->nullable();
            $table->boolean('is_pre_order')->default(false);
            $table->datetime('end_of_course')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__courses');
    }
};
