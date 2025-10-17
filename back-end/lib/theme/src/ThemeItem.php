<?php

namespace Newnet\Theme;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeItem
{
    protected string $themeDir;

    protected array $attributes;

    public string $key;

    public function __construct($key, $themeDir)
    {
        $this->key = $key;
        $this->themeDir = $themeDir;
    }

    protected function getNameAttribute()
    {
        return $this->attributes['name'];
    }

    protected function getDescriptionAttribute()
    {
        return $this->attributes['description'];
    }

    protected function getScreenshotAttribute()
    {
        $image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAIAAADTED8xAAADMElEQVR4nOzVwQnAIBQFQYXff81RUkQCOyDj1YOPnbXWPmeTRef+/3O/OyBjzh3CD95BfqICMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMO0TAAD//2Anhf4QtqobAAAAAElFTkSuQmCC';

        return $this->attributes['screenshot'] ?? $image;
    }

    protected function parseThemeAttributes()
    {
        $fileJson = $this->themeDir.DIRECTORY_SEPARATOR.'composer.json';
        if (File::exists($fileJson)) {
            $reading = File::get($fileJson);
            $jsonContent = json_decode($reading, true);
            if (isset($jsonContent['extra']['theme'])) {
                $this->attributes = $jsonContent['extra']['theme'];
            }
        }
    }

    public function __get(string $key)
    {
        $this->parseThemeAttributes();

        $attributeMethod = 'get'.Str::studly($key).'Attribute';
        if (method_exists($this, $attributeMethod)) {
            return $this->{'get'.Str::studly($key).'Attribute'}();
        }

        return null;
    }
}
