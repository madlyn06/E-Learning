<?php

namespace Newnet\Menu\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface MenuItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getTree($menuId, $columns = ['*']);

    public function updateTree(array $data);
}
