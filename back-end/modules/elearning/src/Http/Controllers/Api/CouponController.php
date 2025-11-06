<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Elearning\Http\Requests\Coupon\ValidateCouponRequest;
use Modules\Elearning\Http\Resources\CouponResource;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Services\CouponService;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Validate a coupon code for a course.
     *
     * @param ValidateCouponRequest $request
     * @return JsonResponse
     */
    public function validate(ValidateCouponRequest $request): JsonResponse
    {
        $user = $request->user();
        $course = Course::findOrFail($request->course_id);
        $couponCode = $request->code;

        $result = $this->couponService->validateCoupon($couponCode, $user, $course);

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'coupon' => new CouponResource($result['coupon']),
            'discount_amount' => $result['discount_amount'],
            'original_price' => $result['original_price'],
            'final_price' => $result['final_price'],
        ]);
    }
}
