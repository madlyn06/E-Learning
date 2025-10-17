<?php

namespace Newnet\Slider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Newnet\Slider\Facades\SliderRender;
use Newnet\Slider\Models\Slider;
use Newnet\Slider\Models\SliderItem;
use Newnet\Slider\Repositories\Eloquent\SliderItemRepository;
use Newnet\Slider\Repositories\Eloquent\SliderRepository;
use Newnet\Slider\Repositories\SliderItemRepositoryInterface;
use Newnet\Slider\Repositories\SliderRepositoryInterface;
use Newnet\Module\Support\BaseModuleServiceProvider;
use Newnet\Slider\Events\SliderEvent;
use Newnet\Slider\Listeners\SliderListener;

class SliderServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(SliderRepositoryInterface::class, function () {
            return new SliderRepository(new Slider());
        });

        $this->app->singleton(SliderItemRepositoryInterface::class, function () {
            return new SliderItemRepository(new SliderItem());
        });

        $this->app->singleton('slider.render', SliderRenderService::class);

        require_once __DIR__.'/../helpers/helpers.php';
    }

    public function boot()
    {
        parent::boot();

        AliasLoader::getInstance()->alias('SliderRender', SliderRender::class);

        Event::listen(SliderEvent::class, SliderListener::class);

    }
}
