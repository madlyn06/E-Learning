<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Ecommerce\Models\Discount;
use Newnet\Core\Repositories\BaseRepository;

class DiscountRepository extends BaseRepository
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }
}
