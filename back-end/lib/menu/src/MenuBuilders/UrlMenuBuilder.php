<?php

namespace Newnet\Menu\MenuBuilders;

class UrlMenuBuilder extends BaseFrontendMenuBuilder
{
    public function getTitle()
    {
        return __('menu::menu.url_menu_builder.panel_title');
    }

    public function getViewName()
    {
        return 'menu::menu-builder.url-menu-builder';
    }

    public function getFrontendUrl()
    {
        return $this->args['url'] ?? '#';
    }

    public function isActive()
    {
        $itemUrl = $this->args['url'] ?? null;
        return request()->fullUrl() == url($itemUrl);
    }
}
