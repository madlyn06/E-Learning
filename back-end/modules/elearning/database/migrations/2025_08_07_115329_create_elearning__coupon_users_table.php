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
        Schema::create('elearning__coupon_users', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 20, 4)->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('coupon_id')->index();
            $table->unsignedBigInteger('course_id')->index();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('elearning__coupons')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__coupon_users');
    }
};
