<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\DiscussionService;

class DiscussionController extends BaseController
{
    protected $discussionService;

    public function __construct(DiscussionService $discussionService)
    {
        $this->discussionService = $discussionService;
    }

    /**
     * Get course discussions
     */
    public function getCourseDiscussions(int $courseId, Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 20);
            $sortBy = $request->get('sort_by', 'latest'); // latest, popular, unanswered
            
            $discussions = $this->discussionService->getCourseDiscussions($courseId, $sortBy, $perPage);
            return $this->paginatedResponse($discussions, 'Course discussions retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get discussion details
     */
    public function getDiscussion(int $discussionId): JsonResponse
    {
        try {
            $discussion = $this->discussionService->getDiscussion($discussionId);
            return $this->successResponse($discussion, 'Discussion retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Create new discussion
     */
    public function createDiscussion(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'course_id' => 'required|integer|exists:elearning__courses,id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'nullable|string',
                'tags' => 'nullable|array',
                'is_anonymous' => 'boolean'
            ]);

            $discussion = $this->discussionService->createDiscussion(auth('sanctum')->id(), $data);
            return $this->createdResponse($discussion, 'Discussion created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update discussion
     */
    public function updateDiscussion(int $discussionId, Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'category' => 'nullable|string',
                'tags' => 'nullable|array'
            ]);

            $discussion = $this->discussionService->updateDiscussion($discussionId, auth('sanctum')->id(), $data);
            return $this->updatedResponse($discussion, 'Discussion updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Delete discussion
     */
    public function deleteDiscussion(int $discussionId): JsonResponse
    {
        try {
            $this->discussionService->deleteDiscussion($discussionId, auth('sanctum')->id());
            return $this->deletedResponse('Discussion deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get discussion replies
     */
    public function getReplies(int $discussionId, Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 20);
            $sortBy = $request->get('sort_by', 'oldest'); // oldest, newest, best
            
            $replies = $this->discussionService->getDiscussionReplies($discussionId, $sortBy, $perPage);
            return $this->paginatedResponse($replies, 'Discussion replies retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Add reply to discussion
     */
    public function addReply(int $discussionId, Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'content' => 'required|string',
                'parent_id' => 'nullable|integer|exists:elearning__discussion_replies,id',
                'is_anonymous' => 'boolean'
            ]);

            $reply = $this->discussionService->addReply($discussionId, auth('sanctum')->id(), $data);
            return $this->createdResponse($reply, 'Reply added successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update reply
     */
    public function updateReply(int $replyId, Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'content' => 'required|string'
            ]);

            $reply = $this->discussionService->updateReply($replyId, auth('sanctum')->id(), $data);
            return $this->updatedResponse($reply, 'Reply updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Delete reply
     */
    public function deleteReply(int $replyId): JsonResponse
    {
        try {
            $this->discussionService->deleteReply($replyId, auth('sanctum')->id());
            return $this->deletedResponse('Reply deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Like discussion
     */
    public function likeDiscussion(int $discussionId): JsonResponse
    {
        try {
            $this->discussionService->likeDiscussion($discussionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Discussion liked successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Unlike discussion
     */
    public function unlikeDiscussion(int $discussionId): JsonResponse
    {
        try {
            $this->discussionService->unlikeDiscussion($discussionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Discussion unliked successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Mark discussion as solved
     */
    public function markAsSolved(int $discussionId): JsonResponse
    {
        try {
            $this->discussionService->markAsSolved($discussionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Discussion marked as solved');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get discussion categories
     */
    public function getCategories(int $courseId): JsonResponse
    {
        try {
            $categories = $this->discussionService->getDiscussionCategories($courseId);
            return $this->successResponse($categories, 'Discussion categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Search discussions
     */
    public function searchDiscussions(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $courseId = $request->get('course_id');
            $perPage = $request->get('per_page', 20);
            
            if (empty($query)) {
                return $this->errorResponse('Search query is required', 400);
            }

            $results = $this->discussionService->searchDiscussions($query, $courseId, $perPage);
            return $this->paginatedResponse($results, 'Search results retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to search discussions');
        }
    }

    /**
     * Get user's discussions
     */
    public function getUserDiscussions(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 20);
            $status = $request->get('status', 'all'); // all, active, solved, closed
            
            $discussions = $this->discussionService->getUserDiscussions(auth('sanctum')->id(), $status, $perPage);
            return $this->paginatedResponse($discussions, 'User discussions retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve user discussions');
        }
    }
}
