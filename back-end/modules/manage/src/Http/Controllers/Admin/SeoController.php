<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Manage\ManageAdminMenuKey;
use Newnet\AdminUi\Facades\AdminMenu;

class SeoController extends Controller
{
  public function index()
  {
    $items = [
      [
        'name' => __('manage::seo.team'),
        'key' => 'team',
      ],
      [
        'name' => __('manage::seo.page'),
        'key' => 'page',
      ],
      [
        'name' => __('manage::seo.service'),
        'key' => 'service',
      ],
      [
        'name' => __('manage::seo.category'),
        'key' => 'category',
      ],
      [
        'name' => __('manage::seo.post'),
        'key' => 'post',
      ],
      [
        'name' => __('manage::seo.story'),
        'key' => 'story',
      ],
      [
        'name' => __('manage::seo.faq'),
        'key' => 'faq',
      ],
      [
        'name' => __('manage::seo.client'),
        'key' => 'client',
      ],
      [
        'name' => __('manage::seo.contact'),
        'key' => 'contact',
      ],
      [
        'name' => __('manage::seo.about-us'),
        'key' => 'about-us',
      ],
      [
        'name' => __('manage::seo.search'),
        'key' => 'search',
      ],
      [
        'name' => __('manage::seo.rss'),
        'key' => 'rss',
      ],
      [
        'name' => __('manage::seo.portfolio'),
        'key' => 'portfolio',
      ],
      [
        'name' => __('manage::seo.shop'),
        'key' => 'shop',
      ],
      [
        'name' => __('manage::seo.download'),
        'key' => 'download',
      ]
    ];
    return view('manage::admin.seo.index', compact('items'));
  }

  public function edit($id)
  {
    AdminMenu::activeMenu(ManageAdminMenuKey::SEO);
    $setting = setting()->all();
    $item = json_decode(json_encode($setting));

    return view('manage::admin.seo.edit', compact('item', 'id'));
  }
}
