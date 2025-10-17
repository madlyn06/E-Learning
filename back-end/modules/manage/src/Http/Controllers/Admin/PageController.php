<?php

namespace Modules\Manage\Http\Controllers\Admin;

use AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\PageEvent;
use Modules\Manage\Http\Requests\PageRequest;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Repositories\PageRepository;

class PageController extends Controller
{
    protected PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function index(Request $request)
    {
        $items = $this->pageRepository->paginate($request->input('max', 20));

        return view('manage::admin.page.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::PAGE);

        $latestItem = $this->pageRepository->getLatestOrder();
        $order = $latestItem ? $latestItem->sort_order + 1 : 1;

        return view('manage::admin.page.create', compact('order'));
    }

    public function store(PageRequest $request)
    {
        $item = $this->pageRepository->create($request->all());
        event(new PageEvent('created'));
        return redirect()
            ->route('manage.admin.pages.edit', [
                'page' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::page.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::PAGE);

        $item = $this->pageRepository->find($id);

        $order = $item->sort_order;
        return view('manage::admin.page.edit', compact('item', 'order'));
    }

    public function update(PageRequest $request, $id)
    {
        $this->pageRepository->updateById($request->all(), $id);
        event(new PageEvent('updated'));

        return back()->with('success', __('manage::page.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->pageRepository->delete($id);
        event(new PageEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::page.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.pages.index')
            ->with('success', __('manage::page.notification.deleted'));
    }
}
