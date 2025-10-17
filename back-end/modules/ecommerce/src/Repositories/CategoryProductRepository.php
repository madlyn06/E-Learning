<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Ecommerce\Models\CategoryProduct;
use Newnet\Core\Repositories\BaseRepository;

class CategoryProductRepository extends BaseRepository
{
    public function __construct(CategoryProduct $model)
    {
        parent::__construct($model);
    }
}
