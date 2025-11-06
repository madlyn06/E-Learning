<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\NotificationService;

class NotificationController extends BaseController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get user notifications
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 20);
            $type = $request->get('type'); // all, course, system, payment, etc.
            
            $notifications = $this->notificationService->getUserNotifications(auth('sanctum')->id(), $type, $perPage);
            return $this->paginatedResponse($notifications, 'Notifications retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve notifications');
        }
    }

    /**
     * Get unread notifications count
     */
    public function getUnread(): JsonResponse
    {
        try {
            $count = $this->notificationService->getUnreadCount(auth('sanctum')->id());
            return $this->successResponse(['unread_count' => $count], 'Unread count retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to get unread count');
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $id): JsonResponse
    {
        try {
            $this->notificationService->markAsRead(auth('sanctum')->id(), $id);
            return $this->successResponse(null, 'Notification marked as read');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            $this->notificationService->markAllAsRead(auth('sanctum')->id());
            return $this->successResponse(null, 'All notifications marked as read');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to mark all notifications as read');
        }
    }

    /**
     * Update notification settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        try {
            $settings = $request->validate([
                'email_notifications' => 'boolean',
                'push_notifications' => 'boolean',
                'course_notifications' => 'boolean',
                'payment_notifications' => 'boolean',
                'system_notifications' => 'boolean',
                'marketing_notifications' => 'boolean'
            ]);

            $this->notificationService->updateUserSettings(auth('sanctum')->id(), $settings);
            return $this->successResponse(null, 'Notification settings updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get notification settings
     */
    public function getSettings(): JsonResponse
    {
        try {
            $settings = $this->notificationService->getUserSettings(auth('sanctum')->id());
            return $this->successResponse($settings, 'Notification settings retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve notification settings');
        }
    }

    /**
     * Delete notification
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->notificationService->deleteNotification(auth('sanctum')->id(), $id);
            return $this->deletedResponse('Notification deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Delete all notifications
     */
    public function deleteAll(): JsonResponse
    {
        try {
            $this->notificationService->deleteAllNotifications(auth('sanctum')->id());
            return $this->deletedResponse('All notifications deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to delete all notifications');
        }
    }

    /**
     * Get notification preferences
     */
    public function getPreferences(): JsonResponse
    {
        try {
            $preferences = $this->notificationService->getNotificationPreferences(auth('sanctum')->id());
            return $this->successResponse($preferences, 'Notification preferences retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve notification preferences');
        }
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        try {
            $preferences = $request->validate([
                'preferences' => 'required|array',
                'preferences.*.type' => 'required|string',
                'preferences.*.email' => 'boolean',
                'preferences.*.push' => 'boolean',
                'preferences.*.in_app' => 'boolean'
            ]);

            $this->notificationService->updateNotificationPreferences(auth('sanctum')->id(), $preferences['preferences']);
            return $this->successResponse(null, 'Notification preferences updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
