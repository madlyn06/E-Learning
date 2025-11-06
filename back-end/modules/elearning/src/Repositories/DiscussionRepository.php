<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Elearning\Models\Discussion;
use Newnet\Core\Repositories\BaseRepository;

class DiscussionRepository extends BaseRepository
{
    public function __construct(Discussion $model)
    {
        $this->model = $model;
    }

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

    public function findById(int $discussionId): ?array
    {
        $discussion = DB::table('elearning__discussions as d')
            ->join('elearning__users as u', 'd.user_id', '=', 'u.id')
            ->join('elearning__courses as c', 'd.course_id', '=', 'c.id')
            ->where('d.id', $discussionId)
            ->where('d.is_active', true)
            ->select('d.*', 'u.name as user_name', 'u.avatar as user_avatar', 'c.name as course_name')
            ->first();

        return $discussion ? (array) $discussion : null;
    }

    public function createDiscussion(int $userId, array $data): array
    {
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
        return $this->findById($discussionId);
    }

    public function updateDiscussion(int $discussionId, int $userId, array $data): array
    {
        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('user_id', $userId)
            ->update(array_merge($data, ['updated_at' => now()]));

        return $this->findById($discussionId);
    }

    public function deleteDiscussion(int $discussionId, int $userId): bool
    {
        return DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('user_id', $userId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    public function getReplies(int $discussionId, string $sortBy = 'oldest', int $perPage = 20): LengthAwarePaginator
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

    public function addReply(int $discussionId, int $userId, array $data)
    {
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
        
        // Update discussion timestamp
        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->update(['updated_at' => now()]);

        return DB::table('elearning__discussion_replies as dr')
            ->join('elearning__users as u', 'dr.user_id', '=', 'u.id')
            ->where('dr.id', $replyId)
            ->select('dr.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->first();
    }

    public function updateReply(int $replyId, int $userId, array $data)
    {
        DB::table('elearning__discussion_replies')
            ->where('id', $replyId)
            ->where('user_id', $userId)
            ->update(array_merge($data, ['updated_at' => now()]));

        return DB::table('elearning__discussion_replies as dr')
            ->join('elearning__users as u', 'dr.user_id', '=', 'u.id')
            ->where('dr.id', $replyId)
            ->select('dr.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->first();
    }

    public function deleteReply(int $replyId, int $userId): bool
    {
        return DB::table('elearning__discussion_replies')
            ->where('id', $replyId)
            ->where('user_id', $userId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    public function like(int $discussionId, int $userId): bool
    {
        DB::table('elearning__discussion_likes')->insert([
            'discussion_id' => $discussionId,
            'user_id' => $userId,
            'created_at' => now()
        ]);

        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->increment('likes_count');

        return true;
    }

    public function unlike(int $discussionId, int $userId): bool
    {
        DB::table('elearning__discussion_likes')
            ->where('discussion_id', $discussionId)
            ->where('user_id', $userId)
            ->delete();

        DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->decrement('likes_count');

        return true;
    }

    public function markAsSolved(int $discussionId, int $userId): bool
    {
        return DB::table('elearning__discussions')
            ->where('id', $discussionId)
            ->where('user_id', $userId)
            ->update(['is_solved' => true, 'updated_at' => now()]) > 0;
    }

    public function search(string $query, ?int $courseId = null, int $perPage = 20): LengthAwarePaginator
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
        }

        return $query->orderBy('d.created_at', 'desc')->paginate($perPage);
    }
}
