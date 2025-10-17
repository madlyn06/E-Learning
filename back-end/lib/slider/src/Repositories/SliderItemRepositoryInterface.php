<?php

namespace Newnet\Slider\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface SliderItemRepositoryInterface extends BaseRepositoryInterface
{
    public function allOfSlider($sliderId, $columns = ['*']);

    public function allActiveOfSlider($sliderId, $columns = ['*']);

    public function getMaxSortOrderOfSlider($sliderId);
}
