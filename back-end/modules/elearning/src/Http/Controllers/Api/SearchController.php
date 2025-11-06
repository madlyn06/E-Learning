<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\SearchService;

class SearchController extends BaseController
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Search courses, lessons, and instructors
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', 'all'); // courses, lessons, instructors, all
            $perPage = $request->get('per_page', 10);
            
            if (empty($query)) {
                return $this->errorResponse('Search query is required', 400);
            }

            $results = $this->searchService->search($query, $type, $perPage);
            return $this->successResponse($results, 'Search results retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to perform search');
        }
    }

    /**
     * Filter courses with advanced criteria
     */
    public function filterCourses(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 10);
            
            $results = $this->searchService->filterCourses($filters, $perPage);
            return $this->paginatedResponse($results, 'Filtered courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to filter courses');
        }
    }

    /**
     * Get popular courses
     */
    public function getPopularCourses(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $category = $request->get('category');
            
            $courses = $this->searchService->getPopularCourses($limit, $category);
            return $this->successResponse($courses, 'Popular courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve popular courses');
        }
    }

    /**
     * Get newest courses
     */
    public function getNewestCourses(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $category = $request->get('category');
            
            $courses = $this->searchService->getNewestCourses($limit, $category);
            return $this->successResponse($courses, 'Newest courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve newest courses');
        }
    }

    /**
     * Get free courses
     */
    public function getFreeCourses(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $category = $request->get('category');
            
            $courses = $this->searchService->getFreeCourses($limit, $category);
            return $this->successResponse($courses, 'Free courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve free courses');
        }
    }

    /**
     * Get featured courses
     */
    public function getFeaturedCourses(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $category = $request->get('category');
            
            $courses = $this->searchService->getFeaturedCourses($limit, $category);
            return $this->successResponse($courses, 'Featured courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve featured courses');
        }
    }

    /**
     * Get instructors with filters
     */
    public function getInstructors(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 10);
            
            $instructors = $this->searchService->getInstructors($filters, $perPage);
            return $this->paginatedResponse($instructors, 'Instructors retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve instructors');
        }
    }

    /**
     * Get trending topics
     */
    public function getTrendingTopics(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $period = $request->get('period', 'week'); // day, week, month
            
            $topics = $this->searchService->getTrendingTopics($limit, $period);
            return $this->successResponse($topics, 'Trending topics retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve trending topics');
        }
    }

    /**
     * Get course recommendations for user
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $userId = auth('sanctum')->id();
            
            $recommendations = $this->searchService->getUserRecommendations($userId, $limit);
            return $this->successResponse($recommendations, 'Course recommendations retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve recommendations');
        }
    }

    /**
     * Get search suggestions
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            
            if (empty($query)) {
                return $this->successResponse([], 'No suggestions available');
            }

            $suggestions = $this->searchService->getSearchSuggestions($query);
            return $this->successResponse($suggestions, 'Search suggestions retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve search suggestions');
        }
    }
}
