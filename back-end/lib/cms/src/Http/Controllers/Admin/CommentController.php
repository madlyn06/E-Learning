<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\Models\Comment;

class CommentController extends Controller
{
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function reply(Request $request)
  {
    $data = $request->all();
    $data['is_published'] = true;
    $data['name'] = auth('admin')->user()->name;
    $item = Comment::create($data);
    $item->update([
      'status' => 'replied',
    ]);
    return response()->json([
      'message' => 'Reply successfully',
    ]);
  }

  /**
   * Pubish a comment
   */
  public function publish(Request $request)
  {
    $item = Comment::find($request->comment_id);
    $item->update([
      'is_published' => $request->is_published,
      'published_at' => now(),
    ]);
    return response()->json([
      'success' => true,
      'is_published' => $request->is_published,
      'comment_id' => $item->id
    ]);
  }

  public function destroy(Request $request, $id)
  {
    Comment::destroy($id);
    if ($request->ajax()) {
      Session::flash('success', __('Comment deleted successfully'));
      return response()->json([
        'success' => true,
      ]);
    }

    return redirect()
      ->back()
      ->with('success', __('Comment deleted successfully'));
  }
}
