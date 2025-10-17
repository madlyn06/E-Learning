<?php

namespace Newnet\Theme;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class ThemeManager
{
    public function has($theme): bool
    {
        return File::exists($this->themePath($theme, 'theme.json'));
    }

    public function set($theme): void
    {
        $this->finder()->setActiveTheme($theme);

        $this->loadServiceProvider();
    }

    public function clear(): void
    {
        $this->finder()->clearThemes();
    }

    public function current(): ?string
    {
        return $this->finder()->getActiveTheme();
    }

    public function path($path = null): string
    {
        return $this->finder()->getThemePath($this->current(), $path);
    }

    public function themePath($theme, $path = null): string
    {
        return $this->finder()->getThemePath($theme, $path);
    }

    public function url($url): string
    {
        if (preg_match('/^((http(s?):)?\/\/)/i', $url)) {
            return $url;
        }

        $url = ltrim($url, '/');

        if (($position = strpos($url, '?')) !== false) {
            $baseUrl = substr($url, 0, $position);
            $params = substr($url, $position);
        } else {
            $baseUrl = $url;
            $params = '';
        }

        $assetsPath = $this->path('assets');
        $assetPath = str_replace(public_path(), '', $assetsPath);

        $fullUrl = $assetPath . '/' . $baseUrl;

        return $fullUrl . $params;
    }

    public function config($key = null, $default = null)
    {
        $config = [];
        if (File::exists($this->path('theme.json'))) {
            $configData = File::get($this->path('theme.json'));

            $config = json_decode($configData, true);
        }

        if ($key) {
            return Arr::get($config, $key, $default);
        }

        return $config;
    }

    public function setting($key = null, $default = null)
    {
        $config = [];
        if (File::exists($this->path('data.json'))) {
            $configData = File::get($this->path('data.json'));

            $config = json_decode($configData, true);
        }

        if ($key) {
            return Arr::get($config, $key, $default);
        }

        return $config;
    }

    protected function finder(): ThemeViewFinder
    {
        return app('theme.finder');
    }

    protected function loadServiceProvider(): void
    {

    }
}
