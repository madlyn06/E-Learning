<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Repositories\CrawlHistoryItemRepositoryInterface;
use Newnet\Cms\Services\ContentService;
use Newnet\Cms\Services\TranslateService;

class CrawlHistoryItemController extends Controller
{
    /**
     * @var CrawlHistoryItemRepositoryInterface|CrawlHistoryItemRepository
     */
    private $crawlHistoryItemRepository;

    public function __construct(CrawlHistoryItemRepositoryInterface $crawlHistoryItemRepository)
    {
        $this->crawlHistoryItemRepository = $crawlHistoryItemRepository;
    }

    public function index(Request $request, $crawlHistoryId)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CRAWL_HISTORY);
        $items = $this->crawlHistoryItemRepository->getByConditions([
            'crawl_history_id' => $crawlHistoryId,
        ])->get();
        $crawlHistory = !empty($items) ? ($items[0]->crawlHistory) : null;
        return view('cms::admin.crawl-history-item.index', compact('items', 'crawlHistory'));
    }

    public function error($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CRAWL_HISTORY);
        $item = $this->crawlHistoryItemRepository->find($id);

        return view('cms::admin.crawl-history-item.error', compact('item'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CRAWL_HISTORY);
        $item = $this->crawlHistoryItemRepository->find($id);
        $item->content = Storage::disk('local')->get($item->handled_file_name);

        return view('cms::admin.crawl-history-item.edit', compact('item'));
    }

    /**
     * reRewrite the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function reRewrite(Request $request, $id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CRAWL_HISTORY);
        $item = $this->crawlHistoryItemRepository->find($id);
        app(ContentService::class)->rewriteContent($item);
        if ($request->wantsJson()) {
            $item->refresh();
            return response()->json($item);
        }
        return redirect()
            ->route('cms.admin.crawl-history-item.index', $item)
            ->with('success', __('Đã rewrite thành công'));
    }

    public function destroy($id, Request $request)
    {
        $this->crawlHistoryItemRepository->delete($id);
        if ($request->wantsJson()) {
            Session::flash('success', __('Đã xoá lịch sử cào thành công'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.crawl-history.index')
            ->with('success', __('Đã xoá lịch sử cào thành công'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        $this->crawlHistoryItemRepository->deleteMultiple($ids);

        Session::flash('success', __('Đã xoá lịch sử cào thành công'));
        return response()->json(['success' => 200]);
    }
}
