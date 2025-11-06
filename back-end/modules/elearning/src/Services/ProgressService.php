<?php

namespace Modules\Elearning\Services;

use Modules\Elearning\Repositories\ProgressRepository;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Lesson;
use Modules\Elearning\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProgressService
{
    protected $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    /**
     * Get course progress for authenticated user
     */
    public function getCourseProgress(int $courseId): array
    {
        $user = Auth::user();
        $enrollment = $this->progressRepository->getCourseProgress($user->id, $courseId);

        if (!$enrollment) {
            throw new \Exception('You are not enrolled in this course');
        }

        $course = Course::with(['sections.lessons'])->find($courseId);
        $totalLessons = $course->sections->sum(function ($section) {
            return $section->lessons->count();
        });

        $completedLessons = $this->progressRepository->getCompletedLessons($user->id, $courseId)->count();
        $progressPercentage = $this->progressRepository->getCourseCompletionPercentage($user->id, $courseId);

        // Update enrollment progress
        $this->progressRepository->updateCourseProgress($user->id, $courseId);

        return [
            'course_id' => $courseId,
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $progressPercentage,
            'status' => $enrollment->status,
            'enrolled_at' => $enrollment->enrolled_at,
            'last_activity' => $enrollment->updated_at
        ];
    }

    /**
     * Mark lesson as complete
     */
    public function markLessonComplete(int $lessonId): bool
    {
        $user = Auth::user();
        $lesson = Lesson::with('section.course')->find($lessonId);

        if (!$lesson) {
            throw new \Exception('Lesson not found');
        }

        // Check if user is enrolled in the course
        $enrollment = $this->progressRepository->getCourseProgress($user->id, $lesson->section->course_id);
        if (!$enrollment) {
            throw new \Exception('You are not enrolled in this course');
        }

        // Mark lesson as complete
        $this->progressRepository->markLessonComplete($user->id, $lesson->section->course_id, $lessonId);

        // Update course progress
        $this->progressRepository->updateCourseProgress($user->id, $lesson->section->course_id);

        return true;
    }

    /**
     * Track lesson progress (for video lessons)
     */
    public function trackLessonProgress(int $lessonId, array $progressData): bool
    {
        $user = Auth::user();
        $lesson = Lesson::with('section.course')->find($lessonId);

        if (!$lesson) {
            throw new \Exception('Lesson not found');
        }

        // Check if user is enrolled in the course
        $enrollment = $this->progressRepository->getCourseProgress($user->id, $lesson->section->course_id);
        if (!$enrollment) {
            throw new \Exception('You are not enrolled in this course');
        }

        // Validate progress data
        $this->validateProgressData($progressData);

        // Update or create tracking record
        $this->progressRepository->trackLessonProgress($user->id, $lesson->section->course_id, $lessonId, $progressData);

        // If progress is 90% or more, consider lesson complete
        if ($progressData['progress_percentage'] >= 90) {
            $this->progressRepository->updateCourseProgress($user->id, $lesson->section->course_id);
        }

        return true;
    }

    /**
     * Get progress overview for authenticated user
     */
    public function getProgressOverview(): array
    {
        $user = Auth::user();
        return $this->progressRepository->getUserProgressOverview($user->id);
    }

    /**
     * Get course completion certificate
     */
    public function getCertificate(int $courseId): array
    {
        $user = Auth::user();
        $enrollment = $this->progressRepository->getCourseProgress($user->id, $courseId);

        if (!$enrollment || $enrollment->status !== 'completed') {
            throw new \Exception('Course not completed or not enrolled');
        }

        $course = Course::with('user')->find($courseId);
        
        // Generate certificate data
        return [
            'certificate_id' => 'CERT-' . strtoupper(uniqid()),
            'student_name' => $user->name,
            'course_name' => $course->name,
            'instructor_name' => $course->user->name,
            'completion_date' => $enrollment->completed_at ?? now(),
            'course_duration' => $course->total_hour . ' hours',
            'issued_date' => now(),
            'status' => 'valid'
        ];
    }

    /**
     * Get course statistics for instructors
     */
    public function getCourseStatistics(int $courseId): array
    {
        $user = Auth::user();
        $course = Course::find($courseId);

        if (!$course || $course->user_id !== $user->id) {
            throw new \Exception('You can only view statistics for your own courses');
        }

        return $this->progressRepository->getCourseStatistics($courseId);
    }

    /**
     * Get user's learning path
     */
    public function getLearningPath(int $courseId): array
    {
        $user = Auth::user();
        $enrollment = $this->progressRepository->getCourseProgress($user->id, $courseId);

        if (!$enrollment) {
            throw new \Exception('You are not enrolled in this course');
        }

        $course = Course::with(['sections.lessons'])->find($courseId);
        $learningPath = [];

        foreach ($course->sections as $section) {
            $sectionData = [
                'id' => $section->id,
                'name' => $section->name,
                'description' => $section->description,
                'lessons' => []
            ];

            foreach ($section->lessons as $lesson) {
                $isCompleted = $this->progressRepository->isLessonCompleted($user->id, $lesson->id);
                $progress = $this->progressRepository->getLessonProgress($user->id, $lesson->id);

                $sectionData['lessons'][] = [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'title' => $lesson->title,
                    'type' => $lesson->type,
                    'is_free' => $lesson->is_free,
                    'is_completed' => $isCompleted,
                    'progress_percentage' => $progress ? $progress->progress_percentage : 0,
                    'duration' => $lesson->duration ?? 0
                ];
            }

            $learningPath[] = $sectionData;
        }

        return $learningPath;
    }

    /**
     * Validate progress data
     */
    private function validateProgressData(array $progressData): void
    {
        if (!isset($progressData['progress_percentage']) || 
            !is_numeric($progressData['progress_percentage']) ||
            $progressData['progress_percentage'] < 0 || 
            $progressData['progress_percentage'] > 100) {
            throw new \Exception('Invalid progress percentage');
        }

        if (!isset($progressData['current_time']) || 
            !is_numeric($progressData['current_time']) ||
            $progressData['current_time'] < 0) {
            throw new \Exception('Invalid current time');
        }

        if (!isset($progressData['total_time']) || 
            !is_numeric($progressData['total_time']) ||
            $progressData['total_time'] <= 0) {
            throw new \Exception('Invalid total time');
        }

        if ($progressData['current_time'] > $progressData['total_time']) {
            throw new \Exception('Current time cannot be greater than total time');
        }
    }

    /**
     * Calculate estimated time to completion
     */
    public function getEstimatedTimeToCompletion(int $courseId): array
    {
        $user = Auth::user();
        $enrollment = $this->progressRepository->getCourseProgress($user->id, $courseId);

        if (!$enrollment) {
            throw new \Exception('You are not enrolled in this course');
        }

        $course = Course::with(['sections.lessons'])->find($courseId);
        $totalLessons = $course->sections->sum(function ($section) {
            return $section->lessons->count();
        });

        $completedLessons = $this->progressRepository->getCompletedLessons($user->id, $courseId)->count();
        $remainingLessons = $totalLessons - $completedLessons;

        // Calculate average time per lesson (assuming 30 minutes per lesson)
        $averageTimePerLesson = 30; // minutes
        $estimatedMinutes = $remainingLessons * $averageTimePerLesson;

        $hours = floor($estimatedMinutes / 60);
        $minutes = $estimatedMinutes % 60;

        return [
            'remaining_lessons' => $remainingLessons,
            'estimated_hours' => $hours,
            'estimated_minutes' => $minutes,
            'estimated_total_minutes' => $estimatedMinutes
        ];
    }
}
