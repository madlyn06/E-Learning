<?php

namespace Newnet\Cms\Handlers;

use Illuminate\Support\Facades\Storage;
use Newnet\Cms\Common\CrawledPostTrait;
use Newnet\Cms\Enums\CrawlHistoryEnum;
use Newnet\Cms\Enums\CrawlHistoryItemEnum;
use Newnet\Cms\Enums\PostActionEnum;
use Newnet\Cms\Exceptions\RewriteException;
use Newnet\Cms\Interface\CommandHandlerInterface;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Services\ChatGPTService;
use Newnet\Cms\Utils\StringUtils;
use Throwable;

class TranslateCommandHandler implements CommandHandlerInterface
{
    use CrawledPostTrait;

    public function execute(CrawlHistory $history)
    {
        /** @var ChatGPTService */
        $chatGPTService = app(ChatGPTService::class);
        $historyItems = $history->crawlHistoryItems()->where('status', CrawlHistoryItemEnum::CRAWLED)->get();
        foreach ($historyItems as $historyItem) {
            try {
                logger('Process handling for history item ID: ' . $historyItem->id);
                $historyItem->update(['status' => CrawlHistoryItemEnum::REWRITING]);
                $prompt = $historyItem->crawlHistory->prompt ?? 'You are a translator that translates HTML text into Vietnamese.';
                if (!$historyItem->crawlHistory->prompt) {
                    $historyItem->crawlHistory->update(['prompt' => $prompt]);
                }
                if (!$historyItem->origin_file_name) {
                    continue;
                }
                $bodyHtml = Storage::disk('local')->get($historyItem->origin_file_name);
                $title = StringUtils::cutStringAtFirstOccurrence($historyItem->origin_title, ['-', '|']);
                if ($history->is_rewrite_title) {
                    $title = $chatGPTService->handleContentBaseOnPrompt($prompt, $title);
                }
                $contentHandled = $chatGPTService->handleContentBaseOnPrompt($prompt, $bodyHtml);
                $year = now()->format('Y');
                $month = now()->format('m');
                // Store rewrote content to a file
                $fileName = "$year/$month/" . 'translated_' . $historyItem->id . '.html';
                Storage::disk('local')->put($fileName, $contentHandled);
                $historyItem->update([
                    'name' => $title,
                    'status' => CrawlHistoryItemEnum::REWROTE,
                    'handled_file_name' => $fileName,
                ]);
                // Handle post after rewriting, e.g. publish, draft, or set schedule to publish.
                $this->handlePostAfterProcessed($historyItem);
                if ($history->post_action == PostActionEnum::PUBLISH->value) {
                    $historyItem->update(['status' => CrawlHistoryItemEnum::PUBLISHED]);
                }
                if ($history->post_action == PostActionEnum::DRAFT->value) {
                    $historyItem->update(['status' => CrawlHistoryItemEnum::DRAFT]);
                }
            } catch (Throwable $th) {
                $history->update(['status' => CrawlHistoryEnum::FAILED, 'error_message' => $th->getMessage()]);
                $historyItem->update(['status' => CrawlHistoryItemEnum::FAILED, 'message' => $th->getMessage()]);
                throw new RewriteException($th->getMessage(), 400);
            }
        }
        $history->update(['status' => CrawlHistoryEnum::COMPLETED]);
    }
}
