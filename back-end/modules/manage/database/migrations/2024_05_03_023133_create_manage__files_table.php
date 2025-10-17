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
        Schema::create('manage__files', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('file_version')->nullable();
            $table->string('file_size')->nullable();
            $table->string('download_count')->nullable();
            $table->string('download_url')->nullable();
            $table->string('post_url')->nullable();
            $table->string('required')->nullable();
            $table->timestamp('published_date')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage__files');
    }
};
