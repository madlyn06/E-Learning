<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Coupon;
use Newnet\Core\Repositories\BaseRepository;

class CouponRepository extends BaseRepository
{
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }
}
