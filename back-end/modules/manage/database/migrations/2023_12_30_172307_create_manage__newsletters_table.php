<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('manage__newsletters', function (Blueprint $table) {
      $table->id();
      $table->string('email')->index();
      $table->string('name')->nullable();
      $table->enum('status', ['created', 'pending', 'approval', 'cancelled'])->default('created');
      $table->string('message')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('newsletters');
  }
};
