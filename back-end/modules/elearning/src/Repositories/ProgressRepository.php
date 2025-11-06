<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Elearning\Models\Enrollment;
use Modules\Elearning\Models\TrackingLesson;
use Modules\Elearning\Models\Course;
use Newnet\Core\Repositories\BaseRepository;

class ProgressRepository extends BaseRepository
{
    protected $enrollment;
    protected $trackingLesson;
    protected $course;

    public function __construct(Enrollment $enrollment, TrackingLesson $trackingLesson, Course $course)
    {
        $this->enrollment = $enrollment;
        $this->trackingLesson = $trackingLesson;
        $this->course = $course;
    }

    /**
     * Get course progress for user
     */
    public function getCourseProgress(int $userId, int $courseId): ?Enrollment
    {
        return $this->enrollment
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    /**
     * Get user's progress overview
     */
    public function getUserProgressOverview(int $userId): array
    {
        $enrollments = $this->enrollment
            ->where('user_id', $userId)
            ->with(['course.sections.lessons'])
            ->get();

        $totalCourses = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        $activeCourses = $enrollments->where('status', 'active')->count();
        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($enrollments as $enrollment) {
            $courseLessons = $enrollment->course->sections->sum(function ($section) {
                return $section->lessons->count();
            });
            $totalLessons += $courseLessons;
            
            $completedLessons += $this->trackingLesson
                ->where('user_id', $userId)
                ->where('course_id', $enrollment->course_id)
                ->count();
        }

        $overallProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return [
            'total_courses' => $totalCourses,
            'completed_courses' => $completedCourses,
            'active_courses' => $activeCourses,
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'overall_progress' => $overallProgress,
            'enrollments' => $enrollments->map(function ($enrollment) {
                return [
                    'course_id' => $enrollment->course_id,
                    'course_name' => $enrollment->course->name,
                    'progress_percentage' => $enrollment->completion_percentage,
                    'status' => $enrollment->status,
                    'enrolled_at' => $enrollment->enrolled_at
                ];
            })
        ];
    }

    /**
     * Mark lesson as complete
     */
    public function markLessonComplete(int $userId, int $courseId, int $lessonId): bool
    {
        return $this->trackingLesson->updateOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $courseId,
                'lesson_id' => $lessonId
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]
        )->exists;
    }

    /**
     * Track lesson progress
     */
    public function trackLessonProgress(int $userId, int $courseId, int $lessonId, array $progressData): bool
    {
        return $this->trackingLesson->updateOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $courseId,
                'lesson_id' => $lessonId
            ],
            [
                'progress_percentage' => $progressData['progress_percentage'],
                'current_time' => $progressData['current_time'],
                'total_time' => $progressData['total_time'],
                'updated_at' => now()
            ]
        )->exists;
    }

    /**
     * Get completed lessons for a course
     */
    public function getCompletedLessons(int $userId, int $courseId): Collection
    {
        return $this->trackingLesson
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->with('lesson')
            ->get();
    }

    /**
     * Update course progress
     */
    public function updateCourseProgress(int $userId, int $courseId): bool
    {
        $course = $this->course->with(['sections.lessons'])->find($courseId);
        if (!$course) {
            return false;
        }

        $totalLessons = $course->sections->sum(function ($section) {
            return $section->lessons->count();
        });

        $completedLessons = $this->trackingLesson
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->count();

        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return $this->enrollment
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update([
                'completion_percentage' => $progressPercentage,
                'status' => $progressPercentage >= 100 ? 'completed' : 'active',
                'completed_at' => $progressPercentage >= 100 ? now() : null
            ]) > 0;
    }

    /**
     * Get course completion percentage
     */
    public function getCourseCompletionPercentage(int $userId, int $courseId): float
    {
        $course = $this->course->with(['sections.lessons'])->find($courseId);
        if (!$course) {
            return 0.0;
        }

        $totalLessons = $course->sections->sum(function ($section) {
            return $section->lessons->count();
        });

        if ($totalLessons === 0) {
            return 0.0;
        }

        $completedLessons = $this->trackingLesson
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * Get user's enrolled courses with progress
     */
    public function getUserEnrolledCourses(int $userId): Collection
    {
        return $this->enrollment
            ->where('user_id', $userId)
            ->with(['course.sections.lessons'])
            ->get();
    }

    /**
     * Check if lesson is completed
     */
    public function isLessonCompleted(int $userId, int $lessonId): bool
    {
        return $this->trackingLesson
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->exists();
    }

    /**
     * Get lesson progress
     */
    public function getLessonProgress(int $userId, int $lessonId): ?TrackingLesson
    {
        return $this->trackingLesson
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();
    }

    /**
     * Get course statistics
     */
    public function getCourseStatistics(int $courseId): array
    {
        $totalEnrollments = $this->enrollment->where('course_id', $courseId)->count();
        $completedEnrollments = $this->enrollment
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->count();

        $averageProgress = $this->enrollment
            ->where('course_id', $courseId)
            ->avg('completion_percentage') ?? 0;

        return [
            'total_enrollments' => $totalEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'average_progress' => round($averageProgress, 2),
            'completion_rate' => $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0
        ];
    }
}
