<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Http\Requests\SatelliteRequest;
use Newnet\Cms\Repositories\Eloquent\SatelliteRepository;
use Newnet\Cms\Repositories\SatelliteRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;

class SatelliteController extends Controller
{
    /**
     * @var SatelliteRepositoryInterface|SatelliteRepository
     */
    private $satelliteRepository;

    public function __construct(SatelliteRepositoryInterface $satelliteRepository)
    {
        $this->satelliteRepository = $satelliteRepository;
    }

    public function index(Request $request)
    {
        $items = $this->satelliteRepository->paginate($request->input('max', 20));

        return view('cms::admin.satellite.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::SYNCHRONIZATION_SITE);

        $item = null;

        return view('cms::admin.satellite.create', compact('item'));
    }

    public function store(SatelliteRequest $request)
    {
        $satellite = $this->satelliteRepository->createWithAuthor($request->all());
        return redirect()
            ->route('cms.admin.satellite.edit', $satellite)
            ->with('success', __('cms::satellite.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::SYNCHRONIZATION_SITE);

        $item = $this->satelliteRepository->find($id);

        return view('cms::admin.satellite.edit', compact('item'));
    }

    public function update($id, SatelliteRequest $request)
    {
        $this->satelliteRepository->updateById($request->all(), $id);

        return back()->with('success', __('cms::satellite.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->satelliteRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('cms::satellite.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.satellite.index')
            ->with('success', __('cms::satellite.notification.deleted'));
    }
}
