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
        Schema::create('elearning__enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('price_paid', 10, 2);
            $table->decimal('original_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['active', 'completed', 'expired', 'cancelled'])->default('active');
            $table->dateTime('enrolled_at');
            $table->dateTime('expires_at')->nullable(); // If the course has an expiry
            $table->integer('completion_percentage')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('elearning__coupons')->onDelete('set null');
            
            // A user can only enroll in a course once (unless expired and re-enrolled)
            $table->unique(['user_id', 'course_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__enrollments');
    }
};
