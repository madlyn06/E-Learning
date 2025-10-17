<?php

namespace Newnet\Slider\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Slider\Http\Resources\SliderResource;
use Newnet\Slider\Repositories\SliderItemRepositoryInterface;
use Newnet\Slider\Repositories\SliderRepositoryInterface;

class SliderController extends Controller
{
  /**
   * @var SliderItemRepositoryInterface|SliderItemRepository
   */
  protected $sliderItemRepository;

  /**
   * @var SliderRepositoryInterface|SliderRepository
   */
  protected $sliderRepository;

  public function __construct(
    SliderItemRepositoryInterface $sliderItemRepository,
    SliderRepositoryInterface $sliderRepository
  ) {
    $this->sliderItemRepository = $sliderItemRepository;
    $this->sliderRepository = $sliderRepository;
  }

  public function getSliderItemsActivated($sliderKey)
  {
    $slider = $this->sliderRepository->findBySlug($sliderKey);
    $sliderItems = $this->sliderItemRepository->allActiveOfSlider($slider->id);
    return SliderResource::collection($sliderItems ?? []);
  }
}
