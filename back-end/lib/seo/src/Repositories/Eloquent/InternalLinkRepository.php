<?php

namespace Newnet\Seo\Repositories\Eloquent;

use Newnet\Seo\Repositories\InternalLinkRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class InternalLinkRepository extends BaseRepository implements InternalLinkRepositoryInterface
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
