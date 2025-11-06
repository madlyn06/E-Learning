<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Review;
use Newnet\Core\Repositories\BaseRepository;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }
}
