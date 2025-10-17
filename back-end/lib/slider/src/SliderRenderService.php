<?php

namespace Newnet\Slider;

use Illuminate\Support\Facades\Log;
use Newnet\Slider\Repositories\SliderItemRepositoryInterface;
use Newnet\Slider\Repositories\SliderRepositoryInterface;
use Newnet\Slider\Repositories\Eloquent\SliderItemRepository;
use Newnet\Slider\Repositories\Eloquent\SliderRepository;

class SliderRenderService
{
    /**
     * @var SliderRepositoryInterface|SliderRepository
     */
    protected $sliderRepository;

    /**
     * @var SliderItemRepositoryInterface|SliderItemRepository
     */
    protected $sliderItemRepository;

    public function __construct(
        SliderRepositoryInterface $sliderRepository,
        SliderItemRepositoryInterface $sliderItemRepository
    ) {
        $this->sliderRepository = $sliderRepository;
        $this->sliderItemRepository = $sliderItemRepository;
    }

    public function render($sliderKey, $layout = null)
    {
        try {
            $slider = $this->findSlider($sliderKey);
        } catch (\Exception $e) {
            Log::error("Slider <strong>{$sliderKey}</strong> not found!");
            return "";
//            return "Slider <strong>{$sliderKey}</strong> not found!";
        }
        if (!$slider->is_active) return "";
        $layout = $layout ?: 'slider::layouts.' . $slider->layout;

        $sliderItems = $this->sliderItemRepository->allActiveOfSlider($slider->id);

        return view($layout)->with([
            'slider'      => $slider,
            'sliderItems' => $sliderItems,
        ]);
    }

    protected function findSlider($sliderKey)
    {
        if (is_numeric($sliderKey)) {
            $slider = $this->sliderRepository->find($sliderKey);
        } else {
            $slider = $this->sliderRepository->findBySlug($sliderKey);
        }
        return $slider;
    }
}
