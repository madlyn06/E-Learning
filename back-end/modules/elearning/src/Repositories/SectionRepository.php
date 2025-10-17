<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Section;
use Newnet\Core\Repositories\BaseRepository;

class SectionRepository extends BaseRepository
{
    public function __construct(Section $model)
    {
        parent::__construct($model);
    }
}
