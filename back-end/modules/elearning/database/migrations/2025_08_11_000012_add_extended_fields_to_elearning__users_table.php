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
        Schema::table('elearning__users', function (Blueprint $table) {
            // Basic user fields
            $table->string('profile_image')->nullable()->after('name');
            $table->string('default_language', 10)->default('en')->after('address');
            $table->json('settings')->nullable()->after('default_language');
            
            // Teacher-specific fields
            $table->text('teaching_experience')->nullable()->after('is_teacher');
            $table->text('education_background')->nullable()->after('teaching_experience');
            $table->text('certificates')->nullable()->after('education_background');
            $table->json('teaching_categories')->nullable()->after('certificates');
            $table->enum('teacher_status', ['not_applied', 'pending', 'approved', 'rejected'])->default('not_applied')->after('teaching_categories');
            $table->timestamp('teacher_approved_at')->nullable()->after('teacher_status');
            $table->text('rejection_reason')->nullable()->after('teacher_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'default_language',
                'settings',
                'teaching_experience',
                'education_background',
                'certificates',
                'teaching_categories',
                'teacher_status',
                'teacher_approved_at',
                'rejection_reason'
            ]);
        });
    }
};
