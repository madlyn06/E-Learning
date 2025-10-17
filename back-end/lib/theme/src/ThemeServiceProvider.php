<?php

namespace Newnet\Theme;

use Composer\Autoload\ClassLoader;
use Newnet\Module\Support\BaseModuleServiceProvider;
use Newnet\Theme\Console\Commands\ThemeLinkCommand;
use Newnet\Theme\Console\Commands\ThemeCreateCommand;
use Newnet\Theme\Facades\Theme;

class ThemeServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        require_once __DIR__.'/../helpers/helpers.php';

        $this->registerThemeConfig();

        $this->registerThemeFinder();
    }

    public function boot()
    {
        parent::boot();

        $this->activateTheme();

        if ($this->app->runningInConsole()) {
            $this->commands([
                ThemeLinkCommand::class,
                ThemeCreateCommand::class,
            ]);
        }
    }

    protected function registerThemeConfig()
    {
        $configViewPaths = config('view.paths');
        $configViewPaths[] = realpath(__DIR__.'/../resources/theme-default');
        $this->app['config']->set('view.paths', $configViewPaths);
    }

    protected function activateTheme()
    {
        $theme = config('cms.theme.current_theme') ?: setting('theme_name');
        if ($theme) {
            Theme::set($theme);

            if ($namespace = Theme::config('namespace')) {
                $composerLoader = new ClassLoader();
                $composerLoader->setPsr4($namespace, Theme::path('src'));
                $composerLoader->register();

                if ($provider = Theme::config('provider')) {
                    if (class_exists($provider)) {
                        $this->app->register($provider);
                    }
                }
            }
        }
    }

    protected function registerThemeFinder(): void
    {
        $this->app->singleton('theme.finder', function ($app) {
            $themeFinder = new ThemeViewFinder(
                $app['files'],
                $app['config']['view.paths'],
            );

            $themeFinder->setHints(
                $this->app->make('view')->getFinder()->getHints()
            );

            return $themeFinder;
        });

        $this->app->singleton('theme.manager', ThemeManager::class);
    }
}
