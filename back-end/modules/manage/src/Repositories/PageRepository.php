<?php

namespace Modules\Manage\Repositories;

use Modules\Manage\Models\Page;
use Newnet\Core\Repositories\BaseRepository;

class PageRepository extends BaseRepository
{
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

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

    public function getLatestOrder()
    {
        return $this->model->latest('sort_order')->first();
    }
}
