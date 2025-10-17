<?php

namespace Newnet\Multilingual;

use Illuminate\Routing\Router;
use Newnet\Module\Support\BaseModuleServiceProvider;

class MultilingualServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        $this->registerMiddleware();
    }

    protected function registerMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('localize', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
        $router->aliasMiddleware('localizationRedirect', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class);
        $router->aliasMiddleware('localeSessionRedirect', \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class);
        $router->aliasMiddleware('localeCookieRedirect', \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class);
        $router->aliasMiddleware('localeViewPath', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class);
    }
}
