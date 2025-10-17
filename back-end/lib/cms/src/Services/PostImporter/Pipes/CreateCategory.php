<?php

namespace Newnet\Cms\Services\PostImporter\Pipes;

use Closure;
use Illuminate\Support\Str;
use Newnet\Cms\Models\Category;
use Newnet\Seo\Models\Url;

class CreateCategory
{
    public function handle($payload, Closure $next)
    {
        $url = Url::whereRequestPath(Str::slug($payload['category']))->whereUrlableType(Category::class)->first();
        if (empty($url)) {
            $locale = app()->getLocale();
            $category = Category::where("name->{$locale}", $payload['category'])->first();
            if (empty($category)) {
                $category = Category::firstOrCreate([
                    'name' => $payload['category'],
                ]);
                $category->seourl()->create([
                    'request_path' => Str::slug($payload['category']),
                    'target_path' => 'cms/category/' . $post->id,
                ]);
            }
        } else {
            logger('Category: '. $payload['category']. ' already exists');
            $category = Category::find($url->urlable_id);
        }
        $payload['category'] = $category;
        return $next($payload);
    }
}
