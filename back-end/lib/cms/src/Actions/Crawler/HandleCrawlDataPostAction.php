<?php

namespace Newnet\Cms\Actions\Crawler;

use DOMDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Cms\Exceptions\CrawlDataException;
use Newnet\Cms\Exceptions\CreateStoryException;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Post;
use Newnet\Seo\Models\Url;
use Illuminate\Support\Str;
use Newnet\Acl\Models\Admin;
use Newnet\Cms\Actions\HandleContentListableAction;
use Newnet\Cms\Models\SyncTracking;
use Newnet\Media\MediaUploader;
use Newnet\Media\Models\Media;
use Newnet\Tag\Models\Tag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Newnet\Cms\Actions\CreateStoryFromPostAction;

class HandleCrawlDataPostAction
{
    protected static $url = null;
    protected static $firstItem = null;
    protected static $totalPages = null;
    protected static $loopTime = 2;
    protected static $isSliceArray = true;
    protected static $postsNotInserted = [];
    protected static $admin = null;
    protected static $processed = null;

    /**
     * Start a new crawl data posts
     */
    public static function action($url)
    {
        self::$admin = Admin::whereIsAdmin(true)->inRandomOrder()->first();
        self::$url = $url;
        $response = Http::get($url . '?orderby=id&order=desc&per_page=12');
        if ($response->successful()) {
            echo 'Crawlling post page::: 1' . PHP_EOL;
            $totalPages = (int) $response->getHeader('X-WP-Totalpages')[0];
            self::$totalPages = $totalPages;
            $posts = json_decode($response->body(), true);
            if (empty($posts)) {
                return;
            }
            self::$firstItem = $posts[0];

            $latestPost = DB::table('latest_items')->where('name', 'post')->first();
            if (!$latestPost) {
                // Handle the first sync post
                for ($i = 1; $i < $totalPages; $i++) {
                    echo 'Crawlling post page::: ' . $i + 1 . PHP_EOL;
                    $response = Http::get($url . '?page=' . $i + 1 . '&orderby=id&order=desc&per_page=12');
                    $postsNextPage = json_decode($response->body(), true);
                    if (!$postsNextPage) {
                        continue;
                    }
                    foreach ($postsNextPage as $post) {
                        array_push($posts, $post);
                    }
                }
                self::insertData($posts);
                DB::table('latest_items')->insert([
                    'name' => 'post',
                    'value' => $posts[0]['id'],
                ]);
            } else {
                // Handle the another sync post
                self::handleCaseSyncAnotherTime($posts, $latestPost->value);
            }
        }
    }

    /**
     * Handle the case sync another time (with existed latest post)
     */
    private static function handleCaseSyncAnotherTime($posts, $latestPostId)
    {
        // Get the last of the posts
        $postIds = array_map(function ($post) {
            return $post['id'];
        }, $posts);

        $index = array_search($latestPostId, $postIds);
        if ($index === false) {
            foreach ($posts as $item) {
                array_push(self::$postsNotInserted, $item);
            }
            self::$isSliceArray = false;
            // There are 2 cases can happened
            // 1. Latest post not found(this post are deleted)
            // 2. Latest found but it's in another page
            // If it's in the next page then firstly, we must be insert first page
            // Continue get next page
            for ($i = self::$loopTime; $i < self::$totalPages; $i++) {
                // If the min post id is not equal to the current latest post id, then we don't need to
                // crawl the next page.
                $response = Http::get(self::$url . '?page=' . self::$loopTime . '&orderby=id&order=desc&per_page=12');
                $postsNextPage = json_decode($response->body(), true);
                echo 'Crawlling post page::: ' . self::$loopTime . PHP_EOL;
                self::$loopTime++;
                foreach ($postsNextPage as $item) {
                    array_push(self::$postsNotInserted, $item);
                }
                self::handleCaseSyncAnotherTime($postsNextPage, $latestPostId);
                break;
            }
        } else {
            if (self::$isSliceArray) {
                self::$postsNotInserted = array_slice($posts, 0, $index);
            }
        }

        $unique_array = array_values(array_intersect_key(self::$postsNotInserted, array_unique(array_column(self::$postsNotInserted, 'id'))));

        $latestArrayInserted = array_filter($unique_array, function ($item) use ($latestPostId) {
            return $item['id'] > $latestPostId;
        });
        if (!empty($latestArrayInserted)) {
            // Insert the post not yet created
            self::insertData($latestArrayInserted);
            DB::table('latest_items')->where(['name' => 'post'])->update([
                'value' => $latestArrayInserted[0]['id'],
            ]);
        }
    }

