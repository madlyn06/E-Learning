<?php

namespace Newnet\Cms\Actions\Crawler;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Events\CategoryEvent;
use Newnet\Cms\Exceptions\CrawlDataException;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\SyncTracking;
use Newnet\Seo\Models\Url;

class HandleCrawlDataCategoryAction
{
  protected static $url = null;
  protected static $firstItem = null;
  protected static $totalPages = null;
  protected static $loopTime = 2;
  protected static $isSliceArray = true;
  protected static $categoriesNotInserted = [];

  /**
   * Start a new crawl data category
   */
  public static function action($url)
  {
    self::$url = $url;
    $response = Http::get($url . '?orderby=id&order=desc&per_page=12');
    if ($response->successful()) {
      echo 'Crawlling category page::: 1' . PHP_EOL;
      $totalPages = (int) $response->getHeader('X-WP-Totalpages')[0];
      self::$totalPages = $totalPages;
      $categories = json_decode($response->body(), true);
      if (empty($categories)) {
        return;
      }
      self::$firstItem = $categories[0];
      $latestCategory = DB::table('latest_items')->where('name', 'category')->first();
      if (!$latestCategory) {
        // Handle the first sync category
        for ($i = 1; $i < $totalPages; $i++) {
          echo 'Crawlling category page::: ' . $i + 1 . PHP_EOL;
          $response = Http::get($url . '?page=' . $i + 1 . '&orderby=id&order=desc&per_page=12');
          $catNextPage = json_decode($response->body(), true);
          foreach ($catNextPage as $cat) {
            array_push($categories, $cat);
          }
        }
        self::insertData($categories);
        DB::table('latest_items')->insert([
          'name' => 'category',
          'value' => self::$firstItem['id'],
        ]);
        self::handleSyncParentCategory($categories);
      } else {
        // Handle the another sync category
        self::handleCaseSyncAnotherTime($categories, $latestCategory->value);
      }

      event(new CategoryEvent('created'));
    }
  }

  /**
   * Handle the case sync another time (with existed latest category)
   */
  private static function handleCaseSyncAnotherTime($categories, $latestCategoryId)
  {
    // Get the last of the categories
    $categoryIds = array_map(function ($category) {
      return $category['id'];
    }, $categories);

    $index = array_search($latestCategoryId, $categoryIds);
    if ($index === false) {
      foreach ($categories as $item) {
        array_push(self::$categoriesNotInserted, $item);
      }
      self::$isSliceArray = false;
      // There are 2 cases can happened
      // 1. Latest category not found(this category are deleted)
      // 2. Latest found but it's in another page
      // If it's in the next page then firstly, we must be insert first page
      // Continue get next page
      for ($i = self::$loopTime; $i < self::$totalPages; $i++) {
        // If the min category id is not equal to the current latest category id, then we don't need to
        // crawl the next page.
        $response = Http::get(self::$url . '?page=' . self::$loopTime . '&orderby=id&order=desc&per_page=12');
        $categoriesNextPage = json_decode($response->body(), true);
        echo 'Crawlling category page::: ' . self::$loopTime . PHP_EOL;
        self::$loopTime++;
        foreach ($categoriesNextPage as $item) {
          array_push(self::$categoriesNotInserted, $item);
        }
        self::handleCaseSyncAnotherTime($categoriesNextPage, $latestCategoryId);
        break;
      }
    } else {
      if (self::$isSliceArray) {
        self::$categoriesNotInserted = array_slice($categories, 0, $index);
      }
    }

    $unique_array = array_values(array_intersect_key(self::$categoriesNotInserted, array_unique(array_column(self::$categoriesNotInserted, 'id'))));

    $latestArrayInserted = array_filter($unique_array, function ($item) use ($latestCategoryId) {
      return $item['id'] > $latestCategoryId;
    });
    if (!empty($latestArrayInserted)) {
      // Insert the category not yet created
      self::insertData($latestArrayInserted);
      DB::table('latest_items')->where(['name' => 'category'])->update([
        'value' => $latestArrayInserted[0]['id'],
      ]);
      self::handleSyncParentCategory($latestArrayInserted);
    }
  }

  /**
   * Insert new category
   * @param array $data
   */
  private static function insertData($data = [])
  {
    try {
      foreach ($data as $item) {
        $isExisted = Url::whereRequestPath($item['slug'])->whereUrlableType(Category::class)->exists();
        if (!$isExisted) {
          $locale = app()->getLocale();
          $category = Category::where("name->{$locale}", $item['name'])->first();
          if (!empty($category)) {
            continue;
          }
          $prepareData = [
            'migrate_category_id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'is_active' => true,
          ];
          $category = Category::create($prepareData);
          $category->seourl()->create([
            'request_path' => $item['slug'],
            'target_path' => 'cms/category/' . $category->id,
          ]);
        }
      }
    } catch (\Throwable $th) {
      SyncTracking::where('status', 'running')->update([
        'status' => 'failed',
        'message' => 'Error while sync categories :::'.$th->getMessage(),
      ]);
      // Get latest item inserted
      $categoryItem = Category::latest()->first();
      if ($categoryItem->migrate_category_id) {
        DB::table('latest_items')->updateOrInsert(['value' => $categoryItem,], ['name' => 'category']);
      }
      throw new CrawlDataException('Failed to sync category::: '. $th->getMessage());
    }
  }

  /**
   * Handle sync parent category
   */
  private static function handleSyncParentCategory($categories)
  {
    $filterCategories = array_filter($categories, function ($category) {
      return $category['parent'] > 0;
    });
    foreach ($filterCategories as $item) {
      $categoriesResult = Category::whereIn('migrate_category_id', [$item['id'], $item['parent']])->get();

      $categoryNeedUpdateKey = 0;
      foreach ($categoriesResult as $key => $category) {
        if ($category->migrate_category_id == $item['id']) {
          $categoryNeedUpdateKey = $key;
          break;
        }
      }
      if ($categoryNeedUpdateKey == 0) {
        $categoryNeedUpdate = $categoriesResult[0];
        $parentCategory = $categoriesResult[1];
      } else {
        $categoryNeedUpdate = $categoriesResult[1];
        $parentCategory = $categoriesResult[0];
      }

      $categoryNeedUpdate->update(['parent_id' => $parentCategory->id]);
    }
  }
}
