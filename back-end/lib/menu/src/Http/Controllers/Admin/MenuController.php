<?php

namespace Newnet\Menu\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Menu\Http\Requests\MenuRequest;
use Newnet\Menu\MenuAdminMenuKey;
use Newnet\Menu\Repositories\Eloquent\MenuItemRepository;
use Newnet\Menu\Repositories\Eloquent\MenuRepository;
use Newnet\Menu\Repositories\MenuItemRepositoryInterface;
use Newnet\Menu\Repositories\MenuRepositoryInterface;

class MenuController extends Controller
{
    /**
     * @var MenuRepositoryInterface|MenuRepository
     */
    private $menuRepository;

    /**
     * @var MenuItemRepositoryInterface|MenuItemRepository
     */
    private $menuItemRepository;

    public function __construct(
        MenuRepositoryInterface $menuRepository,
        MenuItemRepositoryInterface $menuItemRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuItemRepository = $menuItemRepository;
    }

    public function index(Request $request)
    {
        $items = $this->menuRepository->paginate($request->input('max', 20));

        return view('menu::admin.menu.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(MenuAdminMenuKey::MENU);

        return view('menu::admin.menu.create');
    }

    public function store(MenuRequest $request)
    {
        $item = $this->menuRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('menu.admin.menu.edit', $item->id)
                ->with('success', __('menu::menu.notification.created'));
        }

        return redirect()
            ->route('menu.admin.menu.index')
            ->with('success', __('menu::menu.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(MenuAdminMenuKey::MENU);

        $item = $this->menuRepository->find($id);
        $menuItems = $this->menuItemRepository->getTree($item->id, ['id', 'parent_id', 'label']);

        return view('menu::admin.menu.edit', compact('item', 'menuItems'));
    }

    public function update(MenuRequest $request, $id)
    {
        $item = $this->menuRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('menu.admin.menu.edit', $item->id)
                ->with('success', __('menu::menu.notification.updated'));
        }

        return redirect()
            ->route('menu.admin.menu.index')
            ->with('success', __('menu::menu.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->menuRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('menu::menu.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('menu.admin.menu.index')
            ->with('success', __('menu::menu.notification.deleted'));
    }
}
