<?php

declare(strict_types=1);

namespace Modules\Elearning\Services;

use Modules\Elearning\Models\Coupon;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\CouponUser;
use Modules\Elearning\Models\User;
use Illuminate\Support\Facades\Log;

class CouponService
{
    /**
     * Validate a coupon for a specific user and course.
     *
     * @param string $couponCode
     * @param User $user
     * @param Course $course
     * @return array
     */
    public function validateCoupon(string $couponCode, User $user, Course $course): array
    {
        try {
            // Find the coupon by code
            $coupon = Coupon::where('code', $couponCode)->first();
            
            if (!$coupon) {
                return [
                    'valid' => false,
                    'message' => 'Coupon not found',
                ];
            }
            
            // Check if coupon is active
            if (!$coupon->isValid()) {
                return [
                    'valid' => false,
                    'message' => 'Coupon is not valid or has expired',
                ];
            }
            
            // Check if user can use this coupon
            if (!$coupon->isValidForUser($user)) {
                return [
                    'valid' => false,
                    'message' => 'You have already used this coupon the maximum number of times',
                ];
            }
            
            // Check if minimum purchase is met
            if ($coupon->minimum_purchase && $course->price < $coupon->minimum_purchase) {
                return [
                    'valid' => false,
                    'message' => "This coupon requires a minimum purchase of {$coupon->minimum_purchase}",
                ];
            }
            
            // Calculate discount
            $discountAmount = $coupon->calculateDiscount($course->price);
            
            if ($discountAmount <= 0) {
                return [
                    'valid' => false,
                    'message' => 'No discount can be applied',
                ];
            }
            
            // Calculate final price
            $finalPrice = $course->sale_price ?? $course->price - $discountAmount;
            
            return [
                'valid' => true,
                'coupon' => $coupon,
                'discount_amount' => $discountAmount,
                'original_price' => $course->sale_price ?? $course->price,
                'final_price' => $finalPrice,
                'message' => 'Coupon applied successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error validating coupon: ' . $e->getMessage(), [
                'coupon_code' => $couponCode,
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'valid' => false,
                'message' => 'An error occurred while validating the coupon',
            ];
        }
    }
    
    /**
     * Record coupon usage.
     *
     * @param Coupon $coupon
     * @param User $user
     * @param Course $course
     * @param float $discountValue
     * @return CouponUser
     */
    public function recordCouponUsage(Coupon $coupon, User $user, Course $course, float $discountValue): CouponUser
    {
        return CouponUser::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'course_id' => $course->id,
            'value' => $discountValue,
        ]);
    }
}
