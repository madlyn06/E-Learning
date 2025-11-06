<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Enrollment;
use Newnet\Core\Repositories\BaseRepository;

class EnrollmentRepository extends BaseRepository
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }
}
