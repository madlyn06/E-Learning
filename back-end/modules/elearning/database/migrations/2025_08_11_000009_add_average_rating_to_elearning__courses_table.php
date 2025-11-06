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
        Schema::table('elearning__courses', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 1)->default(0)->after('end_of_course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elearning__courses', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });
    }
};
