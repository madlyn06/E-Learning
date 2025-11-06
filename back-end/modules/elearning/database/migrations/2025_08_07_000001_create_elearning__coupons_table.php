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
        Schema::create('elearning__coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2); // Percentage or fixed amount
            $table->decimal('minimum_purchase', 10, 2)->nullable();
            $table->decimal('maximum_discount', 10, 2)->nullable(); // For percentage discounts
            $table->integer('usage_limit_per_coupon')->nullable(); // Total times the coupon can be used
            $table->integer('usage_limit_per_user')->nullable(); // Times a single user can use this coupon
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_one_time')->default(false); // If true, can only be used once per user
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('elearning__users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__coupons');
    }
};
