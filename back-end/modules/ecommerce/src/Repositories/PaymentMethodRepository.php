<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Ecommerce\Models\PaymentMethod;
use Newnet\Core\Repositories\BaseRepository;

class PaymentMethodRepository extends BaseRepository
{
    public function __construct(PaymentMethod $model)
    {
        parent::__construct($model);
    }
}
