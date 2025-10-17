<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Newnet\Setting\Http\Controllers\Admin\SettingController;

class CmsSettingController extends SettingController
{
  protected $view = 'cms::admin.setting.index';

  public function index()
  {
    $setting = setting()->all();
    $item = json_decode(json_encode($setting));
    if (!empty($item->faqs_gallery)) {
      $item->faqs_gallery = collect($item->faqs_gallery);
    }
    if (!empty($item->contact_gallery)) {
      $item->contact_gallery = collect($item->contact_gallery);
    }
    return view($this->view, compact('item'));
  }
}
