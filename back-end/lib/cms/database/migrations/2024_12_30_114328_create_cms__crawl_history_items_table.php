<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Newnet\Cms\Enums\CrawlHistoryItemEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cms__crawl_history_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crawl_history_id');
            $table->string('url');
            $table->text('origin_title')->nullable()->comment('The original title');
            $table->text('name')->nullable()->comment('The title after translated');
            $table->string('origin_file_name')->nullable();
            $table->string('handled_file_name')->nullable();
            $table->longText('content')->nullable();
            $table->enum('status', [
                CrawlHistoryItemEnum::ALREADY_CRAWL->value,
                CrawlHistoryItemEnum::PENDING->value,
                CrawlHistoryItemEnum::CRAWLING->value,
                CrawlHistoryItemEnum::CRAWLED->value,
                CrawlHistoryItemEnum::REWRITING->value,
                CrawlHistoryItemEnum::REWROTE->value,
                CrawlHistoryItemEnum::PUBLISHED->value,
                CrawlHistoryItemEnum::DRAFT->value,
                CrawlHistoryItemEnum::FAILED->value,
            ])->default(CrawlHistoryItemEnum::PENDING->value);
            $table->longText('message')->nullable()->comment('The error message when crawl failed');
            $table->timestamp('published_at')->nullable();

            $table->foreign('crawl_history_id')->references('id')->on('cms__crawl_histories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms__crawl_history_items');
    }
};
