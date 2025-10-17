<?php

namespace Newnet\Menu\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Menu\Events\MenuEvent;
use Newnet\Menu\Http\Requests\MenuItemRequest;
use Newnet\Menu\MenuAdminMenuKey;
use Newnet\Menu\Models\MenuItem;
use Newnet\Menu\Repositories\Eloquent\MenuItemRepository;
use Newnet\Menu\Repositories\Eloquent\MenuRepository;
use Newnet\Menu\Repositories\MenuItemRepositoryInterface;
use Newnet\Menu\Repositories\MenuRepositoryInterface;

class MenuItemController extends Controller
{
    /**
     * @var MenuItemRepositoryInterface|MenuItemRepository
     */
    private $menuItemRepository;

    /**
     * @var MenuRepositoryInterface|MenuRepository
     */
    private $menuRepository;

    public function __construct(
        MenuItemRepositoryInterface $menuItemRepository,
        MenuRepositoryInterface $menuRepository
    ) {
        $this->menuItemRepository = $menuItemRepository;
        $this->menuRepository = $menuRepository;
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(MenuAdminMenuKey::MENU);

        $menu = $this->menuRepository->find($request->input('menu_id'));
        $item = new MenuItem();
        $item->parent_id = $request->input('parent_id');

        return view('menu::admin.menu-item.create')->with([
            'menu' => $menu,
            'item' => $item,
        ]);
    }

    public function store(MenuItemRequest $request)
    {
        $item = $this->menuItemRepository->create($request->all());
        if ($request->input('continue')) {
            return redirect()
                ->route('menu.admin.menu-item.edit', $item->id)
                ->with('success', __('menu::menu-item.notification.created'));
        }

        return redirect()
            ->route('menu.admin.menu.edit', $item->menu_id)
            ->with('success', __('menu::menu-item.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(MenuAdminMenuKey::MENU);

        $item = $this->menuItemRepository->find($id);
        $menu = $item->menu;

        return view('menu::admin.menu-item.edit')->with([
            'menu' => $menu,
            'item' => $item,
        ]);
    }

    public function update(MenuItemRequest $request, $id)
    {
        $item = $this->menuItemRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return back()->with('success', __('menu::menu-item.notification.updated'));
        }

        return redirect()
            ->route('menu.admin.menu.edit', $item->menu_id)
            ->with('success', __('menu::menu-item.notification.updated'));
    }

    public function destroy($id)
    {
        $this->menuItemRepository->delete($id);
        event(new MenuEvent('deleted'));

        return response()->json([
            'success' => true,
            'id'      => $id,
        ]);
    }

    public function updateTree(Request $request)
    {
        $this->menuItemRepository->updateTree($request->input('menu', []));

        return response()->json([
            'success' => true,
            'message' => __('menu::menu.notification.updated'),
        ]);
    }
}
