<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Newnet\Cms\Models\Page;
use Newnet\Cms\Repositories\StorySettingRepositoryInterface;
use Newnet\Core\Repositories\AuthorRepositoryInterface;
use Newnet\Core\Repositories\AuthorRepositoryTrait;
use Newnet\Core\Repositories\BaseRepository;
use Newnet\Core\Repositories\NestedRepositoryTrait;

class StorySettingRepository extends BaseRepository implements StorySettingRepositoryInterface, AuthorRepositoryInterface
{
    use AuthorRepositoryTrait;
    use NestedRepositoryTrait;

    /**
     * Get Page Active by Slug
     *
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Page
     */
    public function findBySlug($slug)
    {
        return $this->model
            ->where('is_active', 1)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Get Page Active by ID
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Page
     */
    public function findActive($id)
    {
        return $this->model
            ->where('is_active', 1)
            ->where('id', $id)
            ->firstOrFail();
    }

    public function paginateTree($itemPerPage)
    {
        $data = $this->model->query();

        if ($name = request('name')) {
            $data->where(function ($q) use ($name) {
                foreach (explode(' ', $name) as $keyword) {
                    if ($keyword = trim($keyword)) {
                        $q->where('name', 'like', "%{$keyword}%");
                    }
                }
            });
        }

        return $data
            ->orderBy('id', 'desc')
            ->paginate($itemPerPage);
    }
}
