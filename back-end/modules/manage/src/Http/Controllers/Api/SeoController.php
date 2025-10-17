<?php

namespace Modules\Manage\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Media\Models\Media;
use Newnet\Setting\Models\Setting;

class SeoController extends Controller
{
  public function getMetaData($key)
  {
    $seo_meta_title = $key . '_seo_meta_title';
    $seo_meta_description = $key . '_seo_meta_description';
    $seo_meta_keywords = $key . '_seo_meta_keywords';
    $seo_meta_og_title = $key . '_seo_meta_og_title';
    $seo_meta_og_description = $key . '_seo_meta_og_description';
    $seo_meta_og_image = $key . '_seo_meta_og_image';
    $seo_meta_twitter_title = $key . '_seo_meta_twitter_title';
    $seo_meta_twitter_description = $key . '_seo_meta_twitter_description';
    $seo_meta_twitter_image = $key . '_seo_meta_twitter_image';
    $arrSetting = [
      $seo_meta_title,
      $seo_meta_description,
      $seo_meta_keywords,
      $seo_meta_og_title,
      $seo_meta_og_description,
      $seo_meta_og_image,
      $seo_meta_twitter_title,
      $seo_meta_twitter_description,
      $seo_meta_twitter_image,
    ];
    $items = Setting::whereIn('name', $arrSetting)->get();
    $arrImages = [
      $seo_meta_og_image,
      $seo_meta_twitter_image,
    ];
    $search = $key.'_seo_meta_';
    $object = [];
    foreach ($items as $item) {
      $key = str_replace($search, "", $item->name);
      if (in_array($item->name, $arrImages) && ($item->value)) {
        $media = Media::find($item->value);
        if ($media->url) {
          $object[$key] = $media->url;
        }
      } else {
        $object[$key] = $item->value;
      }
    }
    return ['data' => $object];
  }
}
