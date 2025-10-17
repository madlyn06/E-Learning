<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Jobs\ImportPostJob;
use Newnet\Cms\Models\TrackingImport;

class SatelliteSyncController extends Controller
{
    public function sync(Request $request)
    {
        $trackingImport = TrackingImport::create([
            'payload' => json_encode($request->all()),
            'start_at' => now(),
            'status' => 'running',
            'message' => 'Đang đồng bộ',
        ]);
        ImportPostJob::dispatch($request->all(), $trackingImport);
        
        return response()->json([
            'message' => 'Đang đồng bộ danh mục, tag, bài viết đến '. $request->satellite_site,
            'status' => 200
        ]);
    }
}
