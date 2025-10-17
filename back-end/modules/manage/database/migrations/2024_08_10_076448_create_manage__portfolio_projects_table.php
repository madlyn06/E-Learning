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
        Schema::create('manage__portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->longText('name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('client_name')->nullable();
            $table->string('year')->nullable();
            $table->string('author')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(1);

            $table->foreign('category_id')->references('id')->on('manage__portfolio_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage__portfolio_projects');
    }
};
