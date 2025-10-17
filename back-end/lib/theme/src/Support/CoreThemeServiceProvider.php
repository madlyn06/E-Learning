<?php

namespace Newnet\Theme\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Newnet\Theme\Facades\Theme;

abstract class CoreThemeServiceProvider extends ServiceProvider
{
    abstract public function getThemeName(): string;

    public function register()
    {
        $this->registerHelper();
        $this->registerThemeView();
        $this->loadJsonTranslationsFrom($this->getThemePath('lang'));
    }

    public function boot()
    {
        $this->loadRoutes();
        $this->loadTranslationsFrom($this->getThemePath('lang'), $this->getThemeName());
        $this->publishes([
            $this->getThemePath('public') => public_path('themes/'.$this->getThemeName()),
        ], 'theme');
        Theme::set($this->getThemeName());
    }

    protected function loadRoutes()
    {
        $routeAdmin = $this->getThemePath('routes/admin.php');
        if (file_exists($routeAdmin)) {
            Route::middleware(config('core.admin_middleware'))
                ->domain(config('core.admin_domain'))
                ->prefix(config('core.admin_prefix'))
                ->group($routeAdmin);
        }

        $routeWeb = $this->getThemePath('routes/web.php');
        if (file_exists($routeWeb)) {
            Route::middleware(['web'])
                ->group($routeWeb);
        }

        $routeApi = $this->getThemePath('routes/api.php');
        if (file_exists($routeApi)) {
            Route::middleware(['api'])
                ->prefix('api')
                ->group($routeApi);
        }
    }

    protected function registerThemeView()
    {
        $viewFinder = $this->app['view']->getFinder();
        $viewFinder->prependLocation($this->getThemePath('resources/views'));

        $vendorViewsPath = $this->getThemePath('resources/views/vendor');
        if (is_dir($vendorViewsPath)) {
            $directories = scandir($vendorViewsPath);

            foreach ($directories as $namespace) {
                if ($namespace != '.' && $namespace != '..') {
                    $path = $vendorViewsPath . DIRECTORY_SEPARATOR . $namespace;
                    $viewFinder->prependNamespace($namespace, $path);
                }
            }
        }
    }

    protected function registerHelper()
    {
        $helperFile = $this->getThemePath('src/helpers.php');
        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }

    protected function getThemeDir()
    {
        $class_info = new \ReflectionClass($this);
        return dirname(dirname($class_info->getFileName()));
    }

    protected function getThemePath($path)
    {
        return $this->getThemeDir().DIRECTORY_SEPARATOR.$path;
    }
}
