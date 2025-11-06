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
        Schema::table('elearning__enrollments', function (Blueprint $table) {
            // Add pending status enum
            $table->enum('status', ['pending', 'active', 'expired', 'completed', 'cancelled'])->default('pending')->change();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('elearning__payments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__enrollments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'expired', 'completed', 'cancelled'])->default('pending')->change();
            $table->dropColumn('payment_id');
        });
    }
};
