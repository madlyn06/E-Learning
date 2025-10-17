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
        Schema::create('ecommerce__ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('stars');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->string('email');
            $table->boolean('is_published')->default(false);

            $table->foreign('product_id')->references('id')->on('ecommerce__products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce__ratings');
    }
};
