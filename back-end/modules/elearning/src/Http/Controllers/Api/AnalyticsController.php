<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\AnalyticsService;

class AnalyticsController extends BaseController
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get dashboard analytics for instructors
     */
    public function getDashboardAnalytics(): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getDashboardAnalytics($userId);
            return $this->successResponse($analytics, 'Dashboard analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve dashboard analytics');
        }
    }

    /**
     * Get course analytics
     */
    public function getCourseAnalytics(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getCourseAnalytics($courseId, $userId);
            return $this->successResponse($analytics, 'Course analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get user learning analytics
     */
    public function getUserLearningAnalytics(): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getUserLearningAnalytics($userId);
            return $this->successResponse($analytics, 'Learning analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve learning analytics');
        }
    }

    /**
     * Get enrollment analytics
     */
    public function getEnrollmentAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', 'month'); // day, week, month, year
            $courseId = $request->get('course_id');
            $userId = auth('sanctum')->id();
            
            $analytics = $this->analyticsService->getEnrollmentAnalytics($userId, $period, $courseId);
            return $this->successResponse($analytics, 'Enrollment analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve enrollment analytics');
        }
    }

    /**
     * Get revenue analytics
     */
    public function getRevenueAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', 'month');
            $courseId = $request->get('course_id');
            $userId = auth('sanctum')->id();
            
            $analytics = $this->analyticsService->getRevenueAnalytics($userId, $period, $courseId);
            return $this->successResponse($analytics, 'Revenue analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve revenue analytics');
        }
    }

    /**
     * Get student progress analytics
     */
    public function getStudentProgressAnalytics(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getStudentProgressAnalytics($courseId, $userId);
            return $this->successResponse($analytics, 'Student progress analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get content engagement analytics
     */
    public function getContentEngagementAnalytics(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getContentEngagementAnalytics($courseId, $userId);
            return $this->successResponse($analytics, 'Content engagement analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get time spent analytics
     */
    public function getTimeSpentAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', 'week');
            $courseId = $request->get('course_id');
            $userId = auth('sanctum')->id();
            
            $analytics = $this->analyticsService->getTimeSpentAnalytics($userId, $period, $courseId);
            return $this->successResponse($analytics, 'Time spent analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve time spent analytics');
        }
    }

    /**
     * Get completion rate analytics
     */
    public function getCompletionRateAnalytics(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getCompletionRateAnalytics($courseId, $userId);
            return $this->successResponse($analytics, 'Completion rate analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get assessment analytics
     */
    public function getAssessmentAnalytics(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getAssessmentAnalytics($courseId, $userId);
            return $this->successResponse($analytics, 'Assessment analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Export analytics report
     */
    public function exportReport(Request $request): JsonResponse
    {
        try {
            $type = $request->get('type', 'course'); // course, user, revenue, enrollment
            $format = $request->get('format', 'pdf'); // pdf, excel, csv
            $period = $request->get('period', 'month');
            $courseId = $request->get('course_id');
            $userId = auth('sanctum')->id();
            
            $report = $this->analyticsService->exportReport($userId, $type, $format, $period, $courseId);
            return $this->successResponse($report, 'Report exported successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get real-time analytics
     */
    public function getRealTimeAnalytics(): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $analytics = $this->analyticsService->getRealTimeAnalytics($userId);
            return $this->successResponse($analytics, 'Real-time analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve real-time analytics');
        }
    }
}
