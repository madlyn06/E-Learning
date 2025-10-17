<?php

namespace Newnet\Menu\Services;

use Newnet\Core\Utils\Common;
use Newnet\Menu\Exceptions\MenuException;
use Newnet\Menu\Models\Menu;
use Newnet\Menu\Repositories\MenuItemRepositoryInterface;

class MenuService
{
  /**
   * Get tree menu of menu items
   * @param string $key
   * @return array
   * @throws MenuException
   */
  public function getMenuItemByKey($key)
  {
    $menu = Menu::whereSlug($key)->first();
    if (!$menu) {
      throw new MenuException("The menu key $key does not exist");
    }

    $menuItems = app(MenuItemRepositoryInterface::class)->getTree($menu->id);

    return Common::buildMenuTree($menuItems);
  }
}
