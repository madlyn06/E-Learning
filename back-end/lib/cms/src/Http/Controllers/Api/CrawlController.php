<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Newnet\Cms\Enums\CrawlHistoryItemEnum;
use Newnet\Cms\Exceptions\CrawlDataException;
use Newnet\Cms\Models\CrawlHistoryItem;

class CrawlController extends Controller
{
    /**
     * Check the status of a given request.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\Response The response indicating the status.
     */
    public function checkStatus(Request $request)
    {
        $id = $request->input('id');
        $history = CrawlHistoryItem::find($id);
        if (!$history) {
            throw new CrawlDataException('Resource not found');
        }
        return response()->json([
            'status' => $history->status,
            'label' => CrawlHistoryItemEnum::getLabel($history->status),
            'message' => $history->message,
            'data' => [
                'name' => $history->name,
                'content' => $history->handled_file_name ? Storage::disk('local')->get($history->handled_file_name) : null,
            ],
        ]);
    }

    /**
     * Get the crawl history.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\Response The response containing the crawl history.
     */
    public function getCrawlHistory(Request $request)
    {
        $id = $request->input('id');
        $history = CrawlHistoryItem::find($id);
        if (!$history) {
            throw new CrawlDataException('Crawl history not found');
        }
        return response()->json($history);
    }
}
