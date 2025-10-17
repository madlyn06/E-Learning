<?php

use Newnet\Seo\Enums\TargetEnum;
use Newnet\Seo\Models\ShortLink;

if (!function_exists('add_shortlink'))
{
  function add_shortlink(ShortLink $shortCode)
  {
    if (!$shortCode) {
      return '';
    }
    $contentUrls = array_filter(explode(',', $shortCode->content_urls), function($item) {
      return !empty($item);
    });
    $randomlink = array_rand($contentUrls, 1);
    $thelink = $contentUrls[$randomlink];
    return '<a class="btn btn-primary" style="' . $shortCode->css . '" target="' . $shortCode->target . '" href="' . $thelink . '">' . $shortCode->text . '</a>';
  }
}

if (!function_exists('get_target_options'))
{
  /**
   * Get Category Parent Options
   *
   * @return array
   */
  function get_target_options()
  {
    $options = [];

    $targets = [TargetEnum::BLANK, TargetEnum::SELF];
    foreach ($targets as $item) {
      $options[] = [
        'value' => $item->value,
        'label' => ucfirst(strtolower($item->name)),
      ];
    }

    return $options;
  }
}

if (!function_exists('render_popup_show_code'))
{
  function render_popup_show_code($adsItem)
  {
    return view('seo::web.popup-form', compact('adsItem'))->render();
  }
}

if (!function_exists('render_button_show_code'))
{
  function render_button_show_code($adsItem, $isFromGoogle)
  {
    if (!$isFromGoogle) {
      return '';
    }
    return view('seo::web.count-down', compact('adsItem'))->render();
  }
}

if (!function_exists('render_link_to_redirect_new_page_guide'))
{
  function render_link_to_redirect_new_page_guide($adsItem)
  {
    return view('seo::web.link-page-guide', compact('adsItem'))->render();
  }
}
