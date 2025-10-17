<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Newnet\Cms\Actions\Crawler\HandleCrawlDataCategoryAction;
use Newnet\Cms\Actions\Crawler\HandleCrawlDataPostAction;
use Newnet\Cms\Actions\Crawler\HandleCrawlDataTagAction;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Events\RunningSyncDataEvent;
use Newnet\Cms\Models\SyncTracking;

class RunningSyncDataListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(RunningSyncDataEvent $event)
  {
    // $cmd = "php artisan cms:sync-data-from-wp";
    // Process::start($cmd);

    logger('Starting sync data...');

    $tracking = SyncTracking::where('status', 'running')->first();
    if (!$tracking) {
      $tracking = SyncTracking::create([
        'status' => 'running',
        'message' => 'Đang đồng bộ danh mục bài viết',
      ]);
    }
    
    $url = setting('wordpress_url_api');

    logger('Starting sync category...');
    HandleCrawlDataCategoryAction::action($url.'/categories');

    $tracking->update([
      'processed' => 20,
      'message' => 'Đã đồng bộ xong danh mục bài viết',
    ]);

    logger('Completed sync category...');

    logger('----------------------------------------------------------------');
    logger('Starting sync tags...');
    HandleCrawlDataTagAction::action($url.'/tags');
    logger('Completed sync tags...');

    $tracking->update([
      'processed' => 40,
      'message' => 'Đã đồng bộ tất cả các tags',
    ]);

    logger('----------------------------------------------------------------');
    logger('Starting sync posts...');

    HandleCrawlDataPostAction::action($url.'/posts');
    logger('Completed sync posts...');

    logger('Successfully sync data!');
    $tracking->update([
      'status' => 'successfully',
      'message' => 'Đã đồng bộ tất cả danh mục, tags và bài viết mới nhất',
      'processed' => 100,
    ]);
    // event(new PostEvent('created'));
  }
}
