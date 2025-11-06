<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elearning__lessons', function (Blueprint $table) {
            $table->string('hls_master_playlist', 191)->nullable();
            $table->string('hls_1080p_path', 191)->nullable()->after('hls_master_playlist');
            $table->string('hls_720p_path', 191)->nullable()->after('hls_1080p_path');
            $table->string('hls_480p_path', 191)->nullable()->after('hls_720p_path');

            // Composite index
            $table->index(
                ['hls_master_playlist', 'hls_1080p_path', 'hls_720p_path', 'hls_480p_path'],
                'lessons_hls_paths_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('elearning__lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_hls_paths_index');
            $table->dropColumn([
                'hls_master_playlist',
                'hls_1080p_path',
                'hls_720p_path',
                'hls_480p_path',
            ]);
        });
    }
};
