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
        Schema::create('elearning__mobile_app_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('push_notifications_enabled')->default(true);
            $table->boolean('email_notifications_enabled')->default(true);
            $table->boolean('auto_download_enabled')->default(false);
            $table->boolean('offline_mode_enabled')->default(true);
            $table->string('download_quality')->default('medium'); // low, medium, high
            $table->boolean('auto_sync_enabled')->default(true);
            $table->integer('sync_interval')->default(15); // minutes
            $table->json('notification_preferences')->nullable();
            $table->json('ui_preferences')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->unique('user_id'); // one settings record per user
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__mobile_app_settings');
    }
};
