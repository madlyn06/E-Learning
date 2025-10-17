<?php

namespace Newnet\Theme\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

abstract class BaseThemeServiceProvider extends ServiceProvider
{
    public function getThemeNamespace()
    {
        $refClass = new \ReflectionClass($this);
        if ($refClass->hasConstant('THEME_NAMESPACE')){
            return $refClass->getConstant('THEME_NAMESPACE');
        } else {
            $themeContent = File::get($this->getThemePath('theme.json'));
            $themeJson = json_decode($themeContent);

            return $themeJson->name;
        }
    }

    public function register()
    {
        $this->registerHelper();
        $this->loadJsonTranslationsFrom($this->getThemePath('lang'));
    }

    public function boot()
    {
        $this->loadRoutes();
        $this->loadTranslationsFrom($this->getThemePath('lang'), $this->getThemeNamespace());
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
