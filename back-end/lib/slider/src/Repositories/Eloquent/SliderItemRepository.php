<?php

namespace Newnet\Slider\Repositories\Eloquent;

use Newnet\Slider\Repositories\SliderItemRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class SliderItemRepository extends BaseRepository implements SliderItemRepositoryInterface
{
    public function allOfSlider($sliderId, $columns = ['*'])
    {
        return $this->model
            ->where('slider_id', $sliderId)
            ->orderBy('sort_order', 'ASC')
            ->get($columns);
    }

    public function allActiveOfSlider($sliderId, $columns = ['*'])
    {
        return $this->model
            ->where('slider_id', $sliderId)
            ->where('is_active', true)
            ->orderBy('sort_order', 'ASC')
            ->get($columns);
    }

    public function getMaxSortOrderOfSlider($sliderId)
    {
        return $this->model
            ->where('slider_id', $sliderId)
            ->max('sort_order');
    }
}
