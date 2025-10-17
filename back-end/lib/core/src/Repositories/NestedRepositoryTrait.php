<?php

namespace Newnet\Core\Repositories;

trait NestedRepositoryTrait
{
    public function paginateTree($itemPerPage)
    {
        return $this->model->withDepth()->defaultOrder()->paginate($itemPerPage);
    }

    public function moveUp($id, $step = 1)
    {
        $model = $this->find($id);
        $model->up($step);

        return $model;
    }

    public function moveDown($id, $step = 1)
    {
        $model = $this->find($id);
        $model->down($step);

        return $model;
    }
}
