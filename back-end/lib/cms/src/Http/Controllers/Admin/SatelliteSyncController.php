<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\Models\SatelliteSync;
use Newnet\Cms\Events\SatelliteSyncEvent;

class SatelliteSyncController extends Controller
{
    public function index()
    {
        $items = SatelliteSync::paginate(10);
        $item = null;
        return view('cms::admin.satellite-sync.index', compact('items', 'item'));
    }

    public function sync(Request $request)
    {
        $satelliteSite = $request->satellite_site;
        if ($satelliteSite) {
            $satelliteSite = json_encode($satelliteSite);
        } else {
            $satelliteSite = SatelliteSync::SYNC_ALL_SITE;
        }
        $satelliteSync = SatelliteSync::create([
            'satellite_site' => $satelliteSite,
            'message' => 'Đang đồng bộ',
            'status' => 'running',
        ]);
        event(new SatelliteSyncEvent($satelliteSite, $satelliteSync->id));
        return redirect()
            ->route('cms.admin.satellite-sync.index')
            ->with('success', __('cms::satellite.notification.created'));
    }

    public function checkTrackingImportStatus()
    {

    }

    public function destroy($id, Request $request)
    {
        $item = SatelliteSync::find($id);
        $item->delete();

        if ($request->wantsJson()) {
            Session::flash('success', __('Đã xoá thành công'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.satellite-sync.index')
            ->with('success', __('Đã xoá thành công'));
    }
}
