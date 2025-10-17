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
        Schema::create('ecommerce__discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['percent', 'amount'])->default('amount');
            $table->integer('value')->default(0);
            $table->text('description')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('products')->nullable();
            $table->text('categories')->nullable();
            $table->boolean('is_apply_all')->default(true);
            $table->integer('max_applies')->default(0)->nullable();
            $table->unsignedInteger('total_applied')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce__discounts');
    }
};
