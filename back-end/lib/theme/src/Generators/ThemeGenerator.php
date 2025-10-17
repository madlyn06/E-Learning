<?php

namespace Newnet\Theme\Generators;

use Illuminate\Console\Command as Console;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeGenerator
{
    protected string $themeName;

    protected bool $isDev;

    protected Console $console;

    protected array $folders = [

    ];

    protected array $files = [
        'composer.json.stub' => 'composer.json',
        'readme.md.stub' => 'readme.md',
        'screenshot.png.stub' => 'screenshot.png',
        'src/ThemeServiceProvider.php.stub' => 'src/ThemeServiceProvider.php',
    ];

    public function setConsole(Console $console): static
    {
        $this->console = $console;

        return $this;
    }

    public function setThemeName($themeName): static
    {
        $this->themeName = $themeName;

        return $this;
    }

    public function setIsDev($isDev): static
    {
        $this->isDev = $isDev;

        return $this;
    }

    public function generate(): void
    {
        if (File::isDirectory($this->getThemePath())) {
            $this->console->error(sprintf('Theme %s exists', $this->themeName));
            return;
        }

        $this->generateFolders();
        $this->generateFiles();
        $this->copyAssetsVendor();

        $this->console->info(sprintf('Theme "%s" created', $this->themeName));
    }

    protected function generateFolders(): void
    {
        foreach ($this->folders as $folder) {
            $path = $this->getThemePath() . '/' . $folder;

            File::makeDirectory($path, 0755, true);
        }
    }

    private function copyAssetsVendor(): void
    {
        File::copyDirectory(
            __DIR__.'/../../stubs/source-copy',
            $this->getThemePath()
        );
    }

    protected function generateFiles(): void
    {
        foreach ($this->files as $stub => $path) {
            $path = $this->getThemePath() . '/' . $this->replacement($path);

            if (!File::isDirectory($dir = dirname($path))) {
                File::makeDirectory($dir, 0775, true);
            }

            File::put($path, $this->getStubContent($stub));
        }
    }

    private function replacement($content): string
    {
        return str_replace([
            '__THEME_NAME__',
            '__THEME_CLASS_NAME__',
            '__THEME_FOLDER__',
            '__COMPOSER_NAME__',
        ], [
            $this->themeName,
            Str::studly($this->themeName),
            $this->getThemeFolder(),
            $this->isDev ? 'newnetcms/theme-'.$this->getThemeFolder() : 'themes/'.$this->getThemeFolder(),
        ], $content);
    }

    private function getThemePath(): string
    {
        if ($this->isDev) {
            return base_path('lib'.DIRECTORY_SEPARATOR.'theme-'.$this->getThemeFolder());
        } else {
            return base_path('themes'.DIRECTORY_SEPARATOR.$this->getThemeFolder());
        }
    }

    private function getThemeFolder(): string
    {
        return Str::kebab($this->themeName);
    }

    private function getStubContent($stub): string
    {
        $content = File::get(__DIR__.'/../../stubs/'.$stub);

        return $this->replacement($content);
    }
}
