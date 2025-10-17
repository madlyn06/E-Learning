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
        Schema::create('elearning__transactions', function (Blueprint $table) {
            $table->id();
            $table->string('txt_id')->nullable();
            $table->string('order_id')->index();
            $table->string('request_id');
            $table->decimal('amount', 20, 4);
            $table->string('order_type')->nullable();
            $table->string('message');
            $table->text('extra_data')->nullable();
            $table->string('pay_type');
            $table->string('response_time');
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED']);
            $table->string('from_uri')->nullable();
            $table->foreign('order_id')->references('order_no')->on('elearning__orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__transactions');
    }
};
