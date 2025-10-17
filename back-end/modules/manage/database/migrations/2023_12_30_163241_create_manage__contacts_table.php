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
    Schema::create('manage__contacts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email');
      $table->string('phone', 15)->nullable();
      $table->string('address')->nullable();
      $table->string('subject')->nullable();
      $table->text('content')->nullable();
      $table->unsignedBigInteger('category_id')->nullable();
      $table->boolean('is_published')->default(false);
      $table->enum('status', ['created', 'pending', 'replied'])->default('created');
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
    Schema::dropIfExists('contacts');
  }
};
