<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Services\WebCrawlerService;

class CrawlController extends Controller
{
    protected WebCrawlerService $crawlerService;

    public function __construct(WebCrawlerService $webCrawlerService)
    {
        $this->crawlerService = $webCrawlerService;
    }

    public function index()
    {
        $item = null;
        return view('cms::admin.crawl.index', compact('item'));
    }

    /**
     * Execute the crawl command.
     *
     * @param Request $request The request object.
     * @return \Illuminate\Http\Response The response containing the status of the crawl.
     */
    public function execute(Request $request)
    {
        $urls = $request->input('urls');
        if (!$urls) {
            return redirect()->back()->with(['error' => 'URLs is required']);
        }
        $isExists = CrawlHistory::where('urls', 'like', '%'. $urls .'%')->first();
        if (!$isExists) {
            $history = $this->crawlerService->startCrawling($request);
        } else {
            $history = $isExists;
        }
        return redirect()->route('cms.admin.crawl-history-item.index', ['crawlHistoryId' => $history->id]);
    }
}
