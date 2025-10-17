<?php

namespace Modules\Ecommerce;

use Illuminate\Support\Facades\Event;
use Modules\Ecommerce\Events\OrderEvent;
use Modules\Ecommerce\Listeners\OrderListener;
use Newnet\Module\Support\BaseModuleServiceProvider;

class EcommerceServiceProvider extends BaseModuleServiceProvider
{
  public function register()
  {
    parent::register();

    require_once __DIR__ . '/../helpers/helper.php';

    $this->app->singleton(
      \Illuminate\Contracts\Debug\ExceptionHandler::class,
      \Modules\Ecommerce\Exceptions\Handler::class
    );
  }

  public function boot()
  {
    parent::boot();

    Event::listen(OrderEvent::class, OrderListener::class);
  }
}
