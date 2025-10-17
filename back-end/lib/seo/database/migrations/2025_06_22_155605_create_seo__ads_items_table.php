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
        Schema::create('seo__ads_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ads_id');
            $table->foreign('ads_id')->references('id')->on('seo__ads')->onDelete('CASCADE');
            $table->string('title');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo__ads_items');
    }
};
