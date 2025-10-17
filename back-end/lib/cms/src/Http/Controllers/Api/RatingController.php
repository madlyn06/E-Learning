<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Newnet\Cms\Models\Rating;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Newnet\Cms\Http\Requests\RatingRequest;
use Newnet\Cms\Http\Resources\RatingResource;

class RatingController extends Controller
{
    /**
     * Get 20 items rating
     */
    public function index()
    {
        $items = Rating::whereIsPublished(true)->orderBy('id', 'DESC')->paginate(setting('item_on_page', 10));
        return RatingResource::collection($items ?? []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request)
    {
        $isRated = Rating::wherePostId($request->post_id)->whereEmail($request->email)->count();
        if ($isRated > 0) {
            return response()->json(['message' => 'Bạn đã đánh giá bài viết này, xin cảm ơn!', 'status' => Response::HTTP_BAD_REQUEST]);
        }
        Rating::create($request->all());
        return response()->json([
            'stars' => Rating::wherePostId($request->post_id)->whereIsPublished(true)->count(),
            'message' => 'Cảm ơn đánh giá của bạn. Đánh giá đã được ghi lại.'
        ]);
    }
}
