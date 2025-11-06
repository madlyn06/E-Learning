<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;

class DiscussionService
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
     * Get course discussions
     */
    public function getCourseDiscussions(int $courseId, string $sortBy = 'latest', int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('elearning__discussions as d')
            ->join('elearning__users as u', 'd.user_id', '=', 'u.id')
            ->where('d.course_id', $courseId)
            ->where('d.is_active', true)
            ->select('d.*', 'u.name as user_name', 'u.avatar as user_avatar');

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('d.likes_count', 'desc');
                break;
            case 'unanswered':
                $query->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('elearning__discussion_replies')
                        ->whereColumn('discussion_id', 'd.id');
                });
                break;
            case 'latest':
            default:
                $query->orderBy('d.created_at', 'desc');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Get discussion details
     */
    public function getDiscussion(int $discussionId): array
    {
        $discussion = DB::table('elearning__discussions as d')
            ->join('elearning__users as u', 'd.user_id', '=', 'u.id')
            ->join('elearning__courses as c', 'd.course_id', '=', 'c.id')
            ->where('d.id', $discussionId)
            ->where('d.is_active', true)
            ->select('d.*', 'u.name as user_name', 'u.avatar as user_avatar', 'c.name as course_name')
            ->first();

        if (!$discussion) {
            throw new \Exception('Discussion not found');
        }

        // Get replies count
        $repliesCount = DB::table('elearning__discussion_replies')
            ->where('discussion_id', $discussionId)
            ->count();

        // Get tags if they exist
        $tags = DB::table('elearning__discussion_tags as dt')
            ->join('elearning__tags as t', 'dt.tag_id', '=', 't.id')
            ->where('dt.discussion_id', $discussionId)
            ->pluck('t.name')
            ->toArray();

        return [
            'discussion' => $discussion,
            'replies_count' => $repliesCount,
            'tags' => $tags
        ];
    }

    /**
     * Create new discussion
     */
    public function createDiscussion(int $userId, array $data): array
    {
        // Check if user is enrolled in the course
        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $data['course_id'])
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to create discussions');
        }

        $discussionData = [
            'course_id' => $data['course_id'],
            'user_id' => $userId,
            'title' => $data['title'],
            'content' => $data['content'],
            'category' => $data['category'] ?? 'general',
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'is_active' => true,
            'likes_count' => 0,
            'views_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $discussionId = DB::table('elearning__discussions')->insertGetId($discussionData);

        // Add tags if provided
        if (!empty($data['tags'])) {
            $this->addTagsToDiscussion($discussionId, $data['tags']);
        }

        return $this->getDiscussion($discussionId);
    }

    /**
     * Update discussion
     */
    public function updateDiscussion(int $discussionId, int $userId, array $data): array
    {
        $discussion = DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('user_id', $userId)
            ->first();

        if (!$discussion) {
            throw new \Exception('Discussion not found or you do not have permission to edit it');
        }

        $updateData = array_filter($data, function ($value) {
            return $value !== null;
        });

        if (!empty($updateData)) {
            $updateData['updated_at'] = now();
            DB::table('elearning__discussions')
                ->where('id', $discussionId)
                ->update($updateData);
        }

        // Update tags if provided
        if (isset($data['tags'])) {
            $this->updateDiscussionTags($discussionId, $data['tags']);
        }

        return $this->getDiscussion($discussionId);
    }

    /**
     * Delete discussion
     */
    public function deleteDiscussion(int $discussionId, int $userId): bool
    {
        $discussion = DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('user_id', $userId)
            ->first();

        if (!$discussion) {
            throw new \Exception('Discussion not found or you do not have permission to delete it');
        }

        // Soft delete - mark as inactive
        return DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    /**
     * Get discussion replies
     */
    public function getDiscussionReplies(int $discussionId, string $sortBy = 'oldest', int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('elearning__discussion_replies as dr')
            ->join('elearning__users as u', 'dr.user_id', '=', 'u.id')
            ->where('dr.discussion_id', $discussionId)
            ->where('dr.is_active', true)
            ->select('dr.*', 'u.name as user_name', 'u.avatar as user_avatar');

        switch ($sortBy) {
            case 'newest':
                $query->orderBy('dr.created_at', 'desc');
                break;
            case 'best':
                $query->orderBy('dr.likes_count', 'desc');
                break;
            case 'oldest':
            default:
                $query->orderBy('dr.created_at', 'asc');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Add reply to discussion
     */
    public function addReply(int $discussionId, int $userId, array $data): array
    {
        // Check if discussion exists and is active
        $discussion = DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('is_active', true)
            ->first();

        if (!$discussion) {
            throw new \Exception('Discussion not found or is not active');
        }

        // Check if user is enrolled in the course
        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $discussion->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to reply to discussions');
        }

        $replyData = [
            'discussion_id' => $discussionId,
            'user_id' => $userId,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'is_active' => true,
            'likes_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $replyId = DB::table('elearning__discussion_replies')->insertGetId($replyData);

        // Update discussion's updated_at timestamp
        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->update(['updated_at' => now()]);

        return $this->getReply($replyId);
    }

    /**
     * Update reply
     */
    public function updateReply(int $replyId, int $userId, array $data): array
    {
        $reply = DB::table('elearning__discussion_replies')
            ->where('id', $replyId)
            ->where('user_id', $userId)
            ->first();

        if (!$reply) {
            throw new \Exception('Reply not found or you do not have permission to edit it');
        }

        DB::table('elearning__discussion_replies')
            ->where('id', $replyId)
            ->update([
                'content' => $data['content'],
                'updated_at' => now()
            ]);

        return $this->getReply($replyId);
    }

    /**
     * Delete reply
     */
    public function deleteReply(int $replyId, int $userId): bool
    {
        $reply = DB::table('elearning__discussion_replies')
            ->where('id', $replyId)
            ->where('user_id', $userId)
            ->first();

        if (!$reply) {
            throw new \Exception('Reply not found or you do not have permission to delete it');
        }

        // Soft delete - mark as inactive
        return DB::table('elearning__discussion_replies')
            ->where('id', $replyId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    /**
     * Like discussion
     */
    public function likeDiscussion(int $discussionId, int $userId): bool
    {
        // Check if already liked
        $existingLike = DB::table('elearning__discussion_likes')
            ->where('discussion_id', $discussionId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            throw new \Exception('You have already liked this discussion');
        }

        // Add like
        DB::table('elearning__discussion_likes')->insert([
            'discussion_id' => $discussionId,
            'user_id' => $userId,
            'created_at' => now()
        ]);

        // Update likes count
        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->increment('likes_count');

        return true;
    }

    /**
     * Unlike discussion
     */
    public function unlikeDiscussion(int $discussionId, int $userId): bool
    {
        // Check if liked
        $existingLike = DB::table('elearning__discussion_likes')
            ->where('discussion_id', $discussionId)
            ->where('user_id', $userId)
            ->first();

        if (!$existingLike) {
            throw new \Exception('You have not liked this discussion');
        }

        // Remove like
        DB::table('elearning__discussion_likes')
            ->where('discussion_id', $discussionId)
            ->where('user_id', $userId)
            ->delete();

        // Update likes count
        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->decrement('likes_count');

        return true;
    }

    /**
     * Mark discussion as solved
     */
    public function markAsSolved(int $discussionId, int $userId): bool
    {
        $discussion = DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('user_id', $userId)
            ->first();

        if (!$discussion) {
            throw new \Exception('Discussion not found or you do not have permission to mark it as solved');
        }

        return DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->update(['is_solved' => true, 'updated_at' => now()]) > 0;
    }

    /**
     * Get discussion categories
     */
    public function getDiscussionCategories(int $courseId): array
    {
        $categories = DB::table('elearning__discussions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        return $categories->toArray();
    }

    /**
     * Search discussions
     */
    public function searchDiscussions(string $query, ?int $courseId = null, int $perPage = 20): LengthAwarePaginator
    {
        $searchQuery = DB::table('elearning__discussions as d')
            ->join('elearning__users as u', 'd.user_id', '=', 'u.id')
            ->where('d.is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('d.title', 'like', "%{$query}%")
                  ->orWhere('d.content', 'like', "%{$query}%");
            })
            ->select('d.*', 'u.name as user_name', 'u.avatar as user_avatar');

        if ($courseId) {
            $searchQuery->where('d.course_id', $courseId);
        }

        return $searchQuery->orderBy('d.created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get user's discussions
     */
    public function getUserDiscussions(int $userId, string $status = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('elearning__discussions as d')
            ->join('elearning__courses as c', 'd.course_id', '=', 'c.id')
            ->where('d.user_id', $userId)
            ->select('d.*', 'c.name as course_name');

        switch ($status) {
            case 'active':
                $query->where('d.is_active', true);
                break;
            case 'solved':
                $query->where('d.is_solved', true);
                break;
            case 'closed':
                $query->where('d.is_active', false);
                break;
            // 'all' includes all discussions
        }

        return $query->orderBy('d.created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get discussion statistics
     */
    public function getDiscussionStatistics(int $courseId): array
    {
        $totalDiscussions = DB::table('elearning__discussions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->count();

        $totalReplies = DB::table('elearning__discussion_replies as dr')
            ->join('elearning__discussions as d', 'dr.discussion_id', '=', 'd.id')
            ->where('d.course_id', $courseId)
            ->where('dr.is_active', true)
            ->count();

        $solvedDiscussions = DB::table('elearning__discussions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->where('is_solved', true)
            ->count();

        $activeUsers = DB::table('elearning__discussions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->distinct('user_id')
            ->count('user_id');

        return [
            'total_discussions' => $totalDiscussions,
            'total_replies' => $totalReplies,
            'solved_discussions' => $solvedDiscussions,
            'active_users' => $activeUsers,
            'last_updated' => now()
        ];
    }

    // Private helper methods
    private function addTagsToDiscussion(int $discussionId, array $tags): void
    {
        foreach ($tags as $tagName) {
            // Find or create tag
            $tag = DB::table('elearning__tags')
                ->where('name', $tagName)
                ->first();

            if (!$tag) {
                $tagId = DB::table('elearning__tags')->insertGetId([
                    'name' => $tagName,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $tagId = $tag->id;
            }

            // Link tag to discussion
            DB::table('elearning__discussion_tags')->insert([
                'discussion_id' => $discussionId,
                'tag_id' => $tagId,
                'created_at' => now()
            ]);
        }
    }

    private function updateDiscussionTags(int $discussionId, array $tags): void
    {
        // Remove existing tags
        DB::table('elearning__discussion_tags')
            ->where('discussion_id', $discussionId)
            ->delete();

        // Add new tags
        $this->addTagsToDiscussion($discussionId, $tags);
    }

    private function getReply(int $replyId): array
    {
        $reply = DB::table('elearning__discussion_replies as dr')
            ->join('elearning__users as u', 'dr.user_id', '=', 'u.id')
            ->where('dr.id', $replyId)
            ->select('dr.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->first();

        if (!$reply) {
            throw new \Exception('Reply not found');
        }

        return (array) $reply;
    }
}
