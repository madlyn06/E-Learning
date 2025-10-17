<?php

namespace Newnet\Seo\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Newnet\Seo\Models\Meta;
use Newnet\Seo\Models\Url;

trait SeoableTrait
{
    protected $seoableAttributes = [];

    protected $modelSeourls;

    abstract public function getUrl();

    public static function bootSeoableTrait()
    {
        static::deleting(function ($model) {
            !$model->seometa || $model->seometa->delete();

            foreach ($model->seourls as $seourl) {
                $seourl->delete();
            }
        });

        static::saved(function (self $model) {
            $model->saveSeourlAttribute();
            $model->saveSeometaAttribute();
        });
    }

    public function initializeSeoableTrait()
    {
        $this->fillable[] = 'seometa';
        $this->fillable[] = 'seourl';
        $this->fillable[] = 'url';
    }

    public function seourl()
    {
        return $this
            ->morphOne(Url::class, 'urlable')
            ->where('locale', get_current_edit_locale());
    }

    public function seourls()
    {
        return $this->morphMany(Url::class, 'urlable');
    }

    public function seometa()
    {
        return $this->morphOne(Meta::class, 'metable');
    }

    public function saveSeometaAttribute()
    {
        if (isset($this->seoableAttributes['seometa'])) {
            $value = $this->seoableAttributes['seometa'];

            if ($this->seometa) {
                $this->seometa->update($value);
            } else {
                $this->seometa()->create($value);
            }
        }
    }

    public function saveSeourlAttribute()
    {
        if (isset($this->seoableAttributes['seourl'])) {
            $value = $this->seoableAttributes['seourl'];

            $value['target_path'] = parse_url($this->getUrl(), PHP_URL_PATH);
            $value['request_path'] = $value['request_path'] ?? Str::slug($this->name);

            if ($this->seourl) {
                $this->seourl->update($value);
            } else {
                $this->seourl()->create($value);
            }
        }
    }

    public function setSeometaAttribute($value)
    {
        $this->seoableAttributes['seometa'] = $value;
    }

    public function setSeourlAttribute($value)
    {
        $this->seoableAttributes['seourl'] = $value;
    }

    public function getUrlAttribute()
    {
        if (method_exists($this, 'getUrl')) {
            $targetPath = ltrim(parse_url($this->getUrl(), PHP_URL_PATH), '/');

            $seourls = $this->getSeoUrls($targetPath);

            $seourl = $seourls->where('locale', App::getLocale())->first();
            if (!$seourl) {
                $seourl = $seourls->first();
            }

            $seoUrl = object_get($seourl, 'request_path', $targetPath);

            return LaravelLocalization::localizeURL($seoUrl);
        }

        return LaravelLocalization::localizeURL($this->slug);
    }

    public function setUrlAttribute($value)
    {
        $this->seoableAttributes['seourl']['request_path'] = $value;
    }

    protected function getSeoUrls(string $targetPath)
    {
        if (!$this->modelSeourls) {
            $this->modelSeourls = Url::where('target_path', $targetPath)->get();
        }

        return $this->modelSeourls;
    }
}
