<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Newnet\Core\Repositories\BaseRepository;

class NotificationRepository extends BaseRepository
{
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

    public function getUnreadCount(int $userId): int
    {
        return DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead(int $userId, int $notificationId): bool
    {
        return DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true, 'read_at' => now()]) > 0;
    }

    public function markAllAsRead(int $userId): bool
    {
        return DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]) > 0;
    }

    public function deleteNotification(int $userId, int $notificationId): bool
    {
        return DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function deleteAll(int $userId): bool
    {
        return DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function send(int $userId, string $type, string $title, string $message, array $data = []): bool
    {
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

        return DB::table('elearning__notifications')->insert($notificationData) > 0;
    }

    public function getStatistics(int $userId): array
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

        return [
            'total_notifications' => $totalNotifications,
            'unread_notifications' => $unreadNotifications,
            'notifications_by_type' => $notificationsByType,
            'last_updated' => now()
        ];
    }
}
