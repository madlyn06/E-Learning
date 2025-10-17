<?php

namespace Modules\StaticBlock\Repositories;

use Modules\StaticBlock\Models\StaticBlock;
use Newnet\Core\Repositories\BaseRepository;

class StaticBlockRepository extends BaseRepository
{
    public function __construct(StaticBlock $model)
    {
        parent::__construct($model);
    }

    public function findBySlug($slug)
    {
       return $this->model->where('is_active', true)->where('slug', $slug)->first();
    }
}
