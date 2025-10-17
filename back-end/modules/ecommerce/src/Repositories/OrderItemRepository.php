<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Ecommerce\Models\OrderItem;
use Newnet\Core\Repositories\BaseRepository;

class OrderItemRepository extends BaseRepository
{
    public function __construct(OrderItem $model)
    {
        parent::__construct($model);
    }
}
