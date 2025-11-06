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
        Schema::create('elearning__notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('notification_type'); // course_updates, new_lessons, assignments, live_sessions, etc.
            $table->boolean('email_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('in_app_enabled')->default(true);
            $table->json('frequency_settings')->nullable(); // immediate, daily, weekly
            $table->json('time_settings')->nullable(); // preferred time for notifications
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('elearning__users')->onDelete('cascade');
            
            $table->unique(['user_id', 'notification_type'], 'notif_pref_user_type_unique'); 
            $table->index(['user_id', 'notification_type'], 'notif_pref_user_type_idx');
            $table->index('notification_type', 'notif_pref_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning__notification_preferences');
    }
};
