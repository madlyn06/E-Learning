<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\ProgressService;

class ProgressController extends BaseController
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Get course progress for authenticated user
     */
    public function getCourseProgress($courseId): JsonResponse
    {
        try {
            $data = $this->progressService->getCourseProgress($courseId);
            return $this->successResponse($data, 'Course progress retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Mark lesson as complete
     */
    public function markLessonComplete($lessonId): JsonResponse
    {
        try {
            $this->progressService->markLessonComplete($lessonId);
            return $this->successResponse(null, 'Lesson marked as complete');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Track lesson progress (for video lessons)
     */
    public function trackLessonProgress(Request $request, $lessonId): JsonResponse
    {
        try {
            $this->progressService->trackLessonProgress($lessonId, $request->all());
            return $this->successResponse(null, 'Progress tracked successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get progress overview for authenticated user
     */
    public function getProgressOverview(): JsonResponse
    {
        try {
            $data = $this->progressService->getProgressOverview();
            return $this->successResponse($data, 'Progress overview retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve progress overview');
        }
    }

    /**
     * Get course completion certificate
     */
    public function getCertificate($courseId): JsonResponse
    {
        try {
            $data = $this->progressService->getCertificate($courseId);
            return $this->successResponse($data, 'Certificate generated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }
    }

    /**
     * Get course statistics for instructors
     */
    public function getCourseStatistics($courseId): JsonResponse
    {
        try {
            $data = $this->progressService->getCourseStatistics($courseId);
            return $this->successResponse($data, 'Course statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get user's learning path
     */
    public function getLearningPath($courseId): JsonResponse
    {
        try {
            $data = $this->progressService->getLearningPath($courseId);
            return $this->successResponse($data, 'Learning path retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get estimated time to completion
     */
    public function getEstimatedTimeToCompletion($courseId): JsonResponse
    {
        try {
            $data = $this->progressService->getEstimatedTimeToCompletion($courseId);
            return $this->successResponse($data, 'Estimated time to completion retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
