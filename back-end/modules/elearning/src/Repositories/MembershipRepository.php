<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\MemberShip;
use Newnet\Core\Repositories\BaseRepository;

class MembershipRepository extends BaseRepository
{
    public function __construct(MemberShip $model)
    {
        parent::__construct($model);
    }
}
