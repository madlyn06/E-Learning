<?php

namespace Newnet\Cms\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface StoryRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);

    public function findActive($id);
}
