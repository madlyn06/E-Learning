<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Elearning\Models\LiveSession;
use Newnet\Core\Repositories\BaseRepository;

class LiveSessionRepository extends BaseRepository
{
    public function __construct(LiveSession $model)
    {
        parent::__construct($model);
    }

    public function getCourseSessions(int $courseId): array
    {
        $sessions = DB::table('elearning__live_sessions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->orderBy('start_time', 'asc')
            ->get();

        return $sessions->toArray();
    }

    public function findById(int $sessionId): ?array
    {
        $session = DB::table('elearning__live_sessions as ls')
            ->join('elearning__courses as c', 'ls.course_id', '=', 'c.id')
            ->join('elearning__users as u', 'ls.instructor_id', '=', 'u.id')
            ->where('ls.id', $sessionId)
            ->where('ls.is_active', true)
            ->select('ls.*', 'c.name as course_name', 'u.name as instructor_name')
            ->first();

        return $session ? (array) $session : null;
    }

    public function createSession(int $instructorId, array $data): array
    {
        $sessionData = [
            'course_id' => $data['course_id'],
            'instructor_id' => $instructorId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_time' => $data['start_time'],
            'duration' => $data['duration'],
            'max_participants' => $data['max_participants'] ?? null,
            'is_public' => $data['is_public'] ?? true,
            'platform' => $data['platform'],
            'meeting_url' => $data['meeting_url'] ?? null,
            'meeting_id' => $data['meeting_id'] ?? null,
            'password' => $data['password'] ?? null,
            'record_session' => $data['record_session'] ?? false,
            'status' => 'scheduled',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $sessionId = DB::table('elearning__live_sessions')->insertGetId($sessionData);
        return $this->findById($sessionId);
    }

    public function updateSession(int $sessionId, int $instructorId, array $data): array
    {
        DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->update(array_merge($data, ['updated_at' => now()]));

        return $this->findById($sessionId);
    }

    public function deleteSession(int $sessionId, int $instructorId): bool
    {
        return DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    public function join(int $sessionId, int $userId): array
    {
        DB::table('elearning__live_session_participants')->insert([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'joined_at' => now(),
            'created_at' => now()
        ]);

        return $this->findById($sessionId);
    }

    public function leave(int $sessionId, int $userId): bool
    {
        return DB::table('elearning__live_session_participants')
            ->where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->update(['left_at' => now()]) > 0;
    }

    public function getParticipants(int $sessionId): array
    {
        $participants = DB::table('elearning__live_session_participants as lsp')
            ->join('elearning__users as u', 'lsp.user_id', '=', 'u.id')
            ->where('lsp.session_id', $sessionId)
            ->select('lsp.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->orderBy('lsp.joined_at', 'asc')
            ->get();

        return $participants->toArray();
    }

    public function start(int $sessionId, int $instructorId): bool
    {
        return DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->update([
                'status' => 'in_progress',
                'started_at' => now(),
                'updated_at' => now()
            ]) > 0;
    }

    public function end(int $sessionId, int $instructorId): bool
    {
        return DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->update([
                'status' => 'ended',
                'ended_at' => now(),
                'updated_at' => now()
            ]) > 0;
    }

    public function getUpcoming(int $userId, ?int $courseId = null, int $limit = 10): array
    {
        $query = DB::table('elearning__live_sessions as ls')
            ->join('elearning__courses as c', 'ls.course_id', '=', 'c.id')
            ->join('elearning__users as u', 'ls.instructor_id', '=', 'u.id')
            ->where('ls.is_active', true)
            ->where('ls.status', 'scheduled')
            ->where('ls.start_time', '>', now())
            ->select('ls.*', 'c.name as course_name', 'u.name as instructor_name');

        if ($courseId) {
            $query->where('ls.course_id', $courseId);
        }

        $sessions = $query->orderBy('ls.start_time', 'asc')
            ->limit($limit)
            ->get();

        return $sessions->toArray();
    }

    public function getPast(int $userId, ?int $courseId = null, int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('elearning__live_sessions as ls')
            ->join('elearning__courses as c', 'ls.course_id', '=', 'c.id')
            ->join('elearning__users as u', 'ls.instructor_id', '=', 'u.id')
            ->where('ls.is_active', true)
            ->where('ls.status', 'ended')
            ->select('ls.*', 'c.name as course_name', 'u.name as instructor_name');

        if ($courseId) {
            $query->where('ls.course_id', $courseId);
        }

        return $query->orderBy('ls.ended_at', 'desc')->paginate($perPage);
    }

    public function getChatMessages(int $sessionId, int $perPage = 50, ?int $lastMessageId = null): LengthAwarePaginator
    {
        $query = DB::table('elearning__live_session_chat as lsc')
            ->join('elearning__users as u', 'lsc.user_id', '=', 'u.id')
            ->where('lsc.session_id', $sessionId)
            ->select('lsc.*', 'u.name as user_name', 'u.avatar as user_avatar');

        if ($lastMessageId) {
            $query->where('lsc.id', '>', $lastMessageId);
        }

        return $query->orderBy('lsc.created_at', 'asc')->paginate($perPage);
    }

    public function sendChatMessage(int $sessionId, int $userId, array $data)
    {
        $messageData = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'message' => $data['message'],
            'is_private' => $data['is_private'] ?? false,
            'recipient_id' => $data['recipient_id'] ?? null,
            'created_at' => now()
        ];

        $messageId = DB::table('elearning__live_session_chat')->insertGetId($messageData);

        return DB::table('elearning__live_session_chat as lsc')
            ->join('elearning__users as u', 'lsc.user_id', '=', 'u.id')
            ->where('lsc.id', $messageId)
            ->select('lsc.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->first();
    }
}
