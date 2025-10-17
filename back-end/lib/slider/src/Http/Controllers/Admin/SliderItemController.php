<?php

namespace Newnet\Slider\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Slider\Events\SliderEvent;
use Newnet\Slider\Http\Requests\SliderItemRequest;
use Newnet\Slider\Models\SliderItem;
use Newnet\Slider\Repositories\Eloquent\SliderItemRepository;
use Newnet\Slider\Repositories\Eloquent\SliderRepository;
use Newnet\Slider\Repositories\SliderItemRepositoryInterface;
use Newnet\Slider\Repositories\SliderRepositoryInterface;

class SliderItemController extends Controller
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

    public function index($sliderId)
    {
        \AdminMenu::activeMenu('slider_root');

        $slider = $this->sliderRepository->find($sliderId);
        $items = $this->sliderItemRepository->allOfSlider($sliderId);

        return view('slider::admin.slider-item.index', compact('items', 'slider'));
    }

    public function create(Request $request)
    {
        \AdminMenu::activeMenu('slider_root');

        $slider_id = $request->input('slider_id');

        $item = new SliderItem();
        $item->slider_id = $slider_id;
        $item->is_active = true;
        $item->sort_order = $this->sliderItemRepository->getMaxSortOrderOfSlider($slider_id) + 1;

        $slider = $this->sliderRepository->find($slider_id);

        return view('slider::admin.slider-item.create', compact('item', 'slider'));
    }

    public function store(SliderItemRequest $request)
    {
        $item = $this->sliderItemRepository->create($request->all());
        $item->attachMedia($request->input('image', []), 'image');
        event(new SliderEvent('created'));

        if ($request->input('continue')) {
            return redirect()
                ->route('slider.admin.slider-item.edit', $item->id)
                ->with('success', __('slider::slider-item.notification.created'));
        }

        return redirect()
            ->route('slider.admin.slider-item.index', $item->slider_id)
            ->with('success', __('slider::slider-item.notification.created'));
    }

    public function edit($id)
    {
        \AdminMenu::activeMenu('slider_root');

        $item = $this->sliderItemRepository->find($id);
        $slider = $item->slider;

        return view('slider::admin.slider-item.edit', compact('item', 'slider'));
    }

    public function update(SliderItemRequest $request, $id)
    {
        /** @var SliderItem $item */
        $item = $this->sliderItemRepository->updateById($request->all(), $id);
        event(new SliderEvent('updated'));

        $imageId = $request->input('image');
        $firstMedia = $item->getFirstMedia('image');
        if ($firstMedia && $firstMedia->id != $imageId) {
            $firstMedia->delete();
        }
        $item->attachMedia([$imageId], 'image');

        if ($request->input('continue')) {
            return redirect()
                ->route('slider.admin.slider-item.edit', $item->id)
                ->with('success', __('slider::slider-item.notification.updated'));
        }

        return redirect()
            ->route('slider.admin.slider-item.index', $item->slider_id)
            ->with('success', __('slider::slider-item.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $item = $this->sliderItemRepository->find($id);
        $this->sliderItemRepository->delete($id);
        event(new SliderEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('slider::slider-item.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('slider.admin.slider-item.index', $item->slider_id)
            ->with('success', __('slider::slider-item.notification.deleted'));
    }
}
