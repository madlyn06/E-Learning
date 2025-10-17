<?php

namespace Newnet\Setting\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Media\Models\Media;
use Newnet\Setting\Models\Setting;

class SettingController extends Controller
{
  public function getConfigs()
  {
    $arrConfigs = [
      'site_title',
      'site_description',
      'site_contact_phone',
      'site_contact_phone_zalo',
      'site_contact_email',
      'site_contact_address',
      'setting_facebook',
      'setting_instagram',
      'setting_youtube',
      'setting_pinterest',
      'setting_linkedin',
      'setting_twitter',
    ];
    $arrImages = [
      'logo',
      'logo_dark',
      'logo_light',
      'favicon',
    ];
    $mergedArr = array_merge($arrConfigs, $arrImages);
    $items = Setting::whereIn('name', $mergedArr)->get();
    $arrResult = [];
    foreach ($items as $item) {
      if (in_array($item->name, $arrImages) && ($item->value)) {
        $media = Media::find($item->value);
        if ($media && $media->url) {
          $arrResult[$item->name] = [
            'url' => $media->url,
            'alt' => $media->alt,
            'description' => $media->description,
          ];
        }
      } else {
        $arrResult[$item->name] = $item->value;
      }
    }
    return ['data' => $arrResult];
  }

  public function getConfigsByKey($keys)
  {
    $arrayConfig = explode(',', $keys);
    $arrImages = [
      'logo',
      'logo_dark',
      'logo_light',
      'favicon',
      'seo_meta_og_image',
      'seo_meta_twitter_image',
      'reason_image',
      'banner_contact',
      'banner_why_choose_us',
      'banner_team',
      'banner_faqs',
      'banner_portfolio',
      'banner_support',
      'banner_consulting',
      'banner_testimonal',
      'banner_blog',
      'banner_service',
      'banner_category',
      'banner_404',
      'banner_doc_search',
      'loading_image',
      'loading_shop',
      'client_banner',
      'banner_download',
    ];
    $arrGallery = [
      'faqs_gallery',
      'contact_gallery',
    ];
    if (count($arrayConfig) > 1) {
      $items = Setting::whereIn('name', $arrayConfig)->get();
    } else {
      $items = Setting::where('name', $keys)->get();
    }
    $arrResult = [];
    foreach ($items as $item) {
      if (in_array($item->name, $arrImages) && ($item->value)) {
        $media = Media::find($item->value);
        if ($media && $media->url) {
          $arrResult[$item->name] = [
            'url' => $item->name == 'favicon' ? $media->thumb : $media->url,
            'alt' => $media->alt,
            'description' => $media->description,
          ];
        }
      } elseif (in_array($item->name, $arrGallery) && ($item->value)) {
        $medias = Media::whereIn('id', json_decode($item->value, true))->get();
        $arrGalleryImages = [];
        foreach ($medias as $media) {
          $arrGalleryImages[] = [
            'url' => $media->url,
            'alt' => $media->alt,
            'description' => $media->description,
          ];
        }
        $arrResult[$item->name] = $arrGalleryImages;
      } else {
        $arrResult[$item->name] = $item->value;
      }
    }
    return $arrResult;
  }
}
