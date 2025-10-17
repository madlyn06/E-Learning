<?php

namespace Newnet\Cms\Handlers;

use Illuminate\Support\Facades\Storage;
use Newnet\Cms\Common\CrawledPostTrait;
use Newnet\Cms\Enums\CrawlHistoryEnum;
use Newnet\Cms\Enums\CrawlHistoryItemEnum;
use Newnet\Cms\Exceptions\RewriteException;
use Newnet\Cms\Interface\CommandHandlerInterface;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Services\ChatGPTService;
use Newnet\Cms\Utils\StringUtils;
use Throwable;

class MergeCommandHandler implements CommandHandlerInterface
{
    use CrawledPostTrait;

    public function execute(CrawlHistory $history)
    {
        try {
            /** @var ChatGPTService */
            $chatGPTService = app(ChatGPTService::class);
            $allCrawlHistoryItems = $history->crawlHistoryItems;
            $history->crawlHistoryItems()->where('status', '!=', CrawlHistoryItemEnum::FAILED->value)->update(['status' => CrawlHistoryItemEnum::REWRITING]);
            $mergedContent = '';
            $titleMerged = '';
            $fileName = '';
            foreach ($allCrawlHistoryItems as $item) {
                $originFile = $item->origin_file_name;
                if (!$originFile) {
                    continue;
                }
                if (Storage::disk('local')->exists($originFile)) {
                    $content = Storage::disk('local')->get($originFile);
                    $mergedContent .= $content . "\n";
                    $titleMerged .= $item->origin_title . "\n";
                    $fileName .= '_' . $item->id;
                }
            }
            // Rewrite processing
            $prompt = $history->prompt ?? 'You are a content creator who rewrites HTML text using Vietnamese for easier understanding. Please do NOT rewrite or translate the attribute in element.';
            if (!$history->prompt) {
                $history->update(['prompt' => $prompt]);
            }
            $title = StringUtils::cutStringAtFirstOccurrence($titleMerged, ['-', '|']);
            if ($history->is_rewrite_title) {
                $titlePrompt = $history->title_prompt ?? 'You are a content creator, please rewrite this title for easy understand';
                $title = $chatGPTService->handleContentBaseOnPrompt($titlePrompt, $title);
            }

            $content = preg_replace('/[^\x{0000}-\x{FFFF}]+/u', '', $mergedContent);
            $contentHandled = $chatGPTService->handleContentBaseOnPrompt($prompt, $content);
            $year = now()->format('Y');
            $month = now()->format('m');
            // Store rewrote content to a file
            $fileName = "$year/$month/" . 'merged_' . $fileName . '.html';
            Storage::disk('local')->put($fileName, $contentHandled);
            foreach ($history->crawlHistoryItems as $item) {
                if ($item->status !== CrawlHistoryItemEnum::FAILED->value) {
                    $item->update([
                        'name' => $title,
                        'status' => CrawlHistoryItemEnum::REWROTE,
                        'handled_file_name' => $fileName,
                    ]);
                }
                if ($item->status == CrawlHistoryItemEnum::FAILED->value) {
                    $item->update([
                        'handled_file_name' => $fileName,
                        'name' => $title,
                    ]);
                }
            }
            // Get last history item to represent of all items in history
            $this->handlePostAfterProcessed($history->crawlHistoryItems->last());
        } catch (Throwable $th) {
            $history->update(['status' => CrawlHistoryEnum::FAILED, 'error_message' => $th->getMessage()]);
            $history->crawlHistoryItems()->update([
                'status' => CrawlHistoryItemEnum::FAILED,
                'message' => $th->getMessage(),
            ]);
            throw new RewriteException($th->getMessage(), 400);
        }
        $history->update(['status' => CrawlHistoryEnum::COMPLETED]);
    }
}
