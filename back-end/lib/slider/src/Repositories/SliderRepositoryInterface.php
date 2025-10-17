<?php

namespace Newnet\Slider\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface SliderRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);
}
