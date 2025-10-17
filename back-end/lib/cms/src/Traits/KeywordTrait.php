<?php

namespace Newnet\Cms\Traits;

use Newnet\Cms\Models\Keyword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Collection;
use Newnet\Tag\Traits\TaggableTrait;

/**
 * Trait HasAppendInternalLinkTrait
 *
 * @package Newnet\Cms\Traits
 */
trait KeywordTrait
{
    use TaggableTrait;

    public static function bootKeywordTrait(): void
    {
        static::saved(function (self $model): void {
            if (request()->has('keywords')){
                $model->syncKeywords(request()->input('keywords'));
            }
        });

        static::deleted(function (self $model): void {
            $model->keywords()->detach();
        });
    }

    public function setKeywordsAttribute($keywords): void
    {
        static::saved(function (self $model) use ($keywords) {
            $model->syncKeywords($keywords);
        });
    }


    public function syncKeywords($keywords, bool $detaching = true)
    {
        $this->keywords()->sync($this->parseKeywords($keywords, null, null, true), $detaching);
        return $this;
    }

    protected function parseKeywords($rawKeywords, string $group = null, string $locale = null, $create = false): array
    {
        (is_iterable($rawKeywords) || is_null($rawKeywords)) || $rawKeywords = [$rawKeywords];

        [$strings, $keywords] = collect($rawKeywords)->map(function ($tag) {
            ! is_numeric($tag) || $tag = (int) $tag;

            ! $tag instanceof Model || $tag = [$tag->getKey()];
            ! $tag instanceof Collection || $tag = $tag->modelKeys();
            ! $tag instanceof BaseCollection || $tag = $tag->toArray();

            return $tag;
        })->partition(function ($item) {
            return is_string($item);
        });

        return $keywords->merge(Keyword::{$create ? 'findByNameOrCreate' : 'findByName'}($strings->toArray(), $group, $locale)->pluck('id'))->toArray();
    }
}
