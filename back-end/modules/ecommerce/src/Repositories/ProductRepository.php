<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Ecommerce\Models\Product;
use Newnet\Core\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function paginate($itemOnPage)
    {
        return $this->model->with('ratings')->orderBy('id', 'DESC')->paginate($itemOnPage);
    }
}
