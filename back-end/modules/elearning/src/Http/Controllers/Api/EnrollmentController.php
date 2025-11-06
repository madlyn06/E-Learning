<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Http\Requests\Enrollment\EnrollCourseRequest;
use Modules\Elearning\Http\Resources\EnrollmentResource;
use Modules\Elearning\Http\Resources\PaymentResource;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\PaymentMethod;
use Modules\Elearning\Services\EnrollmentService;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends BaseController
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    /**
     * Enroll user in a course.
     *
     * @param EnrollCourseRequest $request
     * @return JsonResponse
     */
    public function enroll(EnrollCourseRequest $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        $course = Course::find($request->course_id);
        if (!$course) {
            return $this->errorResponse('Course not found');
        }
        if (!$course->is_selling) {
            // Free course
            $result = $this->enrollmentService->enrollUserInFreeCourse($user, $course);
        } else {
            // Selling course
            $couponCode = $request->coupon_code;
            $paymentMethodId = $request->payment_method_id;
            $this->validatePaymentMethod($paymentMethodId);
            $result = $this->enrollmentService->enrollUserInCourse($user, $course, $couponCode, $paymentMethodId);
        }

        if (!$result['success']) {
            return $this->errorResponse($result['message']);
        }
        if (!empty($result['payment'])) {
            $result['payment']->load('paymentMethod');
        }

        return $this->successResponse(
        [
            'enrollment' => new EnrollmentResource($result['enrollment']),
            'payment' => !empty($result['payment']) ? new PaymentResource($result['payment']) : null,
        ],
        'Enrollment successful',
        Response::HTTP_CREATED);
    }

    private function validatePaymentMethod(int $paymentMethodId): void
    {
        $paymentMethod = PaymentMethod::find($paymentMethodId);
        if (!$paymentMethod) {
            throw new \Exception(message: "Payment method not found");
        }

        if (!$paymentMethod->is_active) {
            throw new \Exception(message: "Payment method is not active");
        }
    }

    /**
     * Get user enrollments.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function myEnrollments(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);

        $enrollments = $this->enrollmentService->getUserEnrollments($user, $status, $perPage);
        return $this->successResponse(
            EnrollmentResource::collection($enrollments),
            'Enrollments fetched successfully'
        );
    }

    /**
     * Check if user is enrolled in a course.
     *
     * @param Request $request
     * @param int $courseId
     * @return JsonResponse
     */
    public function checkEnrollment(Request $request, int $courseId): JsonResponse
    {
        $user = $request->user();
        $course = Course::findOrFail($courseId);

        $isEnrolled = $this->enrollmentService->isUserEnrolled($user, $course);

        return $this->successResponse(
            [
                'is_enrolled' => $isEnrolled,
            ],
            'Enrollment checked successfully'
        );
    }
}
