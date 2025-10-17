<?php

namespace Modules\Manage\Util;

use Newnet\Seo\Models\Ads;

class Utils
{
    /**
     * Retrieves the download code.
     *
     * @param string $downloadCode The download code to retrieve.
     * @return array|null The retrieved download code.
     */
    public static function getDownloadCode($downloadCode)
    {
        $ads = Ads::where('code', $downloadCode)->whereIsActive(true)->first();
        if (!$ads) {
            return null;
        }
        $adsItem = null;

        if ($ads && $ads->adsItems->isNotEmpty()) {
            $adsItem = $ads->adsItems->random();
        }
        return [
            'name' => $ads->name,
            'title' => $ads->title,
            'keyword' => $adsItem->title,
            'image' => $adsItem && $adsItem->image ? $adsItem->image->url : '',
            'image_search_google' => $adsItem && $adsItem->image_search_google ? $adsItem->image_search_google->url : '',
        ];
    }
}
