<?php

namespace Newnet\Cms\Factory\SyncSatellite;

use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Satellite;

class HandleAllSite extends HandleSyncSatelliteAbstract
{
    public function prepareData($site = null): array
    {
        $sites = Satellite::whereIsActive(true)->get();
        $result = [];
        foreach ($sites as $site) {
            $category = Category::find($site->category_id);
            $result[$site->url] = HandlePostInCategory::getPost($category);
        }
        return $result;
    }
}
