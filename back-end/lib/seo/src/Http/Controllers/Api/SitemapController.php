<?php

namespace Newnet\Seo\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Seo\Http\Resources\SitemapResource;
use Newnet\Seo\Models\Url;

class SitemapController extends Controller
{
  public function getSitemap($type)
  {
    if ($type != 'all') {
      $urls = Url::with('urlable')->where(['urlable_type' => $type])->get();
    } else {
      $urls = Url::with('urlable')->get();
    }
    return SitemapResource::collection($urls ?? []);
  }
}
