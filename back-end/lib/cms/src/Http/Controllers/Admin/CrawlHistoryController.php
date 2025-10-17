<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\CrawlHistoryRepositoryInterface;

class CrawlHistoryController extends Controller
{
     /**
     * @var CrawlHistoryRepositoryInterface|CrawlHistoryRepository
     */
    private $crawlHistoryRepository;

    public function __construct(CrawlHistoryRepositoryInterface $crawlHistoryRepository)
    {
        $this->crawlHistoryRepository = $crawlHistoryRepository;
    }

    public function index(Request $request)
    {
        $items = $this->crawlHistoryRepository->paginate($request->input('max', 20));

        return view('cms::admin.crawl-history.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $item = $this->crawlHistoryRepository->find($request->id);
        Post::create($data);
        return redirect()
            ->route('cms.admin.post.index')
            ->with('success', __('cms::post.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CRAWL_HISTORY);
        $item = $this->crawlHistoryRepository->find($id);
        $item->content = Storage::disk('local')->get($item->handled_file_name);

        return view('cms::admin.crawl-history.edit', compact('item'));
    }

    public function destroy($id, Request $request)
    {
        $this->crawlHistoryRepository->delete($id);
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
        $this->crawlHistoryRepository->deleteMultiple($ids);

        Session::flash('success', __('Đã xoá lịch sử cào thành công'));
        return response()->json(['success' => 200]);
    }
}
