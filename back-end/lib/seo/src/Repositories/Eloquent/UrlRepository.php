<?php

namespace Newnet\Seo\Repositories\Eloquent;

use Newnet\Seo\Repositories\UrlRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class UrlRepository extends BaseRepository implements UrlRepositoryInterface
{
    public function findByRequestPath($path, $columns = ['*'])
    {
        return $this->model->where('request_path', $path)->firstOrFail($columns);
    }

    public function whereMathRequestPath($path, $columns = ['*'])
    {
        return $this->model
            ->where('request_path', $path)
            ->get($columns);
    }
}
