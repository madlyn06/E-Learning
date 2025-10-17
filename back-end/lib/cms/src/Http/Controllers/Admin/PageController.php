<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Http\Requests\PageRequest;
use Newnet\Cms\Models\Page;
use Newnet\Cms\Repositories\Eloquent\PageRepository;
use Newnet\Cms\Repositories\PageRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\NewItemEvent;

class PageController extends Controller
{
    /**
     * @var PageRepositoryInterface|PageRepository
     */
    private $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function index(Request $request)
    {
        $items = $this->pageRepository->paginateTree($request->input('max', 20));

        return view('cms::admin.page.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::PAGE);

        $item = new Page();
        $item->page_layout = config('cms.page.page_layout.default');
        $item->parent_id = $request->input('parent_id');

        return view('cms::admin.page.create', compact('item'));
    }

    public function store(PageRequest $request)
    {
        $page = $this->pageRepository->createWithAuthor($request->all());
        event(new NewItemEvent($page));
        return redirect()
            ->route('cms.admin.page.edit', $page)
            ->with('success', __('cms::page.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::PAGE);

        $item = $this->pageRepository->find($id);

        return view('cms::admin.page.edit', compact('item'));
    }

    public function update($id, PageRequest $request)
    {
        $page = $this->pageRepository->updateById($request->all(), $id);
        event(new NewItemEvent($page));

        return back()->with('success', __('cms::page.notification.updated'));
    }

    public function moveUp($id)
    {
        $this->pageRepository->moveUp($id);

        return back()->with('success', __('cms::page.notification.updated'));
    }

    public function moveDown($id)
    {
        $this->pageRepository->moveDown($id);

        return back()->with('success', __('cms::page.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->pageRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('cms::page.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.page.index')
            ->with('success', __('cms::page.notification.deleted'));
    }
}
