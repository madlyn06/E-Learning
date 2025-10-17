<?php

namespace Newnet\Zone\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Zone\Http\Requests\ZoneRequest;
use Newnet\Zone\Repositories\Eloquent\ZoneDistrictRepositoryInterface;
use Newnet\Zone\Repositories\Eloquent\ZoneTownshipRepositoryInterface;

class TownshipController extends Controller
{
    /**
     * @var ZoneDistrictRepositoryInterface
     */
    private $zoneDistrictRepository;
    /**
     * @var ZoneTownshipRepositoryInterface
     */
    private $zoneTownshipRepository;

    public function __construct(
        ZoneDistrictRepositoryInterface $zoneDistrictRepository,
        ZoneTownshipRepositoryInterface $zoneTownshipRepository
    )
    {
        $this->zoneDistrictRepository = $zoneDistrictRepository;
        $this->zoneTownshipRepository = $zoneTownshipRepository;
    }

    public function index($id)
    {

    }

    public function create($id)
    {
        return view('zone::admin.townships.create',  compact('id'));
    }

    public function store(ZoneRequest $request, $id)
    {
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'district_id' => $id
        ];
        $this->zoneTownshipRepository->create($data);
        return redirect()->route('zone.admin.township.index', $id);
    }

    public function edit($id)
    {
        $item = $this->zoneTownshipRepository->getById($id);
        return view('zone::admin.townships.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $this->zoneTownshipRepository->update(['id' => $id], [
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status
        ]);
        return redirect()
            ->back()
            ->with('success', __('zone::message.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->zoneTownshipRepository->destroy($id);
        if ($request->wantsJson()) {
            Session::flash('success', __('zone::message.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', __('zone::message.notification.deleted'));
    }

    public function townships(Request $request, $id) {
        $item = $this->zoneDistrictRepository->getById($id);
        $townships = $item->townships()->get();
        $township_id = $request->township_id;
        $firstItemId = count($townships) > 0 ? $townships[0]->id : '';
        if ($request->ajax()) {
            $isTownship = true;
            $renderHtml = view('zone::admin.provinces.zone-ajax', compact('townships', 'isTownship', 'township_id'))->render();
            return response()->json(['townships' => $renderHtml, 'firstItemId' => $firstItemId]);
        }
        return view('zone::admin.townships.index', compact('townships'));
    }
}
