<?php

namespace Newnet\Cms\Repositories;

use Newnet\Core\Repositories\BaseRepositoryInterface;

interface StoryItemRepositoryInterface extends BaseRepositoryInterface
{
    public function allOfStory($storyId, $columns = ['*']);

    public function allActiveOfStory($storyId, $columns = ['*']);

    public function getMaxSortOrderOfStory($storyId);
}
