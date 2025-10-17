<?php

namespace Newnet\Cms\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Models\CrawlHistoryItem;
use Newnet\Cms\Enums\CrawlHistoryItemEnum;
use Throwable;

class WebCrawlerService
{
    /**
     * Starts the web crawling process based on the given request.
     *
     * @param \Illuminate\Http\Request $request The request object containing the parameters for the crawling process.
     * @return CrawlHistory The crawl history object.
     * @throws Throwable
     */
    public function startCrawling(Request $request): CrawlHistory|Throwable
    {
        $data = $request->all();
        if ($request->categories) {
            $data['categories'] = json_encode($request->categories);
        }
        $history = CrawlHistory::create($data);
        $arrUrls = array_filter(explode(',', $request->input('urls')));
        foreach ($arrUrls as $url) {
            $url = trim($url);
            // $isExsistUrl = CrawlHistoryItem::whereUrl($url)->whereNotNull('origin_file_name')->first();
            // if ($isExsistUrl) {
            //     $isExsistUrl->update(['message' => 'URL is already exists', 'status' => CrawlHistoryItemEnum::ALREADY_CRAWL]);
            //     continue;
            // }
            $historyItem = CrawlHistoryItem::create([
                'crawl_history_id' => $history->id,
                'status' => CrawlHistoryItemEnum::PENDING,
                'url' => $url,
            ]);
            try {
                $crawlResult = $this->handleCrawlPage($url, $historyItem);
                if (empty($crawlResult)) {
                    continue;
                }
                $historyItem = $historyItem->update([
                    'status' => CrawlHistoryItemEnum::CRAWLED,
                    'origin_title' => $crawlResult['title'],
                    'origin_file_name' => $crawlResult['origin_file_name'],
                ]);
            } catch (Throwable $e) {
                $historyItem->update(['status' => CrawlHistoryItemEnum::FAILED, 'message' => $e->getMessage()]);
            }
        }
        // If any items without file, we will stop run rewrite
        $isPushEvent = true;
        foreach ($history->crawlHistoryItems as $item) {
            if (!$item->url) {
                $isPushEvent = false;
                break;
            }
        }
        if ($isPushEvent) {
            event(new \Newnet\Cms\Events\CrawledPostEvent($history));
        }

        return $history;
    }

    /**
     * Handles the web crawling process for the given URL.
     *
     * @param string $url The URL to be crawled.
     * @return array
     */
    private function handleCrawlPage(string $url, CrawlHistoryItem $historyItem): array
    {
        $response = Http::get($url);
        $htmlContent = $response->body();
        $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'auto');
        $crawler = new Crawler($htmlContent);
        $history = $historyItem->crawlHistory;
        $cssSelectors = $history->css_selectors;

        if (!empty($cssSelectors)) {
            return $this->handleCrawlBaseOnCSSSelector($crawler, $historyItem);
        }

        $title = $crawler->filter('title')->text();

        $this->handleExceptDom($crawler, $history);

        $bodyContent = $crawler->filter('body')->html();
        $isAllowRewrite = $this->isAllowRewriteContent($bodyContent);
        if ($isAllowRewrite) {
            $year = now()->format('Y');
            $month = now()->format('m');
            $fileName = "$year/$month/" . 'origin_' . $historyItem->id . '_body.html';

            // Repalce words before rewriting
            if ($historyItem->crawlHistory->replace_words_before) {
                $bodyContent = app(ContentService::class)->handleReplaceWords($bodyContent, $historyItem->crawlHistory->replace_words_before);
            }
            Storage::disk('local')->put($fileName, $bodyContent);

            return [
                'title' => $title,
                'origin_file_name' => $fileName,
            ];
        }
        $historyItem->update(['status' => CrawlHistoryItemEnum::FAILED, 'message' => 'The content is too short to rewrite']);
        return [];
    }

    /**
     * Handles the web crawling process based on the given CSS selector.
     *
     * @param Crawler $crawler The crawler object containing the content to be crawled.
     * @param CrawlHistoryItem $historyItem The history item object containing the details of the crawling process.
     * @return array
     */
    private function handleCrawlBaseOnCSSSelector($crawler, $historyItem)
    {
        $cssSelectors = $historyItem->crawlHistory->css_selectors;
        $arrSelectors = array_map('trim', array_filter(explode(',', $cssSelectors)));

        $content = '';
        foreach ($arrSelectors as $selector) {
            $elements = $crawler->filter($selector);
            if ($elements->count() > 0) {
                $content .= $elements->html();
            }
        }
        $isAllowRewrite = $this->isAllowRewriteContent($content);
        if (!$isAllowRewrite) {
            $historyItem->update(['status' => CrawlHistoryItemEnum::FAILED, 'message' => 'The content is too short to rewrite']);
            return [];
        }
        $year = now()->format('Y');
        $month = now()->format('m');
        $this->handleExceptDom($crawler, $historyItem->crawlHistory);
        $fileName = "$year/$month/" . 'origin_' . $historyItem->id . '_body.html';
        Storage::disk('local')->put($fileName, $content);
        return [
            'title' => $crawler->filter('title')->text(),
            'origin_file_name' => $fileName,
        ];
    }

    /**
     * This method handle except element, class or id in body
     */
    private function handleExceptDom(&$crawler, $history)
    {
        $cssSelectorsToRemove = $history->css_selectors_except;
        if (!empty($cssSelectorsToRemove) && is_string($cssSelectorsToRemove)) {
            $crawler->filter($cssSelectorsToRemove)->each(function (Crawler $node) {
                $domElement = $node->getNode(0);
                if ($domElement && $domElement->parentNode) {
                    $domElement->parentNode->removeChild($domElement);
                }
            });
        }

        $crawler->filter('a')->each(function (Crawler $node) {
            $domElement = $node->getNode(0);
            if ($domElement && $domElement->parentNode) {
                while ($domElement->hasChildNodes()) {
                    $child = $domElement->firstChild;
                    $domElement->parentNode->insertBefore($child, $domElement);
                }
                if ($domElement->parentNode) {
                    $domElement->parentNode->removeChild($domElement);
                }
            }
        });
    }

    /**
     * Checks if the given body content is allowed to be rewritten.
     *
     * @param string $bodyContent The content of the body to check.
     * @return bool Returns true if the content is allowed to be rewritten, false otherwise.
     */
    private function isAllowRewriteContent($bodyContent)
    {
        $maxWords = setting('max_words', 500);
        if ($maxWords) {
            $wordCount = str_word_count(strip_tags($bodyContent));
            if ($wordCount < $maxWords) {
                return false;
            }
        }
        return true;
    }
}
