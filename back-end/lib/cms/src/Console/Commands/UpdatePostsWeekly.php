<?php

namespace Newnet\Cms\Console\Commands;

use Illuminate\Console\Command;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Services\InternalLinkService;
use Newnet\Seo\Models\InternalLink;

class UpdatePostsWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnsoft:update-internal-link-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update internal link for post every week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating internal link for post...');
        $keywords = InternalLink::all(['name', 'value'])->toArray();

        $linkService = new InternalLinkService();
        $updatedCount = 0;

        Post::where('append_internal_link', false)->orderBy('id', 'desc')
        ->chunk(100, function ($posts) use ($keywords, $linkService, &$updatedCount) {
            foreach ($posts as $post) {
                $newContent = $linkService->linkifyExceptHeadings($post->content, $keywords);
                echo 'Updated Post ID: ' . $post->id . PHP_EOL;
                $dataUpdate = [
                    'content' => $newContent,
                    'append_internal_link' => false,
                ];
                // TODO Workaround fix for case link not append the first time run cron
                if ($post->has_runned_cron_twice >= 1) { // Fix for case run cron twice
                    $dataUpdate['append_internal_link'] = true;
                }
                if (!$dataUpdate['append_internal_link']) {
                    $dataUpdate['has_runned_cron_twice'] = $post->has_runned_cron_twice + 1;
                }
                $post->update($dataUpdate);
                $updatedCount ++;
            }
        });
        logger('Update internal link for post successfully!. Total updated posts: ' . $updatedCount);

        $this->info('Update internal link for post successfully!. Total updated posts: ' . $updatedCount);
    }
}
