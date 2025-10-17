<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Modules\Ecommerce\Models\Rating;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ecommerce\Http\Requests\RatingRequest;
use Modules\Ecommerce\Http\Resources\RatingResource;

class RatingController extends Controller
{
    /**
     * Get 20 items rating
     */
    public function index()
    {
        $items = Rating::whereIsPublished(true)->orderBy('id', 'DESC')->paginate(1);
        return RatingResource::collection($items ?? []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request)
    {
        $isRated = Rating::whereProductId($request->product_id)->whereEmail($request->email)->count();
        if ($isRated > 0) {
            return response()->json(['message' => 'Bạn đã đánh giá bài viết này, xin cảm ơn!', 'status' => Response::HTTP_BAD_REQUEST]);
        }
        Rating::create($request->all());
        return response()->json([
            'stars' => Rating::whereProductId($request->product_id)->whereIsPublished(true)->count(),
            'message' => 'Cảm ơn đánh giá của bạn. Đánh giá đã được ghi lại.'
        ]);
    }
}
