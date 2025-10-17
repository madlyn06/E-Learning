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
        Schema::create('cms__ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('stars');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('post_id');
            $table->string('name');
            $table->string('email');
            $table->boolean('is_published')->default(false);

            $table->foreign('post_id')->references('id')->on('cms__posts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms__ratings');
    }
};
