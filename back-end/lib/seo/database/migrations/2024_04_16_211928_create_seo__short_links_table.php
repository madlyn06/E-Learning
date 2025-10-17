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
        Schema::create('seo__short_links', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->longText('content_urls');
            $table->string('text');
            $table->text('css')->nullable();
            $table->enum('target', ['_blank', '_self'])->default('_blank');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo__short_links');
    }
};
