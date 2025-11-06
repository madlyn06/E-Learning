<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Newnet\Core\Repositories\BaseRepository;

class MobileRepository extends BaseRepository
{
    public function getDashboard(int $userId): array
    {
        $enrollments = DB::table('elearning__enrollments as e')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->where('e.user_id', $userId)
            ->select('e.*', 'c.name as course_name', 'c.thumbnail')
            ->orderBy('e.updated_at', 'desc')
            ->limit(5)
            ->get();

        $recentLessons = DB::table('elearning__tracking_lessons as tl')
            ->join('elearning__lessons as l', 'tl.lesson_id', '=', 'l.id')
            ->join('elearning__courses as c', 'tl.course_id', '=', 'c.id')
            ->where('tl.user_id', $userId)
            ->select('tl.*', 'l.name as lesson_name', 'c.name as course_name')
            ->orderBy('tl.updated_at', 'desc')
            ->limit(3)
            ->get();

        return [
            'recent_enrollments' => $enrollments,
            'recent_lessons' => $recentLessons,
            'last_updated' => now()
        ];
    }

    public function getOfflineContent(int $userId, ?int $courseId = null): array
    {
        $query = DB::table('elearning__mobile_downloads as md')
            ->join('elearning__courses as c', 'md.course_id', '=', 'c.id')
            ->where('md.user_id', $userId)
            ->where('md.status', 'downloaded');

        if ($courseId) {
            $query->where('md.course_id', $courseId);
        }

        $downloads = $query->select('md.*', 'c.name as course_name', 'c.thumbnail')
            ->orderBy('md.updated_at', 'desc')
            ->get();

        return [
            'downloads' => $downloads,
            'last_updated' => now()
        ];
    }

    public function downloadCourse(int $userId, int $courseId): array
    {
        $downloadData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => 'queued',
            'total_size' => 1024 * 1024 * 100, // 100 MB placeholder
            'downloaded_size' => 0,
            'progress_percentage' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $downloadId = DB::table('elearning__mobile_downloads')->insertGetId($downloadData);

        return [
            'download_id' => $downloadId,
            'course_id' => $courseId,
            'status' => 'queued'
        ];
    }

    public function getDownloadProgress(int $courseId): array
    {
        $userId = auth('sanctum')->id();
        
        $download = DB::table('elearning__mobile_downloads')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$download) {
            return ['status' => 'not_found'];
        }

        return [
            'download_id' => $download->id,
            'course_id' => $courseId,
            'status' => $download->status,
            'progress_percentage' => $download->progress_percentage,
            'last_updated' => $download->updated_at
        ];
    }

    public function cancelDownload(int $courseId): bool
    {
        $userId = auth('sanctum')->id();
        
        return DB::table('elearning__mobile_downloads')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update(['status' => 'cancelled', 'updated_at' => now()]) > 0;
    }

    public function syncOfflineProgress(int $userId, array $data): bool
    {
        foreach ($data['lessons'] as $lessonData) {
            DB::table('elearning__tracking_lessons')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'course_id' => $data['course_id'],
                    'lesson_id' => $lessonData['lesson_id']
                ],
                [
                    'progress_percentage' => $lessonData['progress_percentage'],
                    'time_spent' => $lessonData['time_spent'],
                    'completed_at' => $lessonData['completed_at'] ?? null,
                    'updated_at' => now()
                ]
            );
        }

        return true;
    }

    public function getMobileNotifications(int $userId, int $perPage = 20, ?string $lastSync = null): LengthAwarePaginator
    {
        $query = DB::table('elearning__notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($lastSync) {
            $query->where('created_at', '>', $lastSync);
        }

        return $query->paginate($perPage);
    }

    public function markNotificationRead(int $userId, int $notificationId): bool
    {
        return DB::table('elearning__notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true, 'read_at' => now()]) > 0;
    }

    public function getAppSettings(int $userId): array
    {
        $settings = DB::table('elearning__mobile_app_settings')
            ->where('user_id', $userId)
            ->first();

        if (!$settings) {
            return [
                'auto_download' => false,
                'wifi_only_download' => true,
                'push_notifications' => true,
                'dark_mode' => false,
                'font_size' => 'medium',
                'language' => 'en',
                'offline_mode' => false,
                'data_saver' => true
            ];
        }

        return (array) $settings;
    }

    public function updateAppSettings(int $userId, array $data): bool
    {
        $existingSettings = DB::table('elearning__mobile_app_settings')
            ->where('user_id', $userId)
            ->first();

        if ($existingSettings) {
            return DB::table('elearning__mobile_app_settings')
                ->where('user_id', $userId)
                ->update(array_merge($data, ['updated_at' => now()])) > 0;
        } else {
            $data['user_id'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = now();
            return DB::table('elearning__mobile_app_settings')->insert($data) > 0;
        }
    }

    public function getMobileCourseList(int $userId, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('elearning__courses as c')
            ->join('elearning__enrollments as e', 'c.id', '=', 'e.course_id')
            ->where('e.user_id', $userId)
            ->where('c.is_published', true)
            ->select('c.*', 'e.status as enrollment_status', 'e.completion_percentage');

        if (isset($filters['status'])) {
            $query->where('e.status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where('c.name', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('e.updated_at', 'desc')->paginate($perPage);
    }

    public function getMobileLessonContent(int $userId, int $lessonId): array
    {
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('l.id', $lessonId)
            ->select('l.*', 's.name as section_name', 'c.name as course_name')
            ->first();

        if (!$lesson) {
            return ['error' => 'Lesson not found'];
        }

        return [
            'lesson' => $lesson,
            'last_updated' => now()
        ];
    }

    public function updateLearningSession(int $userId, array $data): bool
    {
        $sessionData = [
            'user_id' => $userId,
            'course_id' => $data['course_id'],
            'lesson_id' => $data['lesson_id'],
            'action' => $data['action'],
            'timestamp' => $data['timestamp'],
            'device_info' => json_encode($data['device_info'] ?? []),
            'created_at' => now()
        ];

        return DB::table('elearning__mobile_learning_sessions')->insert($sessionData) > 0;
    }

    public function getSearchSuggestions(string $query, int $limit = 5): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        $suggestions = [];

        $courseSuggestions = DB::table('elearning__courses')
            ->where('is_published', true)
            ->where('name', 'like', "%{$query}%")
            ->select('name', 'id')
            ->limit(3)
            ->get();

        $suggestions['courses'] = $courseSuggestions->toArray();

        return $suggestions;
    }

    public function reportCrash(int $userId, array $data): bool
    {
        $crashData = [
            'user_id' => $userId,
            'error_message' => $data['error_message'],
            'stack_trace' => $data['stack_trace'] ?? null,
            'device_info' => json_encode($data['device_info']),
            'app_version' => $data['app_version'],
            'os_version' => $data['os_version'],
            'created_at' => now()
        ];

        return DB::table('elearning__mobile_crash_reports')->insert($crashData) > 0;
    }
}
