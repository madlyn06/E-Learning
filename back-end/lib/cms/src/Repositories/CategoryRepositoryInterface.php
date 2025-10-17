<?php

namespace Newnet\Cms\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);
}
