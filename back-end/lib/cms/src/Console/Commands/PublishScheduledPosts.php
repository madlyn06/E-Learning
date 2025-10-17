<?php

namespace Newnet\Cms\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Newnet\Cms\Enums\CrawlHistoryEnum;
use Newnet\Cms\Enums\CrawlHistoryItemEnum;
use Newnet\Cms\Enums\PostActionEnum;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Services\ContentService;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $crawlHistories = CrawlHistory::wherePostAction(PostActionEnum::SCHEDULE->value)
            ->whereStatus(CrawlHistoryEnum::COMPLETED)
            ->where('schedule_at', '<=', $now)
            ->with('crawlHistoryItems')->get();
        if ($crawlHistories->isEmpty()) {
            return;
        }
        logger('Publishing scheduled posts...');
        foreach ($crawlHistories as $crawlHistory) {
            $this->publishScheduledCrawledPost($crawlHistory);
        }
        logger('Published scheduled posts successfully. Total: ' . $crawlHistories->count());
    }

    /**
     * Publishes a scheduled crawled post.
     *
     * @param CrawlHistory $crawlHistory The crawl history instance containing the details of the post to be published.
     * @return void
     */
    private function publishScheduledCrawledPost(CrawlHistory $crawlHistory)
    {
        logger('Starting to publish scheduled post. Crawl history ID: '. $crawlHistory->id);
        $crawlHistoryItems = $crawlHistory->crawlHistoryItems;
        foreach ($crawlHistoryItems as $item) {
            $post = new Post();
            $content = Storage::disk('local')->get($item->handled_file_name);
            $post->name = $item->name;
            $post->content = $content;
            $post->published_at = now();
            $post->save();
            $categories = $item->crawlHistory->categories;
            if ($categories) {
                $categories = explode(',', $categories);
                $post->categories()->sync($categories);
            }
            /** @var ContentService */
            app(ContentService::class)->dispatch($post);

            $item->update(['status' => CrawlHistoryItemEnum::PUBLISHED, 'published_at' => now()]);
            $this->info("Đã đăng bài viết ID = {$post->id}");
        }
        $crawlHistory->update(['schedule_at' => null]);
        logger('Published scheduled post successfully. Crawl history ID: ' . $crawlHistory->id);
    }
}
