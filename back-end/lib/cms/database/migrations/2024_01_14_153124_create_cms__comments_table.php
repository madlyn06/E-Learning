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
		Schema::create('cms__comments', function (Blueprint $table) {
			$table->id();
			$table->string('name')->nullable();
			$table->string('email')->nullable();
			$table->string('title')->nullable();
			$table->string('phone')->nullable();
			$table->text('content');
			$table->unsignedBigInteger('post_id')->nullable();
			$table->boolean('is_from_admin')->default(false);
			$table->boolean('is_show_frontend')->default(false);
			$table->boolean('is_published')->default(false);
			$table->nestedSet();
			$table->integer('like')->default(0);
			$table->integer('dislike')->default(0);
			$table->timestamp('deleted_at')->nullable();

			$table->foreign('post_id')->references('id')->on('cms__posts')->onDelete('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('cms__comments');
	}
};
