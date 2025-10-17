<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\Events\RunningSyncDataEvent;
use Newnet\Cms\Models\SyncTracking;
use Newnet\Setting\Http\Controllers\Admin\SettingController;

class SyncController extends SettingController
{
  public function index()
  {
    $setting = setting()->all();
    $item = json_decode(json_encode($setting));

    $tracking = SyncTracking::where('status', 'running')->first();
    if ($tracking) {
      $trackingInfo = [
        'message' => $tracking->message,
        'processed' => $tracking->processed,
        'status' => $tracking->status,
      ];
    } else {
      $synced = SyncTracking::where('status', 'successfully')->latest()->first();
      if ($synced) {
        $trackingInfo = [
          'status' => $synced->status,
          'message' => $synced->message,
          'processed' => $synced->processed,
          'updated_at' => $synced->updated_at,
        ];
      } else {
        $trackingInfo = [];
      }
    }

    $items = SyncTracking::orderBy('id', 'desc')->paginate(20);

    return view('cms::admin.sync.index', compact('item', 'trackingInfo', 'items'));
  }

  public function trackingSyncProcess()
  {
    $tracking = SyncTracking::where('status', 'running')->first();
    if ($tracking && $tracking->processed < 100) {
      return response()->json([
        'processed' => $tracking->processed,
      ]);
    }
    return response()->json([
      'message' => 'Đã đồng bộ tất cả danh mục, tags và bài viết mới nhất',
      'status' => 'completed',
      'processed' => 100,
    ]);
  }

  public function save(Request $request)
  {
    if ($request->input('sync')) {
      $tracking = SyncTracking::where('status', 'running')->first();
      if ($tracking) {
        return redirect()
        ->back()
        ->with('success', __('cms::sync.notification.running'));
      }
      SyncTracking::create([
        'status' => 'running',
        'message' => 'Đang đồng bộ danh mục bài viết',
      ]);
      event(new RunningSyncDataEvent());
      return redirect()
        ->back()
        ->with('success', __('cms::sync.notification.running'));
    }

    parent::save($request);
    return redirect()
      ->back()
      ->with('success', __('cms::sync.notification.setting'));
  }

  public function deleteSyncProcess($id)
  {
    SyncTracking::where('id', $id)->delete();
    Session::flash('success', __('Xoá lịch sử sync data thành công'));
    return response()->json([
      'success' => true,
    ]);
  }
}
