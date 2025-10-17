<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Course;
use Newnet\Core\Repositories\BaseRepository;

class CourseRepository extends BaseRepository
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }
}
