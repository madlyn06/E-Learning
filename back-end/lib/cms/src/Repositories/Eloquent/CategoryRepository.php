<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Newnet\Cms\Models\Category;
use Newnet\Cms\Repositories\CategoryRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;
use Newnet\Core\Repositories\NestedRepositoryInterface;
use Newnet\Core\Repositories\NestedRepositoryTrait;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface, NestedRepositoryInterface
{
    use NestedRepositoryTrait;


    public function create(array $data)
    {
        $model = parent::create($data);

        $this->model->fixTree();

        return $model;
    }

    public function updateById(array $data, $id)
    {
        $model = parent::updateById($data, $id);

        $this->model->fixTree();

        return $model;
    }

    public function findBySlug($slug)
    {
        return $this->model
            ->where('slug', $slug)
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
            ->withDepth()
            ->defaultOrder()
            ->paginate($itemPerPage);
    }

    public function listRoot()
    {
        return Category::defaultOrder()->whereIsRoot()->get();
    }
}
