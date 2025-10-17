<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
// use Modules\Ecommerce\Events\PostEvent;
use Modules\Ecommerce\Models\Rating;

class RatingController extends Controller
{
  /**
   * Pubish a rating
   */
  public function publish(Request $request)
  {
    $item = Rating::find($request->rating_id);
    $item->update([
      'is_published' => $request->is_published,
    ]);
    // event(new PostEvent('updated'));
    return response()->json([
      'success' => true,
      'is_published' => $request->is_published,
      'rating_id' => $item->id
    ]);
  }

  public function destroy($id, Request $request)
  {
    Rating::find($id)->delete();
    // event(new PostEvent('deleted', $id, auth('admin')->user()->id));

    if ($request->wantsJson()) {
      Session::flash('success', __('cms::rating.notification.deleted'));
      return response()->json([
        'success' => true,
      ]);
    }

    return redirect()
      ->route('cms.admin.ratings.index')
      ->with('success', __('cms::rating.notification.deleted'));
  }

  public function destroyMultipleItems(Request $request)
  {
    $ids = $request->ids;
    Rating::whereIn('id', $ids)->delete();

    // event(new PostEvent('deleted'));
    Session::flash('success', __('cms::rating.notification.deleted'));
    return response()->json(['success' => 200]);
  }
}
