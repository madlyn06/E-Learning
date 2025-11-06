<?php

namespace Modules\Elearning\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Enrollment;
use Modules\Elearning\Models\User;
use Modules\Elearning\Services\CouponService;
use Modules\Elearning\Services\Payment\PaymentService;

class EnrollmentService
{
    protected $couponService;
    protected $paymentService;

    public function __construct(CouponService $couponService, PaymentService $paymentService)
    {
        $this->couponService = $couponService;
        $this->paymentService = $paymentService;
    }

    public function enrollUserInFreeCourse(User $user, Course $course): array
    {
        try {
            // Check if user is already enrolled in this course
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->first();

            if ($existingEnrollment) {
                return [
                    'success' => false,
                    'message' => 'You are already enrolled in this course',
                ];
            }

            // Create enrollment
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'price_paid' => 0,
                'original_price' => 0,
                'discount_amount' => 0,
                'coupon_id' => null,
                'payment_method' => null,
                'transaction_id' => null,
                'enrolled_at' => now(),
                'completed_at' => now(),
                'status' => 'active',
            ]);

            return [
                'success' => true,
                'enrollment' => $enrollment,
                'message' => 'Enrollment created successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error enrolling user in free course: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while enrolling in the course: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Enroll a user in a course.
     *
     * @param User $user
     * @param Course $course
     * @param string|null $couponCode
     * @param int $paymentMethodId
     * @return array
     */
    public function enrollUserInCourse(User $user, Course $course, ?string $couponCode, int $paymentMethodId): array
    {
        try {
            // Check if user is already enrolled in this course
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->first();

            if ($existingEnrollment) {
                return [
                    'success' => false,
                    'message' => 'You are already enrolled in this course',
                ];
            }

            $originalPrice = $course->sale_price ?? $course->price;
            $discountAmount = 0;
            $coupon = null;

            // Apply coupon if provided
            if ($couponCode) {
                $couponValidation = $this->couponService->validateCoupon($couponCode, $user, $course);
                if ($couponValidation['valid']) {
                    $coupon = $couponValidation['coupon'];
                    $discountAmount = $couponValidation['discount_amount'];
                    $finalPrice = $couponValidation['final_price'];
                } else {
                    return [
                        'success' => false,
                        'message' => $couponValidation['message'],
                    ];
                }
            } else {
                $finalPrice = $originalPrice;
            }

            // Begin database transaction
            DB::beginTransaction();

            try {
                // Create payment
                $paymentData = [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'payment_method_id' => $paymentMethodId,
                    'amount' => $finalPrice,
                    'currency' => 'VND', // Adjust as needed
                ];

                $payment = $this->paymentService->createPayment($paymentData);
                
                // Initialize payment
                $paymentResult = $this->paymentService->initializePayment($payment);
                
                if (!$paymentResult['success']) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Failed to process payment: ' . $paymentResult['message'],
                    ];
                }

                // Record coupon usage if applicable
                if ($coupon) {
                    $this->couponService->recordCouponUsage($coupon, $user, $course, $discountAmount);
                }

                // Create enrollment (will be activated when payment is confirmed)
                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'price_paid' => $finalPrice,
                    'original_price' => $originalPrice,
                    'payment_id' => $payment->id,
                    'discount_amount' => $discountAmount,
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'payment_method' => $payment->paymentMethod->name,
                    'transaction_id' => $payment->reference_id,
                    'status' => 'pending', // Will be updated to 'active' when payment is confirmed
                    'enrolled_at' => now(),
                    'expires_at' => $payment->expires_at,
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'enrollment' => $enrollment,
                    'payment' => $payment,
                    'message' => 'Enrollment initiated. Please complete the payment.',
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error enrolling user in course: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'coupon_code' => $couponCode,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while enrolling in the course: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Activate an enrollment after payment is confirmed.
     *
     * @param string $transactionId
     * @return array
     */
    public function activateEnrollment(string $transactionId): array
    {
        try {
            $enrollment = Enrollment::where('transaction_id', $transactionId)
                ->where('status', 'pending')
                ->first();

            if (!$enrollment) {
                return [
                    'success' => false,
                    'message' => 'Enrollment not found or already activated',
                ];
            }

            $enrollment->update([
                'status' => 'active',
            ]);

            return [
                'success' => true,
                'enrollment' => $enrollment,
                'message' => 'Enrollment activated successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error activating enrollment: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while activating the enrollment',
            ];
        }
    }

    /**
     * Get user enrollments.
     *
     * @param User $user
     * @param string|null $status
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserEnrollments(User $user, ?string $status = null, int $perPage = 10)
    {
        $query = Enrollment::where('user_id', $user->id)
            ->with(['course']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Check if a user is enrolled in a course.
     *
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public function isUserEnrolled(User $user, Course $course): bool
    {
        return Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
    }
}
