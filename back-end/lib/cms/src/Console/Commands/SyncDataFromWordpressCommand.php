<?php

namespace Newnet\Cms\Console\Commands;

use Illuminate\Console\Command;
use Newnet\Cms\Actions\Crawler\HandleCrawlDataCategoryAction;
use Newnet\Cms\Actions\Crawler\HandleCrawlDataPostAction;
use Newnet\Cms\Actions\Crawler\HandleCrawlDataTagAction;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Models\SyncTracking;

class SyncDataFromWordpressCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cms:sync-data-from-wp';

  /**
   * The console sync data posts, categories, tags from wordpress server.
   *
   * @var string
   */
  protected $description = 'Sync data posts, categories, tags from wordpress server';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Starting sync data...');

    $tracking = SyncTracking::where('status', 'running')->first();
    if (!$tracking) {
      $tracking = SyncTracking::create([
        'status' => 'running',
        'message' => 'Đang đồng bộ danh mục bài viết',
      ]);
    }
    try {
      $url = setting('wordpress_url_api');
      $this->info('Starting sync category...');
      HandleCrawlDataCategoryAction::action($url.'/categories');

      $tracking->update([
        'processed' => 20,
        'message' => 'Đã đồng bộ xong danh mục bài viết',
      ]);

      $this->info('Completed sync category...');

      $this->info('----------------------------------------------------------------');
      $this->info('Starting sync tags...');
      HandleCrawlDataTagAction::action($url.'/tags');
      $this->info('Completed sync tags...');

      $tracking->update([
        'processed' => 40,
        'message' => 'Đã đồng bộ tất cả các tags',
      ]);

      $this->info('----------------------------------------------------------------');
      $this->info('Starting sync posts...');

      HandleCrawlDataPostAction::action($url.'/posts');
      $this->info('Completed sync posts...');

      $this->info('Successfully sync data!');
      $tracking->update([
        'status' => 'successfully',
        'message' => 'Đã đồng bộ tất cả danh mục, tags và bài viết mới nhất',
        'processed' => 100,
      ]);
      // event(new PostEvent('created'));
    } catch (\Throwable $th) {
      logger()->error('Error while sync data from wordpress: '.$th->getTraceAsString());
      $tracking->update([
        'processed' => 100,
        'message' => 'Lỗi khi đồng bộ bài viết. '. $th->getTraceAsString(),
        'status' => 'failed',
      ]);
    }
  }
}
