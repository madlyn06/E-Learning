<?php

namespace Newnet\Cms\Facades;

use Illuminate\Support\Facades\Facade;

class StoryRender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'story.render';
    }
}
