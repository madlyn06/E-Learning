<?php

namespace Newnet\Cms\Common;

use Illuminate\Support\Facades\Storage;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Enums\PostActionEnum;
use Newnet\Cms\Services\ContentService;

trait CrawledPostTrait
{
    /**
     * Handle post has been processed
     */
    public function handlePostAfterProcessed($historyItem)
    {
        $post = new Post();
        $post->name = $historyItem->name;
        $post->content = Storage::disk('local')->get($historyItem->handled_file_name);
        switch ($historyItem->crawlHistory->post_action) { // PUBLISH, DRAFT, SCHEDULE
            case PostActionEnum::PUBLISH->value:
                $post->published_at = now();
                $post->save();
                $post->saveSeourlAttribute();
                $post->saveSeometaAttribute();
                $categories = $historyItem->crawlHistory->categories;
                if (!empty($categories)) {
                    $post->categories()->sync(json_decode($categories, true));
                }
                // Remove file translated
                // Storage::disk('local')->delete($historyItem->handled_file_name);
                // Storage::disk('local')->delete($historyItem->origin_file_name);
                break;
            case PostActionEnum::DRAFT->value:
                $post->is_active = false;
                $post->published_at = null;
                $post->save();
                $post->saveSeourlAttribute();
                $post->saveSeometaAttribute();
                $post->save();
                $categories = $historyItem->crawlHistory->categories;
                if (!empty($categories)) {
                    $post->categories()->sync(json_decode($categories, true));
                }
                // Remove file translated
                // Storage::disk('local')->delete($historyItem->handled_file_name);
                // Storage::disk('local')->delete($historyItem->origin_file_name);
                break;
            default:
                // With set schedule to publish, we will handle it in another command
                logger('Set schedule to publish at ' . $historyItem->crawlHistory->schedule_at ? $historyItem->crawlHistory->schedule_at->format('Y-m-d H:i:s') : null);
                break;
        }
        if ($post->id) {
            app(ContentService::class)->dispatch($post);
        }
    }
}
