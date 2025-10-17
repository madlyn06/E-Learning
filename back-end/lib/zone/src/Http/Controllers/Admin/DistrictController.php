<?php

namespace Newnet\Zone\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Zone\Http\Requests\ZoneRequest;
use Newnet\Zone\Repositories\Eloquent\ZoneDistrictRepositoryInterface;

class DistrictController extends Controller
{
    /**
     * @var ZoneDistrictRepositoryInterface
     */
    private $zoneDistrictRepository;

    public function __construct(ZoneDistrictRepositoryInterface $zoneDistrictRepository)
    {
        $this->zoneDistrictRepository = $zoneDistrictRepository;
    }

    public function index($id)
    {

    }

    public function create($id)
    {
        return view('zone::admin.districts.create',  compact('id'));
    }

    public function store(ZoneRequest $request, $id)
    {
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'province_id' => $id
        ];
        $this->zoneDistrictRepository->create($data);
        return redirect()->route('zone.admin.district.index', $id);
    }

    public function edit($id)
    {
        $item = $this->zoneDistrictRepository->getById($id);
        return view('zone::admin.districts.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $this->zoneDistrictRepository->update(['id' => $id], [
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
        $this->zoneDistrictRepository->destroy($id);
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
}
