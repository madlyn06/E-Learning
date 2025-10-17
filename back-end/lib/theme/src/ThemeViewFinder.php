<?php

namespace Newnet\Theme;

use Illuminate\View\FileViewFinder;

class ThemeViewFinder extends FileViewFinder
{
    protected $activeTheme;

    public function getViewFinder()
    {
        return app('view')->getFinder();
    }

    public function setActiveTheme(string $theme): void
    {
        if ($theme) {
            $this->clearThemes();

            $this->registerTheme($theme);

            $this->activeTheme = $theme;
        }
    }

    public function setHints($hints): void
    {
        $this->hints = $hints;
    }

    public function getThemePath(string $theme, string $path = null): string
    {
        return $this->resolvePath(
            config('cms.theme.base_path') . DIRECTORY_SEPARATOR . $theme . ($path ? DIRECTORY_SEPARATOR . $path : '')
        );
    }

    public function getThemeViewPath(string $theme = null): string
    {
        $theme = $theme ?? $this->getActiveTheme();

        return $this->getThemePath($theme, 'resources' . DIRECTORY_SEPARATOR . 'views');
    }

    public function getActiveTheme()
    {
        return $this->activeTheme;
    }

    public function clearThemes(): void
    {
        $paths = $this->getViewFinder()->getPaths();

        if ($this->getActiveTheme()) {
            if (($key = array_search($this->getThemeViewPath($this->getActiveTheme()), $paths)) !== false) {
                unset($paths[$key]);
            }
        }

        $this->activeTheme = null;
        $this->getViewFinder()->setPaths($paths);
    }

    public function registerTheme(string $theme): void
    {
        $this->getViewFinder()->prependLocation($this->getThemeViewPath($theme));

        $this->registerNameSpacesForTheme($theme);
    }

    public function registerNameSpacesForTheme(string $theme): void
    {
        $vendorViewsPath = $this->getThemeViewPath($theme) . DIRECTORY_SEPARATOR . 'vendor';

        if (is_dir($vendorViewsPath)) {
            $directories = scandir($vendorViewsPath);

            foreach ($directories as $namespace) {
                if ($namespace != '.' && $namespace != '..') {
                    $path = $vendorViewsPath . DIRECTORY_SEPARATOR . $namespace;
                    $this->getViewFinder()->prependNamespace($namespace, $path);
                }
            }
        }
    }
}
