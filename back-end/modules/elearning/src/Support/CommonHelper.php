<?php

namespace Modules\Elearning\Support;

use Newnet\Core\Utils\Common;

class CommonHelper
{
    public static function slugify($url)
    {
        return Common::buildSlug($url);
    }
}
