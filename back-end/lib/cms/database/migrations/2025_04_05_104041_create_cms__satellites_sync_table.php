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
        Schema::create('cms__satellites_sync', function (Blueprint $table) {
            $table->id();
            $table->string('satellite_site');
            $table->text('message')->nullable();
            $table->enum('status', ['running', 'successful', 'failed'])->default('running');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms__satellites_sync');
    }
};
