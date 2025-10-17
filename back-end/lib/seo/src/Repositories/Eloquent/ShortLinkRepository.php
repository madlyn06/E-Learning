<?php

namespace Newnet\Seo\Repositories\Eloquent;

use Newnet\Seo\Repositories\ShortLinkRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class ShortLinkRepository extends BaseRepository implements ShortLinkRepositoryInterface
{
  public function paginate($itemOnPage)
  {
      $data = $this->model->query();

      if ($code = request('name')) {
          $data->where(function ($q) use ($code) {
              foreach (explode(' ', $code) as $keyword) {
                  if ($keyword = trim($keyword)) {
                      $q->where('code', 'like', "%{$keyword}%");
                  }
              }
          });
      }

      return $data
          ->orderBy('id', 'desc')
          ->paginate($itemOnPage);
  }
}
