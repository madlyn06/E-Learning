<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Newnet\Cms\Repositories\SatelliteRepositoryInterface;
use Newnet\Core\Repositories\AuthorRepositoryInterface;
use Newnet\Core\Repositories\AuthorRepositoryTrait;
use Newnet\Core\Repositories\BaseRepository;

class SatelliteRepository extends BaseRepository implements SatelliteRepositoryInterface, AuthorRepositoryInterface
{
    use AuthorRepositoryTrait;


    public function paginate($itemPerPage)
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
            ->with('category')
            ->orderBy('id', 'desc')
            ->paginate($itemPerPage);
    }
}
