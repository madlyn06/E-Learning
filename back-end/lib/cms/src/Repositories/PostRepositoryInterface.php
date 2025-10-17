<?php

namespace Newnet\Cms\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);

    public function paginateInCategory($category, $itemPerPage);
}
