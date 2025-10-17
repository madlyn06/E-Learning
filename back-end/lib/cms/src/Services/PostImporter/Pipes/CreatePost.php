<?php

namespace Newnet\Cms\Services\PostImporter\Pipes;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Newnet\Cms\Models\Post;
use Newnet\Media\MediaUploader;
use Newnet\Seo\Models\Url;
use Newnet\Acl\Models\Admin;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreatePost
{
    public function handle($payload, Closure $next): mixed
    {
        $posts = $payload['posts'];
        $postsArr = [];
        $adminAuthor = Admin::whereIsAdmin(true)->inRandomOrder()->first();
        foreach ($posts as $postItem) {
            $url = Url::whereRequestPath(Str::slug($postItem['name']))->whereUrlableType(Post::class)->first();
            if (!$url) {
                $locale = app()->getLocale();
                $post = Post::where("name->{$locale}",  $postItem['name'])->first();
                if (empty($post)) {
                    $post = Post::firstOrCreate([
                        'name' => $postItem['name'],
                        'description' => $postItem['description'],
                        'content' => $postItem['content'],
                    ]);
                    $post->author()->associate($adminAuthor);
                    $post->seourl()->create([
                        'request_path' => Str::slug($postItem['name']),
                        'target_path' => 'cms/post/' . $post->id,
                    ]);
                    $post->categories()->sync($payload['category']);
                    if (!empty($postItem['tags'])) {
                        $tagIds = array_map(fn($item): int => (int) $item->id, $postItem['tags']);
                        $post->tags()->sync($tagIds);
                    }
                    // Handle image
                    $media = $this->handleImageUrl($postItem['image']);
                    if ($media) {
                        $post->media()->attach($media->id, [
                            'group' => 'image',
                        ]);
                    }
                }
            } else {
                logger('Post: ' . $postItem['name'] . ' already exists');
                $post = Post::find($url->urlable_id);
            }
            $postsArr[] = $post;
        }
        $payload['posts'] = $postsArr;
        return $next($payload);
    }

    private function handleImageUrl($url)
    {
        $response = Http::get($url);
        if (!$response->successful()) {
            return null;
        }
        $imageContent = $response->body();
        $fileName = basename(parse_url($url, PHP_URL_PATH));

        $tempImagePath = sys_get_temp_dir() . '/' . $fileName;
        file_put_contents($tempImagePath, $imageContent);

        $uploadedFile = new UploadedFile($tempImagePath, $fileName);

        $media = app(MediaUploader::class)->setFile($uploadedFile)->upload();

        if (file_exists($tempImagePath)) {
            unlink($tempImagePath);
        }

        return $media;
    }
}
