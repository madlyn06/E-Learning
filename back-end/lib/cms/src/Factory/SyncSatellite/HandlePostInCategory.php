<?php

namespace Newnet\Cms\Factory\SyncSatellite;

use Newnet\Cms\Models\Category;

class HandlePostInCategory
{
    public static function getPost(Category $category): array
    {
        $posts = $category->posts;
        $result = [
            'category' => $category->name,
        ];
        foreach ($posts as $post) {
            $item = [
                'name' => $post->name,
                'description' => $post->description,
                'content' => $post->content,
                'image' => $post->image->url ?? null,
            ];
            $tags = $post->tags;
            $tagsArr = [];
            foreach($tags as $tag) {
                $tagsArr[] = $tag->name;
            }
            $item['tags'] = $tagsArr;
            $result['posts'][] = $item;
        }
        return $result;
    }
}
