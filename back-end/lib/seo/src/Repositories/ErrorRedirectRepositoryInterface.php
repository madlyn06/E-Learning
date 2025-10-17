<?php

namespace Newnet\Seo\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface ErrorRedirectRepositoryInterface extends BaseRepositoryInterface
{
    public function findBy($key, $value, $columns = ['*']);
}
