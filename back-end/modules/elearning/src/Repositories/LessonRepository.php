<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Lesson;
use Newnet\Core\Repositories\BaseRepository;

class LessonRepository extends BaseRepository
{
    public function __construct(Lesson $model)
    {
        parent::__construct($model);
    }
}
