<?php

namespace Newnet\Slider\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Slider\Http\Requests\SliderRequest;
use Newnet\Slider\Models\Slider;
use Newnet\Slider\Repositories\SliderItemRepositoryInterface;
use Newnet\Slider\Repositories\SliderRepositoryInterface;
use Newnet\Slider\Repositories\Eloquent\SliderRepository;

class SliderController extends Controller
{
    /**
     * @var SliderRepositoryInterface|SliderRepository
     */
    private $sliderRepository;
    /**
     * @var SliderItemRepositoryInterface
     */
    private $sliderItemRepository;

    public function __construct(SliderRepositoryInterface $sliderRepository, SliderItemRepositoryInterface $sliderItemRepository)
    {
        $this->sliderRepository = $sliderRepository;
        $this->sliderItemRepository = $sliderItemRepository;
    }

    public function index(Request $request)
    {
        $items = $this->sliderRepository->paginate($request->input('max', 20));

        return view('slider::admin.slider.index', compact('items'));
    }

    public function create()
    {
        \AdminMenu::activeMenu('slider_root');

        $item = new Slider();
        $item->is_active = true;
        $item->layout = config('cms.slider.default');

        return view('slider::admin.slider.create', compact('item'));
    }

    public function store(SliderRequest $request)
    {
        $item = $this->sliderRepository->createWithAuthor($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('slider.admin.slider.edit', $item->id)
                ->with('success', __('slider::slider.notification.created'));
        }

        return redirect()
            ->route('slider.admin.slider.index')
            ->with('success', __('slider::slider.notification.created'));
    }

    public function edit($id)
    {
        \AdminMenu::activeMenu('slider_root');

        $item = $this->sliderRepository->find($id);
        $items = $this->sliderItemRepository->allOfSlider($id);
        $slider = $item;

        return view('slider::admin.slider.edit', compact('item', 'items', 'slider'));
    }

    public function update(SliderRequest $request, $id)
    {
        $item = $this->sliderRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('slider.admin.slider.edit', $item->id)
                ->with('success', __('slider::slider.notification.updated'));
        }

        return redirect()
            ->route('slider.admin.slider.index')
            ->with('success', __('slider::slider.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->sliderRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('slider::slider.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('slider.admin.slider.index')
            ->with('success', __('slider::slider.notification.deleted'));
    }
}
