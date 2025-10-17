<?php

namespace Newnet\Seo\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface PreRedirectRepositoryInterface extends BaseRepositoryInterface
{
    public function findBy($key, $value, $columns = ['*']);
}
