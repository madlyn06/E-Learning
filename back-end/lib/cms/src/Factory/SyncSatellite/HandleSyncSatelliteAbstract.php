<?php

namespace Newnet\Cms\Factory\SyncSatellite;

use Illuminate\Support\Facades\Http;

abstract class HandleSyncSatelliteAbstract
{
    abstract public function prepareData($sites): array;

    public function sync($data = []): void
    {
        try {
            foreach ($data as $url => $dataItem) {
                $response = Http::withHeaders([
                    'x-api-key' => '197d6e4645a497dda4015979efa585d4',
                ])->post($url . '/api/satellite-sync', $dataItem);
                $result = $response->json();
            }
        } catch (\Throwable $th) {
            logger('Error when sync data to ' . $url, [$th->getMessage()]);
        }
    }
}
