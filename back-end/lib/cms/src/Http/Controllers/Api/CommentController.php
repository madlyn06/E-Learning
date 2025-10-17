<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Http\Resources\CommentResource;
use Newnet\Cms\Models\Comment;
use Newnet\Core\Utils\Common;

class CommentController extends Controller
{
  public function getComments($post_id)
  {
    $items = Comment::wherePostId($post_id)->get();
    return Common::buildCommentTree($items);
  }

  /**
   * Store a comment
   */
  public function store(Request $request)
  {
    $item = Comment::create($request->all());
    return new CommentResource($item);
  }
}
