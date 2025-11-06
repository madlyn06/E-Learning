<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;

class AnalyticsService
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
     * Get dashboard analytics for instructors
     */
    public function getDashboardAnalytics(int $userId)
    {
        $cacheKey = "dashboard_analytics_{$userId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId) {
            return $this->getDashboardAnalyticsFromDB($userId);
        });
    }

    /**
     * Get course analytics
     */
    public function getCourseAnalytics(int $courseId, int $userId)
    {
        // Check if user owns the course
        $course = $this->courseRepository->findById($courseId);
        if (!$course || $course->user_id !== $userId) {
            throw new \Exception('You can only view analytics for your own courses');
        }

        $cacheKey = "course_analytics_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($courseId) {
            return $this->getCourseAnalyticsFromDB($courseId);
        });
    }

    /**
     * Get user learning analytics
     */
    public function getUserLearningAnalytics(int $userId)
    {
        $cacheKey = "user_learning_analytics_{$userId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($userId) {
            return $this->getUserLearningAnalyticsFromDB($userId);
        });
    }

    /**
     * Get enrollment analytics
     */
    public function getEnrollmentAnalytics(int $userId, string $period = 'month', ?int $courseId = null)
    {
        $cacheKey = "enrollment_analytics_{$userId}_{$period}_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId, $period, $courseId) {
            return $this->getEnrollmentAnalyticsFromDB($userId, $period, $courseId);
        });
    }

    /**
     * Get revenue analytics
     */
    public function getRevenueAnalytics(int $userId, string $period = 'month', ?int $courseId = null)
    {
        $cacheKey = "revenue_analytics_{$userId}_{$period}_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId, $period, $courseId) {
            return $this->getRevenueAnalyticsFromDB($userId, $period, $courseId);
        });
    }

    /**
     * Get student progress analytics
     */
    public function getStudentProgressAnalytics(int $courseId, int $userId)
    {
        // Check if user owns the course
        $course = $this->courseRepository->findById($courseId);
        if (!$course || $course->user_id !== $userId) {
            throw new \Exception('You can only view analytics for your own courses');
        }

        $cacheKey = "student_progress_analytics_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($courseId) {
            return $this->getStudentProgressAnalyticsFromDB($courseId);
        });
    }

    /**
     * Get content engagement analytics
     */
    public function getContentEngagementAnalytics(int $courseId, int $userId)
    {
        // Check if user owns the course
        $course = $this->courseRepository->findById($courseId);
        if (!$course || $course->user_id !== $userId) {
            throw new \Exception('You can only view analytics for your own courses');
        }

        $cacheKey = "content_engagement_analytics_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($courseId) {
            return $this->getContentEngagementAnalyticsFromDB($courseId);
        });
    }

    /**
     * Get time spent analytics
     */
    public function getTimeSpentAnalytics(int $userId, string $period = 'week', ?int $courseId = null)
    {
        $cacheKey = "time_spent_analytics_{$userId}_{$period}_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId, $period, $courseId) {
            return $this->getTimeSpentAnalyticsFromDB($userId, $period, $courseId);
        });
    }

    /**
     * Get completion rate analytics
     */
    public function getCompletionRateAnalytics(int $courseId, int $userId)
    {
        // Check if user owns the course
        $course = $this->courseRepository->findById($courseId);
        if (!$course || $course->user_id !== $userId) {
            throw new \Exception('You can only view analytics for your own courses');
        }

        $cacheKey = "completion_rate_analytics_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($courseId) {
            return $this->getCompletionRateAnalyticsFromDB($courseId);
        });
    }

    /**
     * Get assessment analytics
     */
    public function getAssessmentAnalytics(int $courseId, int $userId)
    {
        // Check if user owns the course
        $course = $this->courseRepository->findById($courseId);
        if (!$course || $course->user_id !== $userId) {
            throw new \Exception('You can only view analytics for your own courses');
        }

        $cacheKey = "assessment_analytics_{$courseId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($courseId) {
            return $this->getAssessmentAnalyticsFromDB($courseId);
        });
    }

    /**
     * Export analytics report
     */
    public function exportReport(int $userId, string $type, string $format, string $period, ?int $courseId = null): array
    {
        // Validate report type
        $validTypes = ['course', 'user', 'revenue', 'enrollment'];
        if (!in_array($type, $validTypes)) {
            throw new \Exception('Invalid report type');
        }

        // Validate format
        $validFormats = ['pdf', 'excel', 'csv'];
        if (!in_array($format, $validFormats)) {
            throw new \Exception('Invalid export format');
        }

        // Generate report data
        $reportData = $this->generateReportData($userId, $type, $period, $courseId);
        
        // Return report information (actual export would be handled by a job)
        return [
            'type' => $type,
            'format' => $format,
            'period' => $period,
            'course_id' => $courseId,
            'generated_at' => now(),
            'download_url' => null, // Would be generated by export job
            'status' => 'processing'
        ];
    }

    /**
     * Get real-time analytics
     */
    public function getRealTimeAnalytics(int $userId)
    {
        $cacheKey = "real_time_analytics_{$userId}";
        
        return Cache::remember($cacheKey, 300, function () use ($userId) { // 5 minutes cache
            return $this->getRealTimeAnalyticsFromDB($userId);
        });
    }

    // Private methods for database queries
    private function getDashboardAnalyticsFromDB(int $userId): array
    {
        $totalCourses = DB::table('elearning__courses')
            ->where('user_id', $userId)
            ->count();

        $totalStudents = DB::table('elearning__enrollments as e')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->where('c.user_id', $userId)
            ->distinct('e.user_id')
            ->count('e.user_id');

        $totalRevenue = DB::table('elearning__payments as p')
            ->join('elearning__enrollments as e', 'p.enrollment_id', '=', 'e.id')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->where('c.user_id', $userId)
            ->where('p.status', 'completed')
            ->sum('p.amount');

        $recentEnrollments = DB::table('elearning__enrollments as e')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->join('elearning__users as u', 'e.user_id', '=', 'u.id')
            ->where('c.user_id', $userId)
            ->select('e.*', 'c.name as course_name', 'u.name as student_name')
            ->orderBy('e.created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'total_revenue' => $totalRevenue,
            'recent_enrollments' => $recentEnrollments,
            'last_updated' => now()
        ];
    }

    private function getCourseAnalyticsFromDB(int $courseId): array
    {
        $totalEnrollments = DB::table('elearning__enrollments')
            ->where('course_id', $courseId)
            ->count();

        $completedEnrollments = DB::table('elearning__enrollments')
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->count();

        $averageProgress = DB::table('elearning__enrollments')
            ->where('course_id', $courseId)
            ->avg('completion_percentage') ?? 0;

        $revenue = DB::table('elearning__payments as p')
            ->join('elearning__enrollments as e', 'p.enrollment_id', '=', 'e.id')
            ->where('e.course_id', $courseId)
            ->where('p.status', 'completed')
            ->sum('p.amount');

        return [
            'total_enrollments' => $totalEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'completion_rate' => $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0,
            'average_progress' => round($averageProgress, 2),
            'total_revenue' => $revenue,
            'last_updated' => now()
        ];
    }

    private function getUserLearningAnalyticsFromDB(int $userId): array
    {
        $totalEnrollments = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->count();

        $completedCourses = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $totalTimeSpent = DB::table('elearning__tracking_lessons')
            ->where('user_id', $userId)
            ->sum('time_spent') ?? 0;

        $averageRating = DB::table('elearning__reviews as r')
            ->join('elearning__enrollments as e', 'r.enrollment_id', '=', 'e.id')
            ->where('e.user_id', $userId)
            ->avg('r.rating') ?? 0;

        return [
            'total_enrollments' => $totalEnrollments,
            'completed_courses' => $completedCourses,
            'completion_rate' => $totalEnrollments > 0 ? round(($completedCourses / $totalEnrollments) * 100, 2) : 0,
            'total_time_spent' => $totalTimeSpent,
            'average_rating' => round($averageRating, 2),
            'last_updated' => now()
        ];
    }

    private function getEnrollmentAnalyticsFromDB(int $userId, string $period, ?int $courseId): array
    {
        $dateFilter = $this->getDateFilter($period);
        
        $query = DB::table('elearning__enrollments as e')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->where('c.user_id', $userId)
            ->where('e.created_at', '>=', $dateFilter);

        if ($courseId) {
            $query->where('e.course_id', $courseId);
        }

        $enrollments = $query->select(
            DB::raw('DATE(e.created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'period' => $period,
            'course_id' => $courseId,
            'enrollments' => $enrollments,
            'total_enrollments' => $enrollments->sum('count'),
            'last_updated' => now()
        ];
    }

    private function getRevenueAnalyticsFromDB(int $userId, string $period, ?int $courseId): array
    {
        $dateFilter = $this->getDateFilter($period);
        
        $query = DB::table('elearning__payments as p')
            ->join('elearning__enrollments as e', 'p.enrollment_id', '=', 'e.id')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->where('c.user_id', $userId)
            ->where('p.status', 'completed')
            ->where('p.created_at', '>=', $dateFilter);

        if ($courseId) {
            $query->where('e.course_id', $courseId);
        }

        $revenue = $query->select(
            DB::raw('DATE(p.created_at) as date'),
            DB::raw('SUM(p.amount) as amount')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'period' => $period,
            'course_id' => $courseId,
            'revenue' => $revenue,
            'total_revenue' => $revenue->sum('amount'),
            'last_updated' => now()
        ];
    }

    private function getStudentProgressAnalyticsFromDB(int $courseId): array
    {
        $enrollments = DB::table('elearning__enrollments')
            ->where('course_id', $courseId)
            ->get();

        $progressDistribution = [
            '0-25%' => 0,
            '26-50%' => 0,
            '51-75%' => 0,
            '76-99%' => 0,
            '100%' => 0
        ];

        foreach ($enrollments as $enrollment) {
            $progress = $enrollment->completion_percentage ?? 0;
            
            if ($progress == 100) {
                $progressDistribution['100%']++;
            } elseif ($progress >= 76) {
                $progressDistribution['76-99%']++;
            } elseif ($progress >= 51) {
                $progressDistribution['51-75%']++;
            } elseif ($progress >= 26) {
                $progressDistribution['26-50%']++;
            } else {
                $progressDistribution['0-25%']++;
            }
        }

        return [
            'total_students' => $enrollments->count(),
            'progress_distribution' => $progressDistribution,
            'average_progress' => round($enrollments->avg('completion_percentage') ?? 0, 2),
            'last_updated' => now()
        ];
    }

    private function getContentEngagementAnalyticsFromDB(int $courseId): array
    {
        $lessons = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->where('s.course_id', $courseId)
            ->select('l.*')
            ->get();

        $engagementData = [];
        foreach ($lessons as $lesson) {
            $views = DB::table('elearning__tracking_lessons')
                ->where('lesson_id', $lesson->id)
                ->count();

            $completions = DB::table('elearning__tracking_lessons')
                ->where('lesson_id', $lesson->id)
                ->where('progress_percentage', '>=', 90)
                ->count();

            $engagementData[] = [
                'lesson_id' => $lesson->id,
                'lesson_name' => $lesson->name,
                'views' => $views,
                'completions' => $completions,
                'completion_rate' => $views > 0 ? round(($completions / $views) * 100, 2) : 0
            ];
        }

        return [
            'lessons' => $engagementData,
            'total_lessons' => $lessons->count(),
            'last_updated' => now()
        ];
    }

    private function getTimeSpentAnalyticsFromDB(int $userId, string $period, ?int $courseId): array
    {
        $dateFilter = $this->getDateFilter($period);
        
        $query = DB::table('elearning__tracking_lessons as tl')
            ->join('elearning__lessons as l', 'tl.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('tl.user_id', $userId)
            ->where('tl.updated_at', '>=', $dateFilter);

        if ($courseId) {
            $query->where('c.id', $courseId);
        }

        $timeData = $query->select(
            DB::raw('DATE(tl.updated_at) as date'),
            DB::raw('SUM(tl.time_spent) as total_time')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'period' => $period,
            'course_id' => $courseId,
            'time_data' => $timeData,
            'total_time_spent' => $timeData->sum('total_time'),
            'last_updated' => now()
        ];
    }

    private function getCompletionRateAnalyticsFromDB(int $courseId): array
    {
        $enrollments = DB::table('elearning__enrollments')
            ->where('course_id', $courseId)
            ->get();

        $totalStudents = $enrollments->count();
        $completedStudents = $enrollments->where('status', 'completed')->count();
        $completionRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100, 2) : 0;

        // Monthly completion trend
        $monthlyCompletions = DB::table('elearning__enrollments')
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->select(
                DB::raw('YEAR(completed_at) as year'),
                DB::raw('MONTH(completed_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return [
            'total_students' => $totalStudents,
            'completed_students' => $completedStudents,
            'completion_rate' => $completionRate,
            'monthly_trend' => $monthlyCompletions,
            'last_updated' => now()
        ];
    }

    private function getAssessmentAnalyticsFromDB(int $courseId): array
    {
        // This would typically involve quiz/assessment data
        // For now, return placeholder data
        return [
            'total_assessments' => 0,
            'average_score' => 0,
            'pass_rate' => 0,
            'assessment_completions' => 0,
            'last_updated' => now()
        ];
    }

    private function getRealTimeAnalyticsFromDB(int $userId): array
    {
        $activeUsers = DB::table('elearning__tracking_lessons')
            ->where('updated_at', '>=', now()->subMinutes(15))
            ->distinct('user_id')
            ->count('user_id');

        $recentActivity = DB::table('elearning__tracking_lessons as tl')
            ->join('elearning__lessons as l', 'tl.lesson_id', '=', 'l.id')
            ->join('elearning__users as u', 'tl.user_id', '=', 'u.id')
            ->where('tl.updated_at', '>=', now()->subMinutes(30))
            ->select('tl.*', 'l.name as lesson_name', 'u.name as user_name')
            ->orderBy('tl.updated_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'active_users' => $activeUsers,
            'recent_activity' => $recentActivity,
            'last_updated' => now()
        ];
    }

    private function generateReportData(int $userId, string $type, string $period, ?int $courseId): array
    {
        switch ($type) {
            case 'course':
                return $this->getCourseAnalyticsFromDB($courseId ?? 0);
            case 'user':
                return $this->getUserLearningAnalyticsFromDB($userId);
            case 'revenue':
                return $this->getRevenueAnalyticsFromDB($userId, $period, $courseId);
            case 'enrollment':
                return $this->getEnrollmentAnalyticsFromDB($userId, $period, $courseId);
            default:
                return [];
        }
    }

    private function getDateFilter(string $period): string
    {
        return match ($period) {
            'day' => now()->subDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };
    }
}
