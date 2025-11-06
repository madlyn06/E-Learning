<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\PaymentMethod;
use Newnet\Core\Repositories\BaseRepository;

class PaymentMethodRepository extends BaseRepository
{
    public function __construct(PaymentMethod $model)
    {
        parent::__construct($model);
    }
}
