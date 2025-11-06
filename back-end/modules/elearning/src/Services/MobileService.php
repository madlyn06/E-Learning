<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;

class MobileService
{
    protected $courseRepository;
    protected $userRepository;

    public function __construct(
        CourseRepository $courseRepository,
        UserRepository $userRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get mobile dashboard data
     */
    public function getDashboard(int $userId)
    {
        $cacheKey = "mobile_dashboard_{$userId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId) {
            return $this->getDashboardFromDB($userId);
        });
    }

    /**
     * Get offline content
     */
    public function getOfflineContent(int $userId, ?int $courseId = null)
    {
        $cacheKey = "offline_content_{$userId}_{$courseId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($userId, $courseId) {
            return $this->getOfflineContentFromDB($userId, $courseId);
        });
    }

    /**
     * Download course for offline use
     */
    public function downloadCourse(int $userId, int $courseId): array
    {
        // Check if user is enrolled in the course
        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to download it');
        }

        // Check if course is already downloaded
        $existingDownload = DB::table('elearning__mobile_downloads')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingDownload && $existingDownload->status === 'downloaded') {
            throw new \Exception('Course is already downloaded');
        }

        // Get course content size
        $course = $this->courseRepository->findById($courseId);
        if (!$course) {
            throw new \Exception('Course not found');
        }

        $contentSize = $this->calculateCourseContentSize($courseId);

        // Create or update download record
        $downloadData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => 'queued',
            'total_size' => $contentSize,
            'downloaded_size' => 0,
            'progress_percentage' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ];

        if ($existingDownload) {
            DB::table('elearning__mobile_downloads')
                ->where('id', $existingDownload->id)
                ->update($downloadData);
            $downloadId = $existingDownload->id;
        } else {
            $downloadId = DB::table('elearning__mobile_downloads')->insertGetId($downloadData);
        }

        // Start download process (this would typically be a job)
        $this->startDownloadProcess($downloadId);

        return [
            'download_id' => $downloadId,
            'course_id' => $courseId,
            'total_size' => $contentSize,
            'status' => 'queued',
            'estimated_time' => $this->estimateDownloadTime($contentSize)
        ];
    }

    /**
     * Get download progress
     */
    public function getDownloadProgress(int $courseId): array
    {
        $userId = auth('sanctum')->id();
        
        $download = DB::table('elearning__mobile_downloads')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$download) {
            throw new \Exception('Download not found');
        }

        return [
            'download_id' => $download->id,
            'course_id' => $courseId,
            'status' => $download->status,
            'total_size' => $download->total_size,
            'downloaded_size' => $download->downloaded_size,
            'progress_percentage' => $download->progress_percentage,
            'estimated_time_remaining' => $this->estimateTimeRemaining($download),
            'last_updated' => $download->updated_at
        ];
    }

    /**
     * Cancel download
     */
    public function cancelDownload(int $courseId): bool
    {
        $userId = auth('sanctum')->id();
        
        $download = DB::table('elearning__mobile_downloads')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$download) {
            throw new \Exception('Download not found');
        }

        if ($download->status === 'downloaded') {
            throw new \Exception('Cannot cancel completed download');
        }

        return DB::table('elearning__mobile_downloads')
            ->where('id', $download->id)
            ->update([
                'status' => 'cancelled',
                'updated_at' => now()
            ]) > 0;
    }

    /**
     * Sync offline progress
     */
    public function syncOfflineProgress(int $userId, array $data): bool
    {
        DB::beginTransaction();

        try {
            foreach ($data['lessons'] as $lessonData) {
                // Update or create tracking record
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

            // Update course progress
            $this->updateCourseProgress($userId, $data['course_id']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get mobile notifications
     */
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

    /**
     * Mark mobile notification as read
     */
    public function markNotificationRead(int $userId, int $notificationId): bool
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
     * Get mobile app settings
     */
    public function getAppSettings(int $userId): array
    {
        $settings = DB::table('elearning__mobile_app_settings')
            ->where('user_id', $userId)
            ->first();

        if (!$settings) {
            // Return default settings
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

    /**
     * Update mobile app settings
     */
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

    /**
     * Get mobile course list
     */
    public function getMobileCourseList(int $userId, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('elearning__courses as c')
            ->join('elearning__enrollments as e', 'c.id', '=', 'e.course_id')
            ->where('e.user_id', $userId)
            ->where('c.is_published', true)
            ->select('c.*', 'e.status as enrollment_status', 'e.completion_percentage');

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('e.status', $filters['status']);
        }

        if (isset($filters['category_id'])) {
            $query->join('elearning__category_course as cc', 'c.id', '=', 'cc.course_id')
                  ->where('cc.category_id', $filters['category_id']);
        }

        if (isset($filters['search'])) {
            $query->where('c.name', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('e.updated_at', 'desc')->paginate($perPage);
    }

    /**
     * Get mobile lesson content
     */
    public function getMobileLessonContent(int $userId, int $lessonId): array
    {
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('l.id', $lessonId)
            ->select('l.*', 's.name as section_name', 'c.name as course_name')
            ->first();

        if (!$lesson) {
            throw new \Exception('Lesson not found');
        }

        // Check if user is enrolled in the course
        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to access lesson content');
        }

        // Get lesson tracking data
        $tracking = DB::table('elearning__tracking_lessons')
            ->where('user_id', $userId)
            ->where('course_id', $lesson->course_id)
            ->where('lesson_id', $lessonId)
            ->first();

        // Get next and previous lessons
        $nextLesson = $this->getNextLesson($lesson->section_id, $lesson->order);
        $previousLesson = $this->getPreviousLesson($lesson->section_id, $lesson->order);

        return [
            'lesson' => $lesson,
            'tracking' => $tracking,
            'next_lesson' => $nextLesson,
            'previous_lesson' => $previousLesson,
            'is_downloaded' => $this->isLessonDownloaded($userId, $lessonId)
        ];
    }

    /**
     * Update mobile learning session
     */
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

    /**
     * Get mobile search suggestions
     */
    public function getMobileSearchSuggestions(string $query, int $limit = 5)
    {
        if (strlen($query) < 2) {
            return [];
        }

        $cacheKey = "mobile_search_suggestions_" . md5($query);
        
        return Cache::remember($cacheKey, 1800, function () use ($query, $limit) {
            $suggestions = [];

            // Course name suggestions
            $courseSuggestions = DB::table('elearning__courses')
                ->where('is_published', true)
                ->where('name', 'like', "%{$query}%")
                ->select('name', 'id')
                ->limit(3)
                ->get();

            $suggestions['courses'] = $courseSuggestions->toArray();

            // Lesson name suggestions
            $lessonSuggestions = DB::table('elearning__lessons as l')
                ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
                ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
                ->where('c.is_published', true)
                ->where('l.name', 'like', "%{$query}%")
                ->select('l.name', 'l.id', 'c.name as course_name')
                ->limit(2)
                ->get();

            $suggestions['lessons'] = $lessonSuggestions->toArray();

            return $suggestions;
        });
    }

    /**
     * Get mobile app version info
     */
    public function getAppVersionInfo(): array
    {
        return [
            'current_version' => '1.0.0',
            'minimum_version' => '1.0.0',
            'latest_version' => '1.0.0',
            'update_required' => false,
            'update_url' => null,
            'changelog' => 'Initial release',
            'last_checked' => now()
        ];
    }

    /**
     * Report mobile app crash
     */
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

    // Private helper methods
    private function getDashboardFromDB(int $userId): array
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

        $upcomingSessions = DB::table('elearning__live_sessions as ls')
            ->join('elearning__courses as c', 'ls.course_id', '=', 'c.id')
            ->join('elearning__enrollments as e', 'c.id', '=', 'e.course_id')
            ->where('e.user_id', $userId)
            ->where('ls.start_time', '>', now())
            ->where('ls.status', 'scheduled')
            ->select('ls.*', 'c.name as course_name')
            ->orderBy('ls.start_time', 'asc')
            ->limit(3)
            ->get();

        return [
            'recent_enrollments' => $enrollments,
            'recent_lessons' => $recentLessons,
            'upcoming_sessions' => $upcomingSessions,
            'last_updated' => now()
        ];
    }

    private function getOfflineContentFromDB(int $userId, ?int $courseId): array
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

        $totalSize = $downloads->sum('total_size');
        $totalDownloaded = $downloads->sum('downloaded_size');

        return [
            'downloads' => $downloads,
            'total_size' => $totalSize,
            'total_downloaded' => $totalDownloaded,
            'last_updated' => now()
        ];
    }

    private function calculateCourseContentSize(int $courseId): int
    {
        // This would calculate the actual size of course content
        // For now, return a placeholder value
        return 1024 * 1024 * 100; // 100 MB
    }

    private function startDownloadProcess(int $downloadId): void
    {
        // This would typically start a background job
        // For now, just log the action
        \Log::info("Starting download process for download ID: {$downloadId}");
    }

    private function estimateDownloadTime(int $size): int
    {
        // Simple estimation based on size
        // This would be more sophisticated in a real implementation
        return round($size / (1024 * 1024 * 2)); // Assuming 2 MB/s
    }

    private function estimateTimeRemaining($download): int
    {
        if ($download->progress_percentage >= 100) {
            return 0;
        }

        $remainingSize = $download->total_size - $download->downloaded_size;
        return round($remainingSize / (1024 * 1024 * 2)); // Assuming 2 MB/s
    }

    private function updateCourseProgress(int $userId, int $courseId): void
    {
        // This would update the overall course progress
        // Implementation similar to ProgressService
        \Log::info("Updating course progress for user {$userId}, course {$courseId}");
    }

    private function getNextLesson(int $sectionId, int $currentOrder): ?array
    {
        $nextLesson = DB::table('elearning__lessons')
            ->where('section_id', $sectionId)
            ->where('order', '>', $currentOrder)
            ->orderBy('order', 'asc')
            ->first();

        return $nextLesson ? (array) $nextLesson : null;
    }

    private function getPreviousLesson(int $sectionId, int $currentOrder): ?array
    {
        $previousLesson = DB::table('elearning__lessons')
            ->where('section_id', $sectionId)
            ->where('order', '<', $currentOrder)
            ->orderBy('order', 'desc')
            ->first();

        return $previousLesson ? (array) $previousLesson : null;
    }

    private function isLessonDownloaded(int $userId, int $lessonId): bool
    {
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->where('l.id', $lessonId)
            ->first();

        if (!$lesson) {
            return false;
        }

        $download = DB::table('elearning__mobile_downloads')
            ->where('user_id', $userId)
            ->where('course_id', $lesson->course_id)
            ->where('status', 'downloaded')
            ->first();

        return $download !== null;
    }
}
