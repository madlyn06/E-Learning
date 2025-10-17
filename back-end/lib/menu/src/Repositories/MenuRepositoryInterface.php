<?php

namespace Newnet\Menu\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface MenuRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug, $columns = ['*']);
}
