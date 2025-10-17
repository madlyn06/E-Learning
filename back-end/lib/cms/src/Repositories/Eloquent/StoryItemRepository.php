<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Newnet\Cms\Repositories\StoryItemRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class StoryItemRepository extends BaseRepository implements StoryItemRepositoryInterface
{
    public function allOfStory($storyId, $columns = ['*'])
    {
        return $this->model
            ->where('story_id', $storyId)
            ->orderBy('sort_order', 'ASC')
            ->get($columns);
    }

    public function allActiveOfStory($storyId, $columns = ['*'])
    {
        return $this->model
            ->where('story_id', $storyId)
            ->where('is_active', true)
            ->orderBy('sort_order', 'ASC')
            ->get($columns);
    }

    public function getMaxSortOrderOfStory($storyId)
    {
        return $this->model
            ->where('story_id', $storyId)
            ->max('sort_order');
    }
}
