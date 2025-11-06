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
            $table->foreignId('user_id')->nullable()->constrained('elearning__users')->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained('elearning__courses')->onDelete('set null');
            $table->foreignId('membership_id')->nullable()->constrained('elearning__memberships')->onDelete('set null');
            $table->foreignId('payment_method_id')->nullable()->constrained('elearning__payment_methods')->onDelete('set null');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->decimal('amount', 20, 2);
            $table->string('currency');
            $table->string('reference_id')->nullable();
            $table->text('transaction_data')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('qr_code')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
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
