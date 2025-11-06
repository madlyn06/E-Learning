<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Elearning\Services\CourseService;
use Modules\Elearning\Http\Resources\CourseResource;

class CourseController extends BaseController
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getByUserId(): JsonResponse
    {
        try {
            $courses = $this->courseService->getInstructorCourses(auth('sanctum')->id());
            return $this->paginatedResponse(CourseResource::collection($courses), 'Instructor courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve instructor courses');
        }
    }

    public function getDashboardCourses(): JsonResponse
    {
        try {
            $courses = $this->courseService->getDashboardCourses(auth('sanctum')->id());
            return $this->successResponse($courses, 'Dashboard courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve dashboard courses');
        }
    }


    public function getBestSellingCourses(): JsonResponse
    {
        try {
            $courses = $this->courseService->getBestSellingCourses(auth('sanctum')->id());
            return $this->paginatedResponse(CourseResource::collection($courses), 'Best selling courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve best selling courses');
        }
    }

    public function index(): JsonResponse
    {
        try {
            $courses = $this->courseService->getAllCourses();
            return $this->paginatedResponse($courses, 'Courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve courses');
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $course = $this->courseService->getCourseById($id);

            if (!$course) {
                return $this->notFoundResponse('Course not found');
            }

            return $this->successResponse(new CourseResource($course), 'Course retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve course');
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $course = $this->courseService->createCourse($request->all());
            return $this->createdResponse(new CourseResource($course), 'Course created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $course = $this->courseService->getCourseById($id);

            if ($course->user_id !== auth('sanctum')->user()->id) {
                return $this->errorResponse('You are not allowed to update this course', 403);
            }

            $success = $this->courseService->updateCourse($id, $request->all());

            if (!$success) {
                return $this->notFoundResponse('Course not found');
            }

            return $this->updatedResponse(new CourseResource($course), 'Course updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $success = $this->courseService->deleteCourse($id);

            if (!$success) {
                return $this->notFoundResponse('Course not found');
            }

            return $this->deletedResponse('Course deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function popular(): JsonResponse
    {
        try {
            $courses = $this->courseService->getPopularCourses();
            return $this->successResponse($courses, 'Popular courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve popular courses');
        }
    }

    public function newest(): JsonResponse
    {
        try {
            $courses = $this->courseService->getNewestCourses();
            return $this->successResponse($courses, 'Newest courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve newest courses');
        }
    }

    public function free(): JsonResponse
    {
        try {
            $courses = $this->courseService->getFreeCourses();
            return $this->successResponse($courses, 'Free courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve free courses');
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $perPage = $request->get('per_page', 10);

            $courses = $this->courseService->searchCourses($query, $perPage);
            return $this->paginatedResponse($courses, 'Search results retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to search courses');
        }
    }

    public function filter(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 10);

            $courses = $this->courseService->filterCourses($filters, $perPage);
            return $this->paginatedResponse($courses, 'Filtered courses retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to filter courses');
        }
    }

    public function publish($id): JsonResponse
    {
        try {
            $this->courseService->publishCourse($id);
            return $this->successResponse(null, 'Course published successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function unpublish($id): JsonResponse
    {
        try {
            $this->courseService->unpublishCourse($id);
            return $this->successResponse(null, 'Course unpublished successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function statistics($id): JsonResponse
    {
        try {
            $statistics = $this->courseService->getCourseStatistics($id);
            return $this->successResponse($statistics, 'Course statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
