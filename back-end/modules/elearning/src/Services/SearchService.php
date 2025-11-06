<?php

namespace Modules\Elearning\Services;

use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;
use Modules\Elearning\Repositories\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    protected $courseRepository;
    protected $userRepository;
    protected $categoryRepository;

    public function __construct(
        CourseRepository $courseRepository,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Search across courses, lessons, and instructors
     */
    public function search(string $query, string $type = 'all', int $perPage = 10): array
    {
        $results = [];

        if ($type === 'all' || $type === 'courses') {
            $results['courses'] = $this->courseRepository->search($query, $perPage);
        }

        if ($type === 'all' || $type === 'instructors') {
            $results['instructors'] = $this->userRepository->searchInstructors($query, $perPage);
        }

        if ($type === 'all' || $type === 'lessons') {
            $results['lessons'] = $this->searchLessons($query, $perPage);
        }

        return $results;
    }

    /**
     * Filter courses with advanced criteria
     */
    public function filterCourses(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->courseRepository->filter($filters, $perPage);
    }

    /**
     * Get popular courses
     */
    public function getPopularCourses(int $limit = 10, ?string $category = null)
    {
        $cacheKey = "popular_courses_{$limit}_{$category}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit, $category) {
            return $this->courseRepository->getPopularCourses($limit, $category);
        });
    }

    /**
     * Get newest courses
     */
    public function getNewestCourses(int $limit = 10, ?string $category = null)
    {
        $cacheKey = "newest_courses_{$limit}_{$category}";
        
        return Cache::remember($cacheKey, 1800, function () use ($limit, $category) {
            return $this->courseRepository->getNewestCourses($limit, $category);
        });
    }

    /**
     * Get free courses
     */
    public function getFreeCourses(int $limit = 10, ?string $category = null)
    {
        $cacheKey = "free_courses_{$limit}_{$category}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit, $category) {
            return $this->courseRepository->getFreeCourses($limit, $category);
        });
    }

    /**
     * Get featured courses
     */
    public function getFeaturedCourses(int $limit = 10, ?string $category = null)
    {
        $cacheKey = "featured_courses_{$limit}_{$category}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit, $category) {
            return $this->courseRepository->getFeaturedCourses($limit, $category);
        });
    }

    /**
     * Get instructors with filters
     */
    public function getInstructors(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getInstructors($filters, $perPage);
    }

    /**
     * Get trending topics
     */
    public function getTrendingTopics(int $limit = 10, string $period = 'week')
    {
        $cacheKey = "trending_topics_{$period}_{$limit}";
        
        return Cache::remember($cacheKey, 1800, function () use ($limit, $period) {
            return $this->getTrendingTopicsFromDB($limit, $period);
        });
    }

    /**
     * Get course recommendations for user
     */
    public function getUserRecommendations(int $userId, int $limit = 10)
    {
        $cacheKey = "user_recommendations_{$userId}_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($userId, $limit) {
            return $this->getRecommendationsFromDB($userId, $limit);
        });
    }

    /**
     * Get search suggestions
     */
    public function getSearchSuggestions(string $query)
    {
        if (strlen($query) < 2) {
            return [];
        }

        $cacheKey = "search_suggestions_" . md5($query);
        
        return Cache::remember($cacheKey, 1800, function () use ($query) {
            return $this->getSuggestionsFromDB($query);
        });
    }

    /**
     * Search lessons
     */
    private function searchLessons(string $query, int $perPage = 10)
    {
        // This would be implemented in LessonRepository
        // For now, return empty result
        return collect([]);
    }

    /**
     * Get trending topics from database
     */
    private function getTrendingTopicsFromDB(int $limit, string $period): array
    {
        $dateFilter = $this->getDateFilter($period);
        
        $topics = DB::table('elearning__courses')
            ->select('category_id', DB::raw('COUNT(*) as course_count'))
            ->where('is_published', true)
            ->where('created_at', '>=', $dateFilter)
            ->groupBy('category_id')
            ->orderBy('course_count', 'desc')
            ->limit($limit)
            ->get();

        $categoryIds = $topics->pluck('category_id')->toArray();
        $categories = $this->categoryRepository->findByIds($categoryIds);

        return $topics->map(function ($topic) use ($categories) {
            $category = $categories->firstWhere('id', $topic->category_id);
            return [
                'category_id' => $topic->category_id,
                'category_name' => $category ? $category->name : 'Unknown',
                'course_count' => $topic->course_count
            ];
        })->toArray();
    }

    /**
     * Get recommendations from database
     */
    private function getRecommendationsFromDB(int $userId, int $limit): array
    {
        // Get user's enrolled categories
        $userCategories = DB::table('elearning__enrollments as e')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->join('elearning__category_course as cc', 'c.id', '=', 'cc.course_id')
            ->where('e.user_id', $userId)
            ->pluck('cc.category_id')
            ->unique()
            ->toArray();

        // Get recommended courses based on user preferences
        $recommendedCourses = DB::table('elearning__courses as c')
            ->join('elearning__category_course as cc', 'c.id', '=', 'cc.course_id')
            ->where('c.is_published', true)
            ->where('c.user_id', '!=', $userId) // Not user's own courses
            ->whereIn('cc.category_id', $userCategories)
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('elearning__enrollments')
                    ->whereColumn('course_id', 'c.id')
                    ->where('user_id', $userId);
            })
            ->select('c.*')
            ->orderBy('c.average_rating', 'desc')
            ->orderBy('c.students_count', 'desc')
            ->limit($limit)
            ->get();

        return $recommendedCourses->toArray();
    }

    /**
     * Get search suggestions from database
     */
    private function getSuggestionsFromDB(string $query): array
    {
        $suggestions = [];

        // Course name suggestions
        $courseSuggestions = DB::table('elearning__courses')
            ->where('is_published', true)
            ->where('name', 'like', "%{$query}%")
            ->select('name', 'id')
            ->limit(5)
            ->get();

        $suggestions['courses'] = $courseSuggestions->toArray();

        // Category suggestions
        $categorySuggestions = DB::table('elearning__categories')
            ->where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->select('name', 'id')
            ->limit(3)
            ->get();

        $suggestions['categories'] = $categorySuggestions->toArray();

        // Instructor suggestions
        $instructorSuggestions = DB::table('elearning__users')
            ->where('is_teacher', true)
            ->where('teacher_status', 'approved')
            ->where('name', 'like', "%{$query}%")
            ->select('name', 'id')
            ->limit(3)
            ->get();

        $suggestions['instructors'] = $instructorSuggestions->toArray();

        return $suggestions;
    }

    /**
     * Get date filter based on period
     */
    private function getDateFilter(string $period): string
    {
        return match ($period) {
            'day' => now()->subDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            default => now()->subWeek(),
        };
    }

    /**
     * Get search analytics
     */
    public function getSearchAnalytics()
    {
        $cacheKey = 'search_analytics';
        
        return Cache::remember($cacheKey, 3600, function () {
            $totalCourses = DB::table('elearning__courses')->where('is_published', true)->count();
            $totalInstructors = DB::table('elearning__users')->where('is_teacher', true)->where('teacher_status', 'approved')->count();
            $totalCategories = DB::table('elearning__categories')->where('is_active', true)->count();
            
            $popularSearches = DB::table('search_logs')
                ->select('query', DB::raw('COUNT(*) as search_count'))
                ->groupBy('query')
                ->orderBy('search_count', 'desc')
                ->limit(10)
                ->get();

            return [
                'total_courses' => $totalCourses,
                'total_instructors' => $totalInstructors,
                'total_categories' => $totalCategories,
                'popular_searches' => $popularSearches
            ];
        });
    }
}
