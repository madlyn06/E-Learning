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
        Schema::create('elearning__mobile_crash_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('device_id')->nullable();
            $table->string('app_version');
            $table->string('os_version');
            $table->string('device_model')->nullable();
            $table->text('crash_log');
            $table->text('stack_trace')->nullable();
            $table->string('crash_type')->nullable();
            $table->text('user_actions')->nullable(); // what user was doing before crash
            $table->json('device_info')->nullable(); // additional device information
            $table->enum('status', ['new', 'investigating', 'resolved', 'ignored'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('set null');
            
            $table->index('user_id');
            $table->index('app_version');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__mobile_crash_reports');
    }
};
