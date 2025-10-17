<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Newnet\Cms\Repositories\CrawlHistoryRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class CrawlHistoryRepository extends BaseRepository implements CrawlHistoryRepositoryInterface
{
    public function paginate($max)
    {
        return $this->model->with('crawlHistoryItems')->orderBy('id', 'DESC')->paginate($max);
    }
}
