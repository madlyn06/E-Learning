<?php

namespace Newnet\Menu;

use Illuminate\Foundation\AliasLoader;
use Newnet\Menu\Facades\FrontendMenu;
use Newnet\Menu\Facades\FrontendMenuBuilder;
use Newnet\Menu\MenuBuilders\UrlMenuBuilder;
use Newnet\Menu\Models\Menu;
use Newnet\Menu\Models\MenuItem;
use Newnet\Menu\Repositories\Eloquent\MenuItemRepository;
use Newnet\Menu\Repositories\Eloquent\MenuRepository;
use Newnet\Menu\Repositories\MenuItemRepositoryInterface;
use Newnet\Menu\Repositories\MenuRepositoryInterface;
use Newnet\Module\Support\BaseModuleServiceProvider;

class MenuServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton('frontend-menu-builder', FrontendMenuBuilderManager::class);
        $this->app->singleton('frontend-menu', FrontendMenuRenderer::class);

        $this->app->singleton(MenuRepositoryInterface::class, function () {
            return new MenuRepository(new Menu());
        });

        $this->app->singleton(MenuItemRepositoryInterface::class, function () {
            return new MenuItemRepository(new MenuItem());
        });

        AliasLoader::getInstance()->alias('FrontendMenuBuilder', FrontendMenuBuilder::class);
        AliasLoader::getInstance()->alias('FrontendMenu', FrontendMenu::class);

        require_once __DIR__.'/../helpers/helpers.php';
    }

    public function registerFrontendMenuBuilders()
    {
        \FrontendMenuBuilder::add(UrlMenuBuilder::class);
    }

    public function boot()
    {
        parent::boot();
    }
}
