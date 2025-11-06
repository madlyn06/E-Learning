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
        Schema::create('elearning__categories', function (Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->nullable();
            $table->string('icon')->nullable();
            $table->nestedSet();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__categories');
    }
};
