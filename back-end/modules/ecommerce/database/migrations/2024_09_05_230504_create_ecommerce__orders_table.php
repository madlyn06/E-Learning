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
        Schema::create('ecommerce__orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->index();
            $table->string('email')->index();
            $table->enum('status', ['pending', 'approval', 'shiping', 'shipped', 'completed'])->default('pending');
            $table->decimal('total_price', 20, 2);
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 20, 2)->nullable();
            $table->text('shipping_address');
            $table->text('billing_address')->nullable();
            $table->text('note')->nullable();
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce__orders');
    }
};
