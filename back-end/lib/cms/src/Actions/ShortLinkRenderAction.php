<?php

namespace Newnet\Cms\Actions;

use Newnet\Seo\Models\Ads;
use Newnet\Seo\Models\ShortLink;

class ShortLinkRenderAction
{
  public static function action($content)
  {
    $pattern = '/short_code\[(\d+)\]/';
    preg_match_all($pattern, $content, $matches);
    $short_codes = $matches[0];
    $numbers = $matches[1];

    $shortLinks = ShortLink::whereIn('code', $numbers)->get();
    foreach($shortLinks as $key => $item) {
      $realContent = add_shortlink($item);
      $content = str_replace($short_codes[$key], $realContent, $content);
    }
    return $content;
  }

  public static function renderAdsContent($content)
  {
    $isFromGoogle = filter_var(request('isFromGoogle'), FILTER_VALIDATE_BOOLEAN);
    $pattern = '/ads_short_code\[(.*?)\]/';
    preg_match_all($pattern, $content, $matches);
    $short_codes = $matches[0];
    $numbers = $matches[1];

    $ads = Ads::whereIn('code', $numbers)->get();
    foreach($ads as $key => $item) {
      $realContent = render_popup_show_code($item, $isFromGoogle);
      $content = str_replace($short_codes[$key], $realContent, $content);
    }
    return $content;
  }
}
