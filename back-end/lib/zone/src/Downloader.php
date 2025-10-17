<?php

namespace Newnet\Zone;

use GuzzleHttp\Client;

class Downloader
{
    const DOWNLOAD_URL = 'https://github.com/kjmtrue/vietnam-zone/raw/database/vietnam-zone.xls';

    /**
     * Download database VietNam Zone
     *
     * @return string|null
     */
    public function downloadFile()
    {
        $client = new Client([
            'verify' => false
        ]);

        $res = $client->get(self::DOWNLOAD_URL);

        \File::put(storage_path('vietnam-zone.xls'), $res->getBody()->getContents());

        return $res->getStatusCode() == 200 ? storage_path('vietnam-zone.xls') : null;
    }
}
