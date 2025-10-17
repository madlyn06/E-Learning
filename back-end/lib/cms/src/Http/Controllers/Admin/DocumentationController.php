<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Facades\DocumentationRender;

class DocumentationController extends Controller
{
  public function index()
  {
    return view('cms::admin.docs.index');
  }

  /**
   * @Route("/admin/documentation/{key}", name="cms::admin.documentation.detail")
   */
  public function detail($key)
  {
    AdminMenu::activeMenu(CmsAdminMenuKey::DOCUMENTATION);

    $view = DocumentationRender::getView($key);
    $label = DocumentationRender::getLabel($key);
    return view('cms::admin.docs.content', compact('view', 'label'));
  }
}
