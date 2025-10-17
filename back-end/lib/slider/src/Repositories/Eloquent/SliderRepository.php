<?php

namespace Newnet\Slider\Repositories\Eloquent;

use Newnet\Slider\Repositories\SliderRepositoryInterface;
use Newnet\Core\Repositories\AuthorRepositoryInterface;
use Newnet\Core\Repositories\AuthorRepositoryTrait;
use Newnet\Core\Repositories\BaseRepository;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface, AuthorRepositoryInterface
{
    use AuthorRepositoryTrait;

    public function findBySlug($slug)
    {
        return $this->model
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
