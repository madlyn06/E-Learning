<?php

namespace Newnet\Cms\Factory\SyncSatellite;

use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Satellite;

class HandleSpeciaficSite extends HandleSyncSatelliteAbstract
{
    public function prepareData($data = null): array
    {
        $siteIds = explode(',', $data);
        $sites = Satellite::whereIn('id', $siteIds)->get();
        $result = [];
        foreach ($sites as $site) {
            $category = Category::find($site->category_id);
            $result[$site->url] = HandlePostInCategory::getPost($category);
        }
        return $result;
    }
}
