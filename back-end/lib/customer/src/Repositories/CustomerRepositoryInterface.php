<?php

namespace Newnet\Customer\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function getByCondition(array $data);

    public function findInDate($column, $from, $to, $paginate = 10);
}
