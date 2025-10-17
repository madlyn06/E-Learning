<?php

namespace Newnet\Cms\Actions\Crawler;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Newnet\Cms\Exceptions\CrawlDataException;
use Newnet\Cms\Models\SyncTracking;
use Newnet\Seo\Models\Url;
use Newnet\Tag\Events\TagEvent;
use Newnet\Tag\Models\Tag;

class HandleCrawlDataTagAction
{
  protected static $url = null;
  protected static $firstItem = null;
  protected static $totalPages = null;
  protected static $loopTime = 2;
  protected static $isSliceArray = true;
  protected static $tagsNotInserted = [];

  /**
   * Start a new crawl data tag
   */
  public static function action($url)
  {
    self::$url = $url;
    $response = Http::get($url . '?orderby=id&order=desc&per_page=12');
    if ($response->successful()) {
      echo 'Crawlling tag page::: 1' . PHP_EOL;
      $totalPages = (int) $response->getHeader('X-WP-Totalpages')[0];
      self::$totalPages = $totalPages;
      $tags = json_decode($response->body(), true);
      if (empty($tags)) {
        return;
      }
      self::$firstItem = $tags[0];

      $latestTag = DB::table('latest_items')->where('name', 'tag')->first();
      if (!$latestTag) {
        // Handle the first sync tag
        for ($i = 1; $i < $totalPages; $i++) {
          echo 'Crawlling tag page::: ' . $i + 1 . PHP_EOL;
          $response = Http::get($url . '?page=' . $i + 1 . '&orderby=id&order=desc&per_page=12');
          $tagsNextPage = json_decode($response->body(), true);
          foreach ($tagsNextPage as $tag) {
            array_push($tags, $tag);
          }
        }
        self::insertData($tags);
        DB::table('latest_items')->insert([
          'name' => 'tag',
          'value' => self::$firstItem['id'],
        ]);
      } else {
        // Handle the another sync tags
        self::handleCaseSyncAnotherTime($tags, $latestTag->value);
      }
      event(new TagEvent('created'));
    }
  }

  /**
   * Handle the case sync another time (with existed latest tag)
   */
  private static function handleCaseSyncAnotherTime($tags, $latestTagId)
  {
    // Get the last of the tags
    $tagIds = array_map(function ($tag) {
      return $tag['id'];
    }, $tags);

    $index = array_search($latestTagId, $tagIds);
    if ($index === false) {
      foreach ($tags as $item) {
        array_push(self::$tagsNotInserted, $item);
      }
      self::$isSliceArray = false;
      // There are 2 cases can happened
      // 1. Latest tag not found(this tag are deleted)
      // 2. Latest found but it's in another page
      // If it's in the next page then firstly, we must be insert first page
      // Continue get next page
      for ($i = self::$loopTime; $i < self::$totalPages; $i++) {
        // If the min tag id is not equal to the current latest tag id, then we don't need to
        // crawl the next page.
        $response = Http::get(self::$url . '?page=' . self::$loopTime . '&orderby=id&order=desc&per_page=12');
        $tagsNextPage = json_decode($response->body(), true);
        echo 'Crawlling tag page::: ' . self::$loopTime . PHP_EOL;
        self::$loopTime++;
        foreach ($tagsNextPage as $item) {
          array_push(self::$tagsNotInserted, $item);
        }
        self::handleCaseSyncAnotherTime($tagsNextPage, $latestTagId);
        break;
      }
    } else {
      if (self::$isSliceArray) {
        self::$tagsNotInserted = array_slice($tags, 0, $index);
      }
    }

    $unique_array = array_values(array_intersect_key(self::$tagsNotInserted, array_unique(array_column(self::$tagsNotInserted, 'id'))));

    $latestArrayInserted = array_filter($unique_array, function ($item) use ($latestTagId) {
      return $item['id'] > $latestTagId;
    });
    if (!empty($latestArrayInserted)) {
      // Insert the tag not yet created
      self::insertData($latestArrayInserted);
      DB::table('latest_items')->where(['name' => 'tag'])->update([
        'value' => $latestArrayInserted[0]['id'],
      ]);
    }
  }

  /**
   * Insert new tag
   * @param array $data
   */
  private static function insertData($data = [])
  {
    try {
      foreach ($data as $item) {
        $isExisted = Url::whereRequestPath(Str::slug($item['name']))->whereUrlableType(Tag::class)->exists();
        if (!$isExisted) {
          $prepareData = [
            'migrate_tag_id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'slug' => Str::slug($item['name']),
          ];
          $tag = Tag::whereSlug(Str::slug($item['name']))->first();
          if (empty($tag)) {
            $tag = Tag::create($prepareData);
            $tag->seourl()->create([
              'request_path' => Str::slug($item['name']),
              'target_path' => 'tags/' . $tag->id,
            ]);
          }
        }
      }
    } catch (\Throwable $th) {
      SyncTracking::where('status', 'running')->update([
        'status' => 'failed',
        'message' => 'Error while sync tags :::'.$th->getMessage(),
      ]);
      // Get latest item inserted
      $tagItem = Tag::latest()->first();
      if ($tagItem->migrate_tag_id) {
        DB::table('latest_items')->updateOrInsert(['value' => $tagItem], ['name' => 'tag']);
      }
      throw new CrawlDataException('Failed to sync tag::: '. $th->getMessage());
    }
  }
}
