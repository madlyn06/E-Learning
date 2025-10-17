<?php

namespace Newnet\Menu;

use Newnet\Menu\Models\Menu;
use Newnet\Menu\Repositories\Eloquent\MenuItemRepository;
use Newnet\Menu\Repositories\Eloquent\MenuRepository;
use Newnet\Menu\Repositories\MenuItemRepositoryInterface;
use Newnet\Menu\Repositories\MenuRepositoryInterface;

class FrontendMenuRenderer
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

    public function render($menuId, $view = null)
    {
        if (is_numeric($menuId)) {
            $menu = $this->menuRepository->find($menuId);
        } elseif (is_string($menuId)) {
            $menu = $this->menuRepository->findBySlug($menuId);
        } elseif ($menuId instanceof Menu) {
            $menu = $menuId;
        }

        if (empty($menu)) {
            return view('menu::menu-render.not-found')->with([
                'menuId' => $menuId,
            ]);
        }

        $menuItems = $this->menuItemRepository->getTree($menu->id);

        if ($view) {
            if (view()->exists($view)) {
                return view($view)->with([
                    'items' => $menuItems,
                ]);
            }

            $viewPath = "menus.{$view}";
            if (view()->exists($viewPath)) {
                return view($viewPath)->with([
                    'items' => $menuItems,
                ]);
            }
        }

        $viewPath = "menus.".$menu->slug;
        if (view()->exists($viewPath)) {
            return view($viewPath)->with([
                'items' => $menuItems,
            ]);
        }

        $viewPath = 'menus.default';
        if (view()->exists($viewPath)) {
            return view($viewPath)->with([
                'items' => $menuItems,
            ]);
        }

        $viewPath = 'menu::menu-render.default';
        if (view()->exists($viewPath)) {
            return view($viewPath)->with([
                'items' => $menuItems,
            ]);
        }
    }
}