    /**
     * Insert new post
     * @param array $data
     * @return void
     * @throws CrawlDataException
     */
    private static function insertData($data = [])
    {
        // Sort the posts by id descending before inserting
        usort($data, function ($a, $b) {
            return $a['id'] - $b['id'];
        });
        try {
            $totalItems = 0;
            $calculatePercent = round(60 / count($data));
            $currentState = SyncTracking::where('status', 'running')->first();
            foreach ($data as $item) {
                $isExisted = Url::whereRequestPath($item['slug'])->whereUrlableType(Post::class)->exists();
                if (!$isExisted) {
                    $locale = app()->getLocale();
                    $postExisted = Post::where("name->{$locale}", $item['title']['rendered'])->first();
                    if (!empty($postExisted)) {
                        continue;
                    }
                    $prepareData = [
                        'migrate_post_id' => $item['id'],
                        'name' => $item['title']['rendered'],
                        'description' => $item['excerpt']['rendered'] ?? (strlen($item['content']['rendered']) > 0 ? Str::words($item['content']['rendered'], 35, '') : null),
                        'content' => self::handleContentOfPost($item['content']['rendered']),
                        'is_active' => true,
                    ];
                    // Sync post
                    $post = Post::create($prepareData);
                    // Create content list for post
                    HandleContentListableAction::action($post);
                    //Sync author
                    $post->author()->associate(self::$admin);
                    $post->save();
                    // Sync categories
                    $itemCategories = $item['categories'];
                    if (count($itemCategories) > 0) {
                        $categoriesInPost = Category::select('id')->whereIn('migrate_category_id', $itemCategories)->pluck('id')->toArray();
                        $post->categories()->sync($categoriesInPost);
                    }
                    // Sync tag
                    $itemTags = $item['tags'];
                    if (count($itemTags) > 0) {
                        $tagInPost = Tag::select('id')->whereIn('migrate_tag_id', $itemTags)->pluck('id')->toArray();
                        $post->syncTags($tagInPost);
                    }
                    // Sync image thumbnail
                    $media = self::getMediaFromUrl($item);
                    if ($media) {
                        $post->media()->attach($media->id, [
                            'group' => 'image',
                        ]);
                    }
                    // Sync SEO
                    $post->seourl()->create([
                        'request_path' => $item['slug'],
                        'target_path' => 'cms/post/' . $post->id,
                    ]);
                    if (!empty($item['yoast_head_json'])) {
                        self::handleSaveYoastSEO($post, $item['yoast_head_json']);
                    }
                    $totalItems++;
                    echo 'Created successfully a post with id = ' . $post->id . PHP_EOL;
                    $currentState->update([
                        'processed' => $currentState->processed + $calculatePercent,
                        'message' => 'Đang đồng bộ bài viết',
                    ]);
                    $post->update();
                }
            }
            echo 'Synced successfully total = ' . $totalItems . PHP_EOL;

            // Create stories from post content
            // Process::run("php artisan cms:create-stories");
            self::createStories();

            SyncTracking::where('status', 'running')->update([
                'processed' => 100,
                'message' => 'Đã đồng bộ tất cả danh mục, tags và bài viết mới nhất',
                'status' => 'successfully',
            ]);
        } catch (\Throwable $th) {
            SyncTracking::where('status', 'running')->update([
                'status' => 'failed',
                'message' => 'Error while sync posts :::' . $th->getMessage(),
            ]);
            // Get latest item inserted
            $postItem = Post::latest()->first();
            if ($postItem->migrate_post_id) {
                DB::table('latest_items')->updateOrInsert(['value' => $postItem->migrate_post_id,], ['name' => 'post']);
            }
            throw new CrawlDataException('Failed to sync post::: ' . $th->getMessage());
        }
    }

    private static function createStories()
    {
        $exceptCategory = setting('story_is_auto_create_from');
        try {
            DB::beginTransaction();
            if ($exceptCategory && count($exceptCategory) > 0) {
                $posts = Post::whereHas('categories', function ($query) use ($exceptCategory) {
                    $query->whereNotIn('id', $exceptCategory);
                })->where(['is_created_story' => false])->orderBy('id', 'desc')->get();
            } else {
                $posts = Post::where(['is_created_story' => false])->orderBy('id', 'desc')->get();
            }
            foreach ($posts as $post) {
                CreateStoryFromPostAction::createStory($post);
            }
            DB::commit();
            event(new StoryEvent('created'));
        } catch (CreateStoryException $ex) {
            logger('Error creating stories from posts', [$ex->getMessage()]);
            DB::rollBack();
        }
    }

