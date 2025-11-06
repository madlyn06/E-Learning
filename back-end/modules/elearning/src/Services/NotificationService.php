<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Elearning\Repositories\UserRepository;

class NotificationService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(int $userId, ?string $type = null, int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(int $userId): int
    {
        return DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $userId, int $notificationId): bool
    {
        $notification = DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            throw new \Exception('Notification not found or access denied');
        }

        return DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->update(['is_read' => true, 'read_at' => now()]) > 0;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(int $userId): bool
    {
        return DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]) > 0;
    }

    /**
     * Update user notification settings
     */
    public function updateUserSettings(int $userId, array $settings): bool
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Update or create notification settings
        $existingSettings = DB::table('elearning__notification_settings')
            ->where('user_id', $userId)
            ->first();

        if ($existingSettings) {
            return DB::table('elearning__notification_settings')
                ->where('user_id', $userId)
                ->update($settings) > 0;
        } else {
            $settings['user_id'] = $userId;
            return DB::table('elearning__notification_settings')->insert($settings) > 0;
        }
    }

    /**
     * Get user notification settings
     */
    public function getUserSettings(int $userId): array
    {
        $settings = DB::table('elearning__notification_settings')
            ->where('user_id', $userId)
            ->first();

        if (!$settings) {
            // Return default settings
            return [
                'email_notifications' => true,
                'push_notifications' => true,
                'course_notifications' => true,
                'payment_notifications' => true,
                'system_notifications' => true,
                'marketing_notifications' => false
            ];
        }

        return (array) $settings;
    }

    /**
     * Delete notification
     */
    public function deleteNotification(int $userId, int $notificationId): bool
    {
        $notification = DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            throw new \Exception('Notification not found or access denied');
        }

        return DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->delete() > 0;
    }

    /**
     * Delete all notifications
     */
    public function deleteAllNotifications(int $userId): bool
    {
        return DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    /**
     * Get notification preferences
     */
    public function getNotificationPreferences(int $userId): array
    {
        $preferences = DB::table('elearning__notification_preferences')
            ->where('user_id', $userId)
            ->get();

        if ($preferences->isEmpty()) {
            // Return default preferences
            return [
                [
                    'type' => 'course_updates',
                    'email' => true,
                    'push' => true,
                    'in_app' => true
                ],
                [
                    'type' => 'assignment_deadlines',
                    'email' => true,
                    'push' => true,
                    'in_app' => true
                ],
                [
                    'type' => 'live_sessions',
                    'email' => true,
                    'push' => true,
                    'in_app' => true
                ],
                [
                    'type' => 'discussion_replies',
                    'email' => false,
                    'push' => true,
                    'in_app' => true
                ],
                [
                    'type' => 'payment_confirmations',
                    'email' => true,
                    'push' => false,
                    'in_app' => true
                ]
            ];
        }

        return $preferences->toArray();
    }

    /**
     * Update notification preferences
     */
    public function updateNotificationPreferences(int $userId, array $preferences): bool
    {
        // Delete existing preferences
        DB::table('elearning__notification_preferences')
            ->where('user_id', $userId)
            ->delete();

        // Insert new preferences
        $preferencesToInsert = [];
        foreach ($preferences as $pref) {
            $preferencesToInsert[] = [
                'user_id' => $userId,
                'type' => $pref['type'],
                'email' => $pref['email'] ?? false,
                'push' => $pref['push'] ?? false,
                'in_app' => $pref['in_app'] ?? true,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        return DB::table('elearning__notification_preferences')->insert($preferencesToInsert) > 0;
    }

    /**
     * Send notification to user
     */
    public function sendNotification(int $userId, string $type, string $title, string $message, array $data = []): bool
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            return false;
        }

        // Check if user has enabled this type of notification
        $settings = $this->getUserSettings($userId);
        $preferences = $this->getNotificationPreferences($userId);

        $notificationData = [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => json_encode($data),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $notificationId = DB::table('elearning__notifications')->insertGetId($notificationData);

        // Send email notification if enabled
        if ($settings['email_notifications'] ?? true) {
            $this->sendEmailNotification($user, $title, $message, $data);
        }

        // Send push notification if enabled
        if ($settings['push_notifications'] ?? true) {
            $this->sendPushNotification($user, $title, $message, $data);
        }

        return $notificationId > 0;
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotifications(array $userIds, string $type, string $title, string $message, array $data = []): int
    {
        $notifications = [];
        $now = now();

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => json_encode($data),
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        if (empty($notifications)) {
            return 0;
        }

        return DB::table('elearning__notifications')->insert($notifications) ? count($notifications) : 0;
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStatistics(int $userId): array
    {
        $totalNotifications = DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->count();

        $unreadNotifications = DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        $notificationsByType = DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        $recentNotifications = DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_notifications' => $totalNotifications,
            'unread_notifications' => $unreadNotifications,
            'notifications_by_type' => $notificationsByType,
            'recent_notifications' => $recentNotifications,
            'last_updated' => now()
        ];
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification($user, string $title, string $message, array $data): void
    {
        // This would integrate with Laravel's mail system
        // For now, just log the email notification
        \Log::info("Email notification sent to {$user->email}: {$title}");
    }

    /**
     * Send push notification
     */
    private function sendPushNotification($user, string $title, string $message, array $data): void
    {
        // This would integrate with push notification services like Firebase
        // For now, just log the push notification
        \Log::info("Push notification sent to user {$user->id}: {$title}");
    }

    /**
     * Clean old notifications
     */
    public function cleanOldNotifications(int $daysOld = 90): int
    {
        $cutoffDate = now()->subDays($daysOld);

        return DB::table('elearning__notifications')
            ->where('created_at', '<', $cutoffDate)
            ->where('is_read', true)
            ->delete();
    }

    /**
     * Get notification templates
     */
    public function getNotificationTemplates(): array
    {
        return [
            'course_enrollment' => [
                'title' => 'Course Enrollment Confirmation',
                'message' => 'You have been successfully enrolled in {course_name}',
                'variables' => ['course_name']
            ],
            'lesson_completed' => [
                'title' => 'Lesson Completed',
                'message' => 'Congratulations! You have completed {lesson_name}',
                'variables' => ['lesson_name']
            ],
            'assignment_due' => [
                'title' => 'Assignment Due Soon',
                'assignment_name' => 'Your assignment "{assignment_name}" is due in {time_remaining}',
                'variables' => ['assignment_name', 'time_remaining']
            ],
            'live_session_reminder' => [
                'title' => 'Live Session Reminder',
                'message' => 'Your live session "{session_name}" starts in {time_remaining}',
                'variables' => ['session_name', 'time_remaining']
            ],
            'payment_confirmation' => [
                'title' => 'Payment Confirmation',
                'message' => 'Your payment of {amount} for {course_name} has been confirmed',
                'variables' => ['amount', 'course_name']
            ]
        ];
    }
}
