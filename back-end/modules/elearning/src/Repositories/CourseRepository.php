<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Elearning\Models\Course;
use Newnet\Core\Repositories\BaseRepository;

class CourseRepository extends BaseRepository
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all courses with pagination
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'categories', 'sections'])
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get courses by instructor ID
     */
    public function getByInstructorId(int $instructorId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['categories', 'sections.lessons'])
            ->where('user_id', $instructorId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getBestSellingCourses(int $instructorId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('enrollments')
            ->withCount('enrollments')
            ->where('user_id', $instructorId)
            ->orderByDesc('enrollments_count')
            ->paginate($perPage);
    }

    public function getCoursesSummary(int $instructorId, int $perPage = 10)
    {
        $allCourses = $this->model
            ->with('enrollments')
            ->where('user_id', $instructorId)
            ->get();

        $courseSummary = [
            'published_course' => $allCourses->where('is_published', true)->count(),
            'pending_course'   => $allCourses->where('is_coming_soon', true)->count(),
            'total_course'     => $allCourses->count(),
        ];

        $studentSummary = [
            'total_students'       => $allCourses->sum(fn($course) => $course->enrollments->count()),
            'students_completed'   => $allCourses->sum(fn($course) => $course->enrollments->whereNotNull('completed_at')->count()),
            'students_in_progress' => $allCourses->sum(fn($course) => $course->enrollments
                ->whereNull('completed_at')
                ->where('completion_percentage', '>', 0)
                ->count()),
        ];

        return array_merge($courseSummary, $studentSummary);
    }

    /**
     * Find course by ID
     */
    public function findById(int|string $id): ?Course
    {
        return $this->model
            ->with(['user', 'categories', 'sections.lessons', 'purposes', 'requirements'])
            ->find($id);
    }

    /**
     * Find course by slug
     */
    public function findBySlug(string $slug): ?Course
    {
        return $this->model
            ->with(['user', 'categories', 'sections.lessons', 'purposes', 'requirements'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Create a new course
     */
    public function create(array $data): Course
    {
        $course = $this->model->create($data);

        // Attach categories if provided
        if (isset($data['categories'])) {
            $course->categories()->attach($data['categories']);
        }

        // Create purposes if provided
        if (isset($data['purposes'])) {
            $course->purposes()->createMany($data['purposes']);
        }

        // Create requirements if provided
        if (isset($data['requirements'])) {
            $course->requirements()->createMany($data['requirements']);
        }

        return $course->load(['categories', 'purposes', 'requirements']);
    }

    /**
     * Update course
     */
    public function updateCourse(int $id, array $data): bool
    {
        $course = $this->model->find($id);

        if (!$course) {
            return false;
        }

        $course->update($data);

        // Update categories if provided
        if (isset($data['categories'])) {
            $course->categories()->sync($data['categories']);
        }

        // Update purposes if provided
        if (isset($data['purposes'])) {
            $course->purposes()->delete();
            $course->purposes()->createMany($data['purposes']);
        }

        // Update requirements if provided
        if (isset($data['requirements'])) {
            $course->requirements()->delete();
            $course->requirements()->createMany($data['requirements']);
        }

        return true;
    }

    /**
     * Delete course
     */
    public function delete($id): bool
    {
        $course = $this->model->find($id);

        if (!$course) {
            return false;
        }

        // Delete related data
        $course->categories()->detach();
        $course->purposes()->delete();
        $course->requirements()->delete();

        return $course->delete();
    }

    /**
     * Get popular courses
     */
    public function getPopularCourses(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->with(['user', 'categories'])
            ->where('is_published', true)
            ->orderBy('students_count', 'desc')
            ->orderBy('average_rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get newest courses
     */
    public function getNewestCourses(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->with(['user', 'categories'])
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get free courses
     */
    public function getFreeCourses(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->with(['user', 'categories'])
            ->where('is_published', true)
            ->where('sale_price', 0)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get featured courses
     */
    public function getFeaturedCourses(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->with(['user', 'categories'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search courses
     */
    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'categories'])
            ->where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('summary', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter courses
     */
    public function filter(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['user', 'categories'])
            ->where('is_published', true);

        // Category filter
        if (isset($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('elearning__categories.id', $filters['category_id']);
            });
        }

        // Price filter
        if (isset($filters['price_range'])) {
            switch ($filters['price_range']) {
                case 'free':
                    $query->where('sale_price', 0);
                    break;
                case 'paid':
                    $query->where('sale_price', '>', 0);
                    break;
                case 'under_50':
                    $query->where('sale_price', '<=', 50);
                    break;
                case 'under_100':
                    $query->where('sale_price', '<=', 100);
                    break;
            }
        }

        // Level filter
        if (isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        // Rating filter
        if (isset($filters['min_rating'])) {
            $query->where('average_rating', '>=', $filters['min_rating']);
        }

        // Duration filter
        if (isset($filters['max_duration'])) {
            $query->where('total_hour', '<=', $filters['max_duration']);
        }

        // Sort by
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }
}
