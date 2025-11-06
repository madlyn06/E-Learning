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
        Schema::create('elearning__tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // hex color for UI display
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0); // how many times this tag is used
            $table->timestamps();

            $table->index('name');
            $table->index('slug');
            $table->index('is_active');
            $table->index('usage_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__tags');
    }
};
