<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Ecommerce\Models\Order;
use Newnet\Core\Repositories\BaseRepository;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function paginate($itemOnPage)
    {
        $query = $this->model->query();

        if ($keyword = request('keyword')) {
            $query->where('order_no', 'like', "%{$keyword}%");
            $query->orWhere('email', 'like', "%{$keyword}%");
        }

        return $query
            ->orderBy('id', 'desc')
            ->paginate($itemOnPage);
    }
}
