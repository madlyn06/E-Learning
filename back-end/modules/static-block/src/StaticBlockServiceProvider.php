<?php

namespace Modules\StaticBlock;

use Modules\StaticBlock\Services\StaticBlockService;
use Newnet\Module\Support\BaseModuleServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Modules\StaticBlock\Facade\StaticBlockRender;

class StaticBlockServiceProvider extends BaseModuleServiceProvider
{
  public function register()
  {
    parent::register();

    $this->app->singleton('static.block', StaticBlockService::class);
  }

  public function boot()
    {
        parent::boot();
        AliasLoader::getInstance()->alias('StaticBlockRender', StaticBlockRender::class);
    }
}
