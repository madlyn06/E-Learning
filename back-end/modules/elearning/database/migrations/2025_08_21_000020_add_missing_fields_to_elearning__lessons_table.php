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
        Schema::table('elearning__lessons', function (Blueprint $table) {
            // Step 1: Remove deprecated fields
            $this->removeDeprecatedFields($table);
            
            // Step 2: Add new fields for enhanced functionality
            $this->addNewFields($table);
            
            // Step 3: Add performance indexes
            $this->addPerformanceIndexes($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__lessons', function (Blueprint $table) {
            // Step 1: Remove added fields
            $this->removeNewFields($table);
            
            // Step 2: Remove performance indexes
            $this->removePerformanceIndexes($table);
            
            // Step 3: Re-add deprecated fields
            $this->restoreDeprecatedFields($table);
        });
    }

    /**
     * Remove deprecated fields from lessons table
     */
    private function removeDeprecatedFields(Blueprint $table): void
    {
        $table->dropColumn([
            'video',
            'video_type',
            'video_url',
            'is_selling',
            'is_published',
            'is_completed',
            'end_of_free',
        ]);
    }

    /**
     * Add new fields for enhanced lesson functionality
     */
    private function addNewFields(Blueprint $table): void
    {
        // Visual and metadata
        $table->integer('duration_minutes')->nullable();
        $table->json('metadata')->nullable();
    }

    /**
     * Add performance indexes for better query performance
     */
    private function addPerformanceIndexes(Blueprint $table): void
    {
        // Composite index for lesson ordering within sections
        $table->index(['section_id', 'position'], 'lessons_section_position_index');
        
        // Index for instructor's lessons
        $table->index(['user_id', 'is_enable'], 'lessons_user_enable_index');
    }

    /**
     * Remove new fields added in this migration
     */
    private function removeNewFields(Blueprint $table): void
    {
        $table->dropColumn([
            'duration_minutes',
            'metadata',
        ]);
    }

    /**
     * Remove performance indexes added in this migration
     */
    private function removePerformanceIndexes(Blueprint $table): void
    {
        $table->dropIndex('lessons_section_position_index');
        $table->dropIndex('lessons_user_enable_index');
    }

    /**
     * Restore deprecated fields that were removed
     */
    private function restoreDeprecatedFields(Blueprint $table): void
    {
        $table->boolean('is_selling')->default(false)->after('is_enable');
        $table->boolean('is_published')->default(true)->after('is_selling');
        $table->boolean('is_completed')->default(false)->after('is_published');
        $table->datetime('end_of_free')->nullable()->after('is_completed');
        $table->string('video')->nullable()->after('end_of_free');
        $table->string('video_type')->nullable()->after('video');
        $table->string('video_url')->nullable()->after('video_type');
    }
};
