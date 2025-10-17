<?php

namespace Newnet\Seo\Repositories\Eloquent;

use Newnet\Seo\Repositories\AdsRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class AdsRepository extends BaseRepository implements AdsRepositoryInterface
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
