<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Elearning\Http\Requests\Review\StoreReviewRequest;
use Modules\Elearning\Http\Resources\ReviewResource;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Review;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews for a course
     */
    public function index($courseId): JsonResponse
    {
        $reviews = Review::where('course_id', $courseId)
            ->where('is_approved', true)
            ->paginate(10);
            
        return response()->json([
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    /**
     * Store a newly created review in storage
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $courseId = $request->input('course_id');
        
        // Check if user is enrolled in the course
        $isEnrolled = Course::find($courseId)->enrollments()->where('user_id', $userId)->exists();
        if (!$isEnrolled) {
            return response()->json([
                'message' => 'You must be enrolled in this course to leave a review'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Check if user already reviewed this course
        $existingReview = Review::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
            
        if ($existingReview) {
            // Update existing review
            $existingReview->update($request->validated());
            return response()->json([
                'message' => 'Review updated successfully',
                'data' => new ReviewResource($existingReview->fresh())
            ]);
        }
        
        // Create new review
        $review = Review::create(array_merge(
            $request->validated(),
            ['user_id' => $userId]
        ));
        
        // Update course rating
        $this->updateCourseAverageRating($courseId);
        
        return response()->json([
            'message' => 'Review created successfully',
            'data' => new ReviewResource($review)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified review
     */
    public function show($id): JsonResponse
    {
        $review = Review::findOrFail($id);
        return response()->json(new ReviewResource($review));
    }

    /**
     * Update the specified review in storage
     */
    public function update(StoreReviewRequest $request, $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'message' => 'You are not authorized to update this review'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $review->update($request->validated());
        
        // Update course rating
        $this->updateCourseAverageRating($review->course_id);
        
        return response()->json([
            'message' => 'Review updated successfully',
            'data' => new ReviewResource($review)
        ]);
    }

    /**
     * Remove the specified review from storage
     */
    public function destroy($id): JsonResponse
    {
        $review = Review::findOrFail($id);
        
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this review'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $courseId = $review->course_id;
        $review->delete();
        
        // Update course rating
        $this->updateCourseAverageRating($courseId);
        
        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }
    
    /**
     * Update the average rating for a course
     */
    private function updateCourseAverageRating($courseId): void
    {
        $averageRating = Review::where('course_id', $courseId)
            ->where('is_approved', true)
            ->avg('rating');
            
        $course = Course::find($courseId);
        $course->average_rating = $averageRating ?? 0;
        $course->save();
    }
}
