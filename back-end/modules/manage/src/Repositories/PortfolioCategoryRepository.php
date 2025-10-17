<?php

namespace Modules\Manage\Repositories;

use Modules\Manage\Models\PortfolioCategory;
use Newnet\Core\Repositories\BaseRepository;

class PortfolioCategoryRepository extends BaseRepository
{
    public function __construct(PortfolioCategory $model)
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
}
