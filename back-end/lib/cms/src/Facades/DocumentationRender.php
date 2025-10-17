<?php

namespace Newnet\Cms\Facades;

use Illuminate\Support\Facades\Facade;

class DocumentationRender extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'module.cms.documentation-render';
  }
}
