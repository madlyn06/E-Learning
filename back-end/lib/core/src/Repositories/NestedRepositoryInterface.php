<?php

namespace Newnet\Core\Repositories;

interface NestedRepositoryInterface
{
    /**
     * @param $itemPerPage
     * @return mixed
     */
    public function paginateTree($itemPerPage);

    /**
     * @param $id
     * @param  int  $step
     * @return mixed
     */
    public function moveUp($id, $step = 1);

    /**
     * @param $id
     * @param  int  $step
     * @return mixed
     */
    public function moveDown($id, $step = 1);
}
