<?php

namespace Newnet\Seo\Repositories\Eloquent;

use Newnet\Seo\Repositories\ErrorRedirectRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class ErrorRedirectRepository extends BaseRepository implements ErrorRedirectRepositoryInterface
{
    public function findBy($key, $value, $columns = ['*'])
    {
        return $this->model->where($key, $value)->firstOrFail($columns);
    }

    public function paginate($itemOnPage)
    {
        $data = $this->model->query();

        if ($from_path = request('from_path')) {
            $data->where('from_path', 'like', "%{$from_path}%");
        }

        if ($to_url = request('to_url')) {
            $data->where('to_url', 'like', "%{$to_url}%");
        }

        return $data
            ->orderByDesc('created_at')
            ->paginate($itemOnPage);
    }
}
