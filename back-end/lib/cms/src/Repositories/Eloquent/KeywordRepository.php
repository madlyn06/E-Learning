<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Newnet\Core\Repositories\BaseRepository;
use Newnet\Cms\Repositories\KeywordRepositoryInterface;

class KeywordRepository extends BaseRepository implements KeywordRepositoryInterface
{
    public function paginate($itemOnPage)
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
            ->paginate($itemOnPage);
    }
}
