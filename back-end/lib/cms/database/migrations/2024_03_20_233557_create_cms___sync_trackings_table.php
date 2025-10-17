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
        Schema::create('cms__sync_trackings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['running', 'successfully', 'failed']);
            $table->longText('message')->nullable();
            $table->unsignedInteger('processed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms__sync_trackings');
    }
};
