<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Newnet\Cms\Enums\ActionEnum;
use Newnet\Cms\Enums\CrawlHistoryEnum;
use Newnet\Cms\Enums\PostActionEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cms__crawl_histories', function (Blueprint $table) {
            $table->id();
            $table->text('urls');
            $table->text('prompt')->nullable();
            $table->string('css_selectors_except')->nullable();
            $table->text('title_prompt')->nullable();
            $table->text('replace_words_before')->nullable();
            $table->string('categories')->nullable();
            $table->integer('max_words')->default(500);
            $table->text('css_selectors')->nullable();
            $table->boolean('is_rewrite_title')->default(false);
            $table->enum('post_action', [
                PostActionEnum::PUBLISH->value,
                PostActionEnum::DRAFT->value,
                PostActionEnum::SCHEDULE->value,
            ])->default(PostActionEnum::PUBLISH->value);
            $table->enum('purpose_action', [
                ActionEnum::REWRITE->value,
                ActionEnum::TRANSLATE->value,
            ])->default(ActionEnum::TRANSLATE->value);
            $table->timestamp('schedule_at')->nullable();
            $table->enum('status', [
                CrawlHistoryEnum::RUNNING->value,
                CrawlHistoryEnum::COMPLETED->value,
                CrawlHistoryEnum::FAILED->value,
            ])->default(CrawlHistoryEnum::RUNNING->value);
            $table->longText('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms__crawl_histories');
    }
};
