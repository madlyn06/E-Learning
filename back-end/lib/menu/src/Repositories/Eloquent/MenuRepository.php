<?php

namespace Newnet\Menu\Repositories\Eloquent;

use Newnet\Menu\Repositories\MenuRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function findBySlug($slug, $columns = ['*'])
    {
        return $this->model->where('slug', $slug)->first($columns);
    }
}
