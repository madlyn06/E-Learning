<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\MobileService;

class MobileController extends BaseController
{
    protected $mobileService;

    public function __construct(MobileService $mobileService)
    {
        $this->mobileService = $mobileService;
    }

    /**
     * Get mobile dashboard data
     */
    public function getDashboard(): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $dashboard = $this->mobileService->getDashboard($userId);
            return $this->successResponse($dashboard, 'Mobile dashboard retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve mobile dashboard');
        }
    }

    /**
     * Get offline content
     */
    public function getOfflineContent(Request $request): JsonResponse
    {
        try {
            $courseId = $request->get('course_id');
            $userId = auth('sanctum')->id();
            
            $content = $this->mobileService->getOfflineContent($userId, $courseId);
            return $this->successResponse($content, 'Offline content retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Download course for offline use
     */
    public function downloadCourse(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $downloadInfo = $this->mobileService->downloadCourse($userId, $courseId);
            return $this->successResponse($downloadInfo, 'Course download started successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get download progress
     */
    public function getDownloadProgress(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $progress = $this->mobileService->getDownloadProgress($userId, $courseId);
            return $this->successResponse($progress, 'Download progress retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Cancel download
     */
    public function cancelDownload(int $courseId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $this->mobileService->cancelDownload($userId, $courseId);
            return $this->successResponse(null, 'Download cancelled successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Sync offline progress
     */
    public function syncOfflineProgress(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'course_id' => 'required|integer',
                'lessons' => 'required|array',
                'lessons.*.lesson_id' => 'required|integer',
                'lessons.*.progress_percentage' => 'required|integer|min:0|max:100',
                'lessons.*.time_spent' => 'required|integer|min:0',
                'lessons.*.completed_at' => 'nullable|date'
            ]);

            $userId = auth('sanctum')->id();
            $this->mobileService->syncOfflineProgress($userId, $data);
            return $this->successResponse(null, 'Offline progress synced successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get mobile notifications
     */
    public function getMobileNotifications(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 20);
            $lastSync = $request->get('last_sync');
            $userId = auth('sanctum')->id();
            
            $notifications = $this->mobileService->getMobileNotifications($userId, $perPage, $lastSync);
            return $this->paginatedResponse($notifications, 'Mobile notifications retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve mobile notifications');
        }
    }

    /**
     * Mark mobile notification as read
     */
    public function markNotificationRead(int $notificationId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $this->mobileService->markNotificationRead($userId, $notificationId);
            return $this->successResponse(null, 'Notification marked as read');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get mobile app settings
     */
    public function getAppSettings(): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $settings = $this->mobileService->getAppSettings($userId);
            return $this->successResponse($settings, 'App settings retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve app settings');
        }
    }

    /**
     * Update mobile app settings
     */
    public function updateAppSettings(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'auto_download' => 'boolean',
                'wifi_only_download' => 'boolean',
                'push_notifications' => 'boolean',
                'dark_mode' => 'boolean',
                'font_size' => 'string|in:small,medium,large',
                'language' => 'string|in:en,vi,zh,ja,ko',
                'offline_mode' => 'boolean',
                'data_saver' => 'boolean'
            ]);

            $userId = auth('sanctum')->id();
            $this->mobileService->updateAppSettings($userId, $data);
            return $this->successResponse(null, 'App settings updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get mobile course list
     */
    public function getMobileCourseList(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 10);
            $userId = auth('sanctum')->id();
            
            $courses = $this->mobileService->getMobileCourseList($userId, $filters, $perPage);
            return $this->paginatedResponse($courses, 'Mobile course list retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve mobile course list');
        }
    }

    /**
     * Get mobile lesson content
     */
    public function getMobileLessonContent(int $lessonId): JsonResponse
    {
        try {
            $userId = auth('sanctum')->id();
            $content = $this->mobileService->getMobileLessonContent($userId, $lessonId);
            return $this->successResponse($content, 'Mobile lesson content retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update mobile learning session
     */
    public function updateLearningSession(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'course_id' => 'required|integer',
                'lesson_id' => 'required|integer',
                'action' => 'required|string|in:start,pause,resume,complete',
                'timestamp' => 'required|integer',
                'device_info' => 'nullable|array'
            ]);

            $userId = auth('sanctum')->id();
            $this->mobileService->updateLearningSession($userId, $data);
            return $this->successResponse(null, 'Learning session updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get mobile search suggestions
     */
    public function getMobileSearchSuggestions(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $limit = $request->get('limit', 5);
            
            if (empty($query)) {
                return $this->successResponse([], 'No suggestions available');
            }

            $suggestions = $this->mobileService->getMobileSearchSuggestions($query, $limit);
            return $this->successResponse($suggestions, 'Mobile search suggestions retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve mobile search suggestions');
        }
    }

    /**
     * Get mobile app version info
     */
    public function getAppVersionInfo(): JsonResponse
    {
        try {
            $versionInfo = $this->mobileService->getAppVersionInfo();
            return $this->successResponse($versionInfo, 'App version info retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve app version info');
        }
    }

    /**
     * Report mobile app crash
     */
    public function reportCrash(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'error_message' => 'required|string',
                'stack_trace' => 'nullable|string',
                'device_info' => 'required|array',
                'app_version' => 'required|string',
                'os_version' => 'required|string'
            ]);

            $userId = auth('sanctum')->id();
            $this->mobileService->reportCrash($userId, $data);
            return $this->successResponse(null, 'Crash report submitted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
