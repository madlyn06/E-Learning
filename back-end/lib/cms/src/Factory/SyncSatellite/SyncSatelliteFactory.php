<?php

namespace Newnet\Cms\Factory\SyncSatellite;

use Newnet\Cms\Exceptions\SyncSatelliteException;
use Newnet\Cms\Factory\SyncSatellite\HandleAllSite;
use Newnet\Cms\Factory\SyncSatellite\HandleSpeciaficSite;
use Newnet\Cms\Factory\SyncSatellite\HandleSyncSatelliteAbstract;

class SyncSatelliteFactory
{
    /**
     * Create type sync satellite
     * @param string $type
     * @throws \Newnet\Cms\Exceptions\SyncSatelliteException
     * @return HandleAllSite|HandleSpeciaficSite
     */
    public static function create($type): HandleSyncSatelliteAbstract
    {
        switch ($type) {
            case 'all':
                return new HandleAllSite();
            case 'specific':
                return new HandleSpeciaficSite();
            default:
                throw new SyncSatelliteException('Exporter not found type: ' . $type);
        }
    }
}
