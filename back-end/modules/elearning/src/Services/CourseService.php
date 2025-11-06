<?php

namespace Modules\Elearning\Services;

use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get all courses with pagination
     */
    public function getAllCourses(int $perPage = 10): LengthAwarePaginator
    {
        return $this->courseRepository->getAllPaginated($perPage);
    }

    /**
     * Get courses by instructor ID
     */
    public function getInstructorCourses(int $instructorId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->courseRepository->getByInstructorId($instructorId, $perPage);
    }

    public function getBestSellingCourses(int $instructorId, int $perPage = 10)
    {
        return $this->courseRepository->getBestSellingCourses($instructorId, $perPage);
    }

    public function getDashboardCourses(int $instructorId, int $perPage = 10)
    {
        return $this->courseRepository->getCoursesSummary($instructorId, $perPage);
    }

    /**
     * Get course by ID
     */
    public function getCourseById(int|string $id): ?Course
    {
        return $this->courseRepository->findById($id);
    }

    /**
     * Get course by slug
     */
    public function getCourseBySlug(string $slug): ?Course
    {
        return $this->courseRepository->findBySlug($slug);
    }

    /**
     * Create a new course
     */
    public function createCourse(array $data): Course
    {
        // Validate instructor permissions
        $this->validateInstructorPermissions();

        // Prepare course data
        $courseData = $this->prepareCourseData($data);

        // Create the course
        $course = $this->courseRepository->create($courseData);

        // Handle course image upload if provided
        if (isset($data['image'])) {
            $this->handleCourseImageUpload($course, $data['image']);
        }

        return $course;
    }

    /**
     * Update course
     */
    public function updateCourse(int|string $id, array $data): bool
    {
        // Validate instructor permissions
        $this->validateInstructorPermissions();

        // Check if instructor owns the course
        $course = $this->courseRepository->findById($id);
        if (!$course || $course->user_id !== Auth::id()) {
            throw new \Exception('You can only update your own courses');
        }

        // Prepare course data
        $courseData = $this->prepareCourseData($data);

        // Handle course image upload if provided
        if (isset($data['image'])) {
            $this->handleCourseImageUpload($course, $data['image']);
        }

        return $this->courseRepository->updateCourse($id, $courseData);
    }

    /**
     * Delete course
     */
    public function deleteCourse(int|string $id): bool
    {
        // Validate instructor permissions
        $this->validateInstructorPermissions();

        // Check if instructor owns the course
        $course = $this->courseRepository->findById($id);
        if (!$course || $course->user_id !== Auth::id()) {
            throw new \Exception('You can only delete your own courses');
        }

        // Delete course image if exists
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        return $this->courseRepository->delete($id);
    }

    /**
     * Get popular courses
     */
    public function getPopularCourses(int $limit = 10)
    {
        return $this->courseRepository->getPopularCourses($limit);
    }

    /**
     * Get newest courses
     */
    public function getNewestCourses(int $limit = 10)
    {
        return $this->courseRepository->getNewestCourses($limit);
    }

    /**
     * Get free courses
     */
    public function getFreeCourses(int $limit = 10)
    {
        return $this->courseRepository->getFreeCourses($limit);
    }

    /**
     * Search courses
     */
    public function searchCourses(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->courseRepository->search($query, $perPage);
    }

    /**
     * Filter courses
     */
    public function filterCourses(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->courseRepository->filter($filters, $perPage);
    }

    /**
     * Publish course
     */
    public function publishCourse(int $id): bool
    {
        $course = $this->courseRepository->findById($id);
        if (!$course || $course->user_id !== Auth::id()) {
            throw new \Exception('You can only publish your own courses');
        }

        // Check if course has required content
        if (!$this->validateCourseForPublishing($course)) {
            throw new \Exception('Course must have at least one section with lessons to be published');
        }

        return $this->courseRepository->updateCourse($id, ['is_published' => true]);
    }

    /**
     * Unpublish course
     */
    public function unpublishCourse(int $id): bool
    {
        $course = $this->courseRepository->findById($id);
        if (!$course || $course->user_id !== Auth::id()) {
            throw new \Exception('You can only unpublish your own courses');
        }

        return $this->courseRepository->updateCourse($id, ['is_published' => false]);
    }

    /**
     * Get course statistics
     */
    public function getCourseStatistics(int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);
        if (!$course) {
            throw new \Exception('Course not found');
        }

        $totalLessons = $course->sections->sum(function ($section) {
            return $section->lessons->count();
        });

        $totalDuration = $course->sections->sum(function ($section) {
            return $section->lessons->sum('duration');
        });

        return [
            'total_sections' => $course->sections->count(),
            'total_lessons' => $totalLessons,
            'total_duration' => $totalDuration,
            'students_count' => $course->enrollments->count(),
            'average_rating' => $course->average_rating,
            'reviews_count' => $course->reviews->count(),
            'completion_rate' => $this->calculateCompletionRate($courseId)
        ];
    }

    /**
     * Validate instructor permissions
     */
    private function validateInstructorPermissions(): void
    {
        $user = Auth::user();
        if (!$user->is_teacher || $user->teacher_status !== 'approved') {
            throw new \Exception('Only approved teachers can manage courses');
        }
    }

    /**
     * Prepare course data for creation/update
     */
    private function prepareCourseData(array $data): array
    {
        $courseData = [
            'name' => $data['name'],
            'summary' => $data['summary'] ?? null,
            'content' => $data['content'] ?? null,
            'price' => $data['price'] ?? 0,
            'sale_price' => $data['sale_price'] ?? null,
            'level' => $data['level'] ?? 'beginner',
            'is_enable' =>  $data['is_enable'] ?? true,
        ];

        // Generate slug if not provided
        if (!isset($data['slug'])) {
            $courseData['slug'] = Str::slug($data['name']);
        } else {
            $courseData['slug'] = $data['slug'];
        }

        // Set instructor ID
        $courseData['user_id'] = Auth::id();

        // Add field relationships
        if (isset($data['categories'])) {
            $courseData['categories'] = $data['categories'];
        }

        if (isset($data['purposes'])) {
            $courseData['purposes'] = $data['purposes'];
        }

        if (isset($data['requirements'])) {
            $courseData['requirements'] = $data['requirements'];
        }

        return $courseData;
    }

    /**
     * Handle course image upload
     */
    private function handleCourseImageUpload(Course $course, $image): void
    {
        if ($image && $image->isValid()) {
            // Delete old image if exists
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }

            // Store new image
            $path = $image->store('courses/images', 'public');
            $course->update(['image' => $path]);
        }
    }

    /**
     * Validate course for publishing
     */
    private function validateCourseForPublishing(Course $course): bool
    {
        if ($course->sections->isEmpty()) {
            return false;
        }

        foreach ($course->sections as $section) {
            if ($section->lessons->isEmpty()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate course completion rate
     */
    private function calculateCompletionRate(int $courseId): float
    {
        // This would typically involve enrollment and progress tracking
        // For now, return a placeholder value
        return 0.0;
    }
}
