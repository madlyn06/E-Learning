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
        Schema::create('elearning__orders', function (Blueprint $table) {
            $table->id();
      $table->uuid('uuid')->index();
      $table->string('order_no')->nullable()->index();
      $table->unsignedBigInteger('user_id')->index();
      $table->string('code')->nullable()->index();
      $table->unsignedBigInteger('course_id');
      $table->decimal('price', 20, 4)->default(0);
      $table->string('payment_method')->nullable();
      $table->integer('province_id')->nullable();
      $table->integer('district_id')->nullable();
      $table->integer('township_id')->nullable();
      $table->string('address')->nullable();
      $table->enum('status', ['CREATED', 'PENDING', 'PAID', 'FAILED', 'CANCELLED']);
      $table->boolean('is_active')->default(false);
      $table->timestamp('actived_at')->nullable();

      $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
      
      $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__orders');
    }
};