    /**
     * Get the image from url and convert it to media type
     * @param array $post
     * @return Media|null
     */
    private static function getMediaFromUrl($post)
    {
        try {
            $wpFeaturedMedia = !empty($post['_links']['wp:featuredmedia']) ? $post['_links']['wp:featuredmedia'] : null;
            if (!empty($wpFeaturedMedia)) {
                $mediaUrl = $wpFeaturedMedia[0]['href'];
                $mediaResponse = Http::get($mediaUrl);
                if ($mediaResponse->successful()) {
                    // Get the image content
                    $mediaContent = json_decode($mediaResponse->body(), true);
                    $imageUrl = !empty($mediaContent['guid']['rendered']) ? $mediaContent['guid']['rendered'] : null;
                    if ($imageUrl) {
                        return self::convertUrlToMedia($imageUrl);
                    }
                }
            } else {
                // Get the first image found in the content of post
                $content = $post['content']['rendered'];
                preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/', $content, $matches);
                if (!empty($matches[1])) {
                    // Display or process the image URLs
                    return self::convertUrlToMedia($matches[1][0]);
                }
            }
            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Convert url to media
     * @param string $url
     * @return Media|null
     */
    private static function convertUrlToMedia($imageInput): Media|null
    {
        if (str_starts_with($imageInput, 'data:image')) {
            [$meta, $content] = explode(',', $imageInput);
            preg_match('/data:image\/(.*?);base64/', $meta, $matches);
            $extension = $matches[1] ?? 'png';
            $imageContent = base64_decode($content);
            $fileName = uniqid() . '.' . $extension;
        } else {
            $response = Http::get($imageInput);
            if (!$response->successful()) {
                return null;
            }
            $imageContent = $response->body();
            $fileName = basename(parse_url($imageInput, PHP_URL_PATH));
        }

        $tempImagePath = sys_get_temp_dir() . '/' . $fileName;
        file_put_contents($tempImagePath, $imageContent);

        $uploadedFile = new UploadedFile($tempImagePath, $fileName);

        $media = app(MediaUploader::class)->setFile($uploadedFile)->upload();

        if (file_exists($tempImagePath)) {
            unlink($tempImagePath);
        }

        return $media;
    }

    /**
     * Handle content of post
     * @param string $content
     * @return string
     */
    private static function handleContentOfPost($content)
    {
        $url = setting('wordpress_url_api');
        $frontEndUrl = config('app.front_end_url');
        $parsed_url = parse_url($url);
        $domain = $parsed_url['scheme'] . "://" . $parsed_url['host'];
        $newContent = self::handleImageInContent($content);
        if (!setting('change_internal_link')) {
            return $newContent;
        }

        $pattern = '/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>/i';

        // Callback function to replace domain in href attribute
        $callback = function ($matches) use ($domain, $frontEndUrl) {
            // Get the current href value
            $href = $matches[1];

            // Replace the old domain with the new domain
            $newHref = str_replace($frontEndUrl, $domain, $href);

            // Reconstruct the <a> tag with the new href
            return str_replace($href, $newHref, $matches[0]);
        };

        // Use preg_replace_callback to apply the callback function to all matches
        return preg_replace_callback($pattern, $callback, $newContent);
    }

    /**
     * Handle upload image into media
     */
    private static function handleImageInContent($content): mixed
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_use_internal_errors(false);

        $imgTags = $dom->getElementsByTagName('img');
        foreach ($imgTags as $image) {
            $src = $image->getAttribute('src');
            if (!empty($src)) {
                $media = self::convertUrlToMedia($src);
                if ($media) {
                    $image->setAttribute('src', $media->url);
                    $existingStyle = $image->getAttribute('style');
                    $centerStyle = 'display: block; margin: auto;';
                    $newStyle = trim($existingStyle . ' ' . $centerStyle);
                    $image->setAttribute('style', $newStyle);
                }
            }
        }
        return $content;
    }

    /**
     * Handle save SEO information based on Yoast plugin WP
     * @param Post $post
     * @param array $data
     * @return void
     */
    private static function handleSaveYoastSEO($post, $data = [])
    {
        $value = [
            'title' => $data['title'],
            // 'keywords',
            'og_title' => $data['og_title'],
            'og_description' => $data['og_description'],
            'twitter_title' => $data['og_title'],
            'twitter_description' => $data['og_description'],
        ];
        if (!empty($data['description'])) {
            $value['description'] = $data['description'];
        }
        if ($post->image) {
            $value['og_image'] = $post->image->id;
            $value['twitter_image'] = $post->image->id;
        }
        if ($post->seometa) {
            $post->seometa->update($value);
        } else {
            $post->seometa()->create($value);
        }
    }
}
