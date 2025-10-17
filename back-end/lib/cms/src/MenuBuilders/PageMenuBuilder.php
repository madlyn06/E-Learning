<?php

namespace Newnet\Cms\MenuBuilders;

use Illuminate\Support\Facades\Route;
use Newnet\Cms\Models\Page;
use Newnet\Menu\MenuBuilders\BaseFrontendMenuBuilder;

class PageMenuBuilder extends BaseFrontendMenuBuilder
{
    public function getTitle()
    {
        return __('cms::menu-builder.page');
    }

    public function getViewName()
    {
        return 'cms::menu-builder.page';
    }

    public function getFrontendUrl()
    {
        try {
            if ($id = $this->args['id']) {
                return Page::find($id)->url;
            }
        } catch (\Exception $e) {}

        return '#';
    }

    public function isActive()
    {
        $itemId = $this->args['id'] ?? null;
        $routeId = Route::current()->parameter('id');
        $routeName = Route::current()->getName();
        if ($routeName === 'cms.web.page.detail' && $routeId == $itemId) {
            return true;
        }

        return false;
    }
}
