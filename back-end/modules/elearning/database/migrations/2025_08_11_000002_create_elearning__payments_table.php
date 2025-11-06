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
        Schema::create('elearning__payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->unique(); // Unique reference ID for this payment
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id')->nullable(); // Course being purchased
            $table->unsignedBigInteger('membership_id')->nullable(); // Membership being purchased
            $table->unsignedBigInteger('payment_method_id');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('VND');
            $table->string('status', 20)->default('pending'); // pending, completed, failed, refunded, cancelled
            $table->text('transaction_data')->nullable(); // JSON data from payment gateway
            $table->string('transaction_id')->nullable(); // ID from payment gateway
            $table->text('payment_url')->nullable(); // URL to redirect user for payment
            $table->text('qr_code')->nullable(); // QR code data or image path
            $table->dateTime('expires_at')->nullable(); // Payment expiration time
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('elearning__courses')->onDelete('set null');
            $table->foreign('membership_id')->references('id')->on('elearning__memberships')->onDelete('set null');
            $table->foreign('payment_method_id')->references('id')->on('elearning__payment_methods')->onDelete('restrict');

            // Ensure only one of course_id or membership_id is set
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__payments');
    }
};
