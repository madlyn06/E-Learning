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
        Schema::create('elearning__discussion_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discussion_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('discussion_id')->references('id')->on('elearning__discussions')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('elearning__tags')->onDelete('cascade');
            
            // Use shorter unique constraint name
            $table->unique(['discussion_id', 'tag_id'], 'discussion_tags_unique');
            $table->index('discussion_id');
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__discussion_tags');
    }
};
