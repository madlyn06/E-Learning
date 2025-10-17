<?php

namespace Newnet\Cms\Services\PostImporter\Pipes;
use Closure;
use Illuminate\Support\Str;
use Newnet\Tag\Models\Tag;

class CreateTag
{
    public function handle($payload, Closure $next)
    {
        $posts = $payload['posts'];
        $tagsArr = [];
        $postsArr = [];
        foreach ($posts as $post) {
            $tags = $post['tags'];
            if (!empty($tags)) {
                foreach ($tags as $tagName) {
                    $tag = Tag::whereSlug(Str::slug($tagName))->first();
                    if (!$tag) {
                        $tag = Tag::create([
                            'name' => $tagName,
                            'slug' => Str::slug($tagName),
                        ]);
                    } else {
                        logger('Tag: '. $tagName. ' already exists');
                    }
                    $tagsArr[] = $tag;
                }
            }
            $post['tags'] = $tagsArr;
            $postsArr[] = $post;
        }
        $payload['posts'] = $postsArr;
        return $next($payload);
    }
}
