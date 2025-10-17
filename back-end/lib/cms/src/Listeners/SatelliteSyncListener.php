<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Actions\CreateStoryFromPostAction;
use Newnet\Cms\Events\SatelliteSyncEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Newnet\Cms\Exceptions\SyncSatelliteException;
use Newnet\Cms\Factory\SyncSatellite\SyncSatelliteFactory;
use Newnet\Cms\Models\SatelliteSync;

class SatelliteSyncListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(SatelliteSyncEvent $event)
  {
    $satelliteSite = $event->satelliteSite;
    $satelliteSyncId = $event->satelliteSyncId;
    $type = 'specific';
    if ($satelliteSite == SatelliteSync::SYNC_ALL_SITE) {
        $type = 'all';
    }
    $res = SyncSatelliteFactory::create($type);
    if (!$res instanceof SyncSatelliteException) {
        $dataSync = $res->prepareData($satelliteSite);
        $res->sync($dataSync);
        if (!empty($satelliteSyncId)) {
            $satelliteSync = SatelliteSync::find($satelliteSyncId);
            $satelliteSync->update([
                'message' => 'Đã đồng bộ xong',
                'status' => 'successful',
            ]);
        }
    } else {
        logger('Not found instance of sync satellite', [$res]);
    }
  }
}
