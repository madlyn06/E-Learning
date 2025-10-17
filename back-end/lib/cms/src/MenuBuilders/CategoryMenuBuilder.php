<?php

namespace Newnet\Cms\MenuBuilders;

use Illuminate\Support\Facades\Route;
use Newnet\Cms\Models\Category;
use Newnet\Menu\MenuBuilders\BaseFrontendMenuBuilder;

class CategoryMenuBuilder extends BaseFrontendMenuBuilder
{
    public function getTitle()
    {
        return __('cms::menu-builder.category');
    }

    public function getViewName()
    {
        return 'cms::menu-builder.category';
    }

    public function getFrontendUrl()
    {
        try {
            if ($id = $this->args['id']) {
                return Category::find($id)->url;
            }
        } catch (\Exception $e) {}

        return '#';
    }

    public function isActive()
    {
        $itemId = $this->args['id'] ?? null;
        $routeId = Route::current()->parameter('id');
        $routeName = Route::current()->getName();
        if ($routeName === 'cms.web.category.detail' && $routeId == $itemId) {
            return true;
        }

        return false;
    }
}
