<?php

namespace Newnet\Cms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Newnet\Cms\Exceptions\SyncSatelliteException;
use Newnet\Cms\Factory\SyncSatellite\SyncSatelliteFactory;
use Newnet\Cms\Models\SatelliteSync;

class SyncToSatelliteSiteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnsoft:sync-satellite-site {sites?} {satelliteSyncId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data to satellite site';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $sites = $this->argument('sites');
        $satelliteSyncId = $this->argument('satelliteSyncId');
        $type = 'specific';
        if ($sites == SatelliteSync::SYNC_ALL_SITE) {
            $type = 'all';
        }
        $res = SyncSatelliteFactory::create($type);
        if (!$res instanceof SyncSatelliteException) {
            $dataSync = $res->prepareData($sites);
            $res->sync($dataSync);
            $this->info('Sync to satellite successfully!');
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
