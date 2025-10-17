<?php

namespace Newnet\Cms\Actions;

use Illuminate\Support\Facades\App;
use Newnet\Seo\Repositories\UrlRepositoryInterface;

class HandleRouteAction
{
  public static function getTargetPath($path)
  {
    $matchRequestPath = app(UrlRepositoryInterface::class)->whereMathRequestPath($path);

    /** @var Url $urlRewrite */
    $urlRewrite = $matchRequestPath->where('locale', App::getLocale())->first();

    if (!$urlRewrite) {
      $urlRewrite = $matchRequestPath->first();
    }

    return $urlRewrite;
  }
}
