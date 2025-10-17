<?php

namespace Newnet\Cms\Actions;

use Newnet\Cms\Models\Story;
use Newnet\Cms\Models\StoryItem;
use Illuminate\Support\Str;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Core\Utils\Common;
use Newnet\Media\MediaUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateStoryFromPostAction
{
  /**
   * Create a new story from post
   */
  public static function createStory($post)
  {
    $storyTextPoint = setting('story_text_point');
    $autoPlayAfter = setting('story_image_transfer');
    $isDraft = setting('story_is_draft', 0);
    $storyAudio = setting('story_audio');

    $description = $post->description ? Str::words($post->description, 13) : Str::words($post->content, 13, '');
    $isExistedStory = Story::where('slug', Str::slug($post->name))->count();
    if ($isExistedStory) {
      logger('Story created with key::: ' . Str::slug($post->name));
      return;
    }
    $story = Story::create([
      'name' => $post->name,
      'slug' => Str::slug($post->name),
      'description' => $description,
      'is_active' => empty($isDraft) ? true : false,
      'post_id' => $post->id,
    ]);
    if ($post->image) {
      $story->media()->attach($post->image->id, [
        'group' => 'image',
      ]);
    }
    if (!empty($storyAudio)) {
      $story->media()->attach($storyAudio, [
        'group' => 'audio',
      ]);
    }

    // Tạo item đầu tiên cho story
    $firstStoryItemData = [
      'story_id' => $story->id,
      'name' => $post->name,
      'description' => $post->description,
      'is_active' => empty($isDraft) ? true : false,
      'link' => empty($storyTextPoint) ? Common::buildSlug($post->url) : '/',
      'auto_play_after' => $autoPlayAfter,
      'sort_order' => 1,
    ];
    $firstStoryItem = StoryItem::create($firstStoryItemData);

    if ($post->image) {
      $firstStoryItem->media()->attach($post->image->id, [
        'group' => 'image',
      ]);
    }

    self::splitContentAndCreateStoryItem($post->content, $story, $post);

    event(new StoryEvent($story));
  }

  /**
   * Create story item
   * @param string $title
   * @param string $content
   * @param Story $story
   * @param Post $post
   */
  private static function createStoryItem($title, $content, $story, $post)
  {
    $maxImages = setting('story_image_max', 7);
    $autoPlayAfter = setting('story_image_transfer');

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);

    $dom->loadHTML($content);
    libxml_use_internal_errors(false);

    $imgTags = $dom->getElementsByTagName('img');
    foreach ($imgTags as $imgTag) {
      if ($maxImages > $story->storyItems()->count()) {
        $latestStoryItem = $story->storyItems()->orderBy('sort_order', 'desc')->first();
        $src = $imgTag->getAttribute('src');
        $arr = explode('/', $src);
        // Step 2: Create story item
        $dataStore = [
          'story_id' => $story->id,
          'name' => $title,
          'description' => $title,
          'is_active' => empty($isDraft) ? true : false,
          'link' => empty($storyTextPoint) ? Common::buildSlug($post->url) : '/',
          'auto_play_after' => $autoPlayAfter,
          'sort_order' => $latestStoryItem ? $latestStoryItem->sort_order + 1 : 1,
        ];
        // Check title story already exist
        if (str_contains($src, 'https://') || str_contains($src, 'http://')) {
          // $dataStore['addition_image'] = $src;
          $itemStory = StoryItem::create($dataStore);
          // Handle upload image
            $media = self::convertUrlToMedia($src);
          if ($media) {
            $itemStory->media()->attach($media->id, [
              'group' => 'image',
            ]);
          }
        } else {
          $storyItem = StoryItem::create($dataStore);
          $mediaId = $arr[count($arr) - 2];
          $storyItem->media()->attach($mediaId, [
            'group' => 'image',
          ]);
        }
      }
    }
  }

  /**
   * Splits the given content based on <h2> tags and processes it for the provided story and post.
   *
   * @param string $content The content to be split.
   * @param mixed $story The story object to be processed.
   * @param mixed $post The post object to be processed.
   * @return void
   */
  private static function splitContentAndCreateStoryItem($content, $story, $post)
  {
    $dom = new DOMDocument();
    $htmlContent = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
    @$dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $h2Elements = $dom->getElementsByTagName('h2');

    foreach ($h2Elements as $h2) {
      $sectionTitle = $h2->nodeValue;
      $content = '';
      $nextNode = $h2->nextSibling;

      while ($nextNode) {
        if ($nextNode->nodeType === XML_ELEMENT_NODE && $nextNode->nodeName === 'h2') {
          break;
        }
        if ($nextNode->nodeType === XML_TEXT_NODE || $nextNode->nodeType === XML_ELEMENT_NODE) {
          $content .= $dom->saveHTML($nextNode);
        }
        $nextNode = $nextNode->nextSibling;
      }
      self::createStoryItem($sectionTitle, $content, $story, $post);
    }
  }

  /**
   * Convert url to media
   * @param string $url
   * @return Media|null
   */
  private static function convertUrlToMedia($imageUrl)
  {
    $response = Http::get($imageUrl);
    if ($response->successful()) {
      $imageContent = $response->body();
      $arr = explode('/', $imageUrl);
      $fileName = $arr[count($arr) - 1];
      $tempImagePath = sys_get_temp_dir() . '/' . $fileName;
      file_put_contents($tempImagePath, $imageContent);
      // Check file name already exist before upload
      $media = app(config('cms.media.model'))->where('file_name', $fileName)->first();
      if ($media) {
        return $media;
      }
      // Create an instance of UploadedFile
      $uploadedFile = new UploadedFile($tempImagePath, $fileName);
      return app(MediaUploader::class)->setFile($uploadedFile)->upload();
    }
    return null;
  }
}
