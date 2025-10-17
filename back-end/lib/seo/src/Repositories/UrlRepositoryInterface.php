<?php

namespace Newnet\Seo\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface UrlRepositoryInterface extends BaseRepositoryInterface
{
    public function findByRequestPath($path, $columns = ['*']);

    public function whereMathRequestPath($path, $columns = ['*']);
}
