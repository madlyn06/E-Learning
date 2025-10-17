<?php


namespace Newnet\Menu\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Menu\Services\MenuService;

class MenuController extends Controller
{
  /** @var MenuService */
  protected $menuService;

  public function __construct(MenuService $menuService)
  {
    $this->menuService = $menuService;
  }

  public function getMenuItem($key): array
  {
    return [
      'data' => $this->menuService->getMenuItemByKey($key)
    ];
  }
}
