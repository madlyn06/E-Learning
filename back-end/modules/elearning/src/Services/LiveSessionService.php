<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;

class LiveSessionService
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
     * Get course live sessions
     */
    public function getCourseSessions(int $courseId): array
    {
        $sessions = DB::table('elearning__live_sessions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->orderBy('start_time', 'asc')
            ->get();

        return $sessions->toArray();
    }

    /**
     * Get live session details
     */
    public function getSession(int $sessionId): array
    {
        $session = DB::table('elearning__live_sessions as ls')
            ->join('elearning__courses as c', 'ls.course_id', '=', 'c.id')
            ->join('elearning__users as u', 'ls.instructor_id', '=', 'u.id')
            ->where('ls.id', $sessionId)
            ->where('ls.is_active', true)
            ->select('ls.*', 'c.name as course_name', 'u.name as instructor_name')
            ->first();

        if (!$session) {
            throw new \Exception('Live session not found');
        }

        // Get participants count
        $participantsCount = DB::table('elearning__live_session_participants')
            ->where('session_id', $sessionId)
            ->count();

        // Get session status
        $status = $this->getSessionStatus($session);

        return [
            'session' => $session,
            'participants_count' => $participantsCount,
            'status' => $status
        ];
    }

    /**
     * Create live session
     */
    public function createSession(int $instructorId, array $data): array
    {
        // Check if user owns the course
        $course = $this->courseRepository->findById($data['course_id']);
        if (!$course || $course->user_id !== $instructorId) {
            throw new \Exception('You can only create sessions for your own courses');
        }

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

        return $this->getSession($sessionId);
    }

    /**
     * Update live session
     */
    public function updateSession(int $sessionId, int $instructorId, array $data): array
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found or you do not have permission to edit it');
        }

        // Check if session can be updated (not started or ended)
        if (in_array($session->status, ['in_progress', 'ended'])) {
            throw new \Exception('Cannot update session that is in progress or has ended');
        }

        $updateData = array_filter($data, function ($value) {
            return $value !== null;
        });

        if (!empty($updateData)) {
            $updateData['updated_at'] = now();
            DB::table('elearning__live_sessions')
                ->where('id', $sessionId)
                ->update($updateData);
        }

        return $this->getSession($sessionId);
    }

    /**
     * Delete live session
     */
    public function deleteSession(int $sessionId, int $instructorId): bool
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found or you do not have permission to delete it');
        }

        // Check if session can be deleted (not started or ended)
        if (in_array($session->status, ['in_progress', 'ended'])) {
            throw new \Exception('Cannot delete session that is in progress or has ended');
        }

        // Soft delete - mark as inactive
        return DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    /**
     * Join live session
     */
    public function joinSession(int $sessionId, int $userId): array
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('is_active', true)
            ->first();

        if (!$session) {
            throw new \Exception('Live session not found or is not active');
        }

        // Check if user is enrolled in the course
        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $session->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to join live sessions');
        }

        // Check if session is public or user is instructor
        if (!$session->is_public && $session->instructor_id !== $userId) {
            throw new \Exception('This session is not public');
        }

        // Check if session has started
        if (now() < $session->start_time) {
            throw new \Exception('Session has not started yet');
        }

        // Check if session has ended
        if (now() > $session->start_time->addMinutes($session->duration)) {
            throw new \Exception('Session has already ended');
        }

        // Check if user is already a participant
        $existingParticipant = DB::table('elearning__live_session_participants')
            ->where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->first();

        if ($existingParticipant) {
            throw new \Exception('You are already participating in this session');
        }

        // Check max participants limit
        if ($session->max_participants) {
            $currentParticipants = DB::table('elearning__live_session_participants')
                ->where('session_id', $sessionId)
                ->count();

            if ($currentParticipants >= $session->max_participants) {
                throw new \Exception('Session has reached maximum participants limit');
            }
        }

        // Add participant
        DB::table('elearning__live_session_participants')->insert([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'joined_at' => now(),
            'created_at' => now()
        ]);

        return [
            'session' => $session,
            'join_successful' => true,
            'meeting_url' => $session->meeting_url,
            'meeting_id' => $session->meeting_id,
            'password' => $session->password
        ];
    }

    /**
     * Leave live session
     */
    public function leaveSession(int $sessionId, int $userId): bool
    {
        $participant = DB::table('elearning__live_session_participants')
            ->where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            throw new \Exception('You are not participating in this session');
        }

        // Update left_at timestamp
        return DB::table('elearning__live_session_participants')
            ->where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->update(['left_at' => now()]) > 0;
    }

    /**
     * Get session participants
     */
    public function getSessionParticipants(int $sessionId): array
    {
        $participants = DB::table('elearning__live_session_participants as lsp')
            ->join('elearning__users as u', 'lsp.user_id', '=', 'u.id')
            ->where('lsp.session_id', $sessionId)
            ->select('lsp.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->orderBy('lsp.joined_at', 'asc')
            ->get();

        return $participants->toArray();
    }

    /**
     * Start live session
     */
    public function startSession(int $sessionId, int $instructorId): bool
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found or you do not have permission to start it');
        }

        if ($session->status !== 'scheduled') {
            throw new \Exception('Session can only be started if it is scheduled');
        }

        if (now() < $session->start_time) {
            throw new \Exception('Cannot start session before scheduled time');
        }

        return DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->update([
                'status' => 'in_progress',
                'started_at' => now(),
                'updated_at' => now()
            ]) > 0;
    }

    /**
     * End live session
     */
    public function endSession(int $sessionId, int $instructorId): bool
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found or you do not have permission to end it');
        }

        if ($session->status !== 'in_progress') {
            throw new \Exception('Session can only be ended if it is in progress');
        }

        return DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->update([
                'status' => 'ended',
                'ended_at' => now(),
                'updated_at' => now()
            ]) > 0;
    }

    /**
     * Get session recording
     */
    public function getSessionRecording(int $sessionId): array
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found');
        }

        if ($session->status !== 'ended') {
            throw new \Exception('Session recording is only available after session ends');
        }

        $recording = DB::table('elearning__live_session_recordings')
            ->where('session_id', $sessionId)
            ->first();

        if (!$recording) {
            throw new \Exception('No recording available for this session');
        }

        return [
            'recording' => $recording,
            'session' => $session
        ];
    }

    /**
     * Get upcoming sessions
     */
    public function getUpcomingSessions(int $userId, ?int $courseId = null, int $limit = 10): array
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

    /**
     * Get past sessions
     */
    public function getPastSessions(int $userId, ?int $courseId = null, int $perPage = 20): LengthAwarePaginator
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

    /**
     * Send session reminder
     */
    public function sendSessionReminder(int $sessionId, int $instructorId): bool
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('instructor_id', $instructorId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found or you do not have permission to send reminders');
        }

        if ($session->status !== 'scheduled') {
            throw new \Exception('Reminders can only be sent for scheduled sessions');
        }

        // Get enrolled students
        $enrolledStudents = DB::table('elearning__enrollments')
            ->where('course_id', $session->course_id)
            ->pluck('user_id')
            ->toArray();

        if (empty($enrolledStudents)) {
            return false;
        }

        // Send notifications to enrolled students
        // This would integrate with the notification system
        foreach ($enrolledStudents as $studentId) {
            // Send reminder notification
            \Log::info("Session reminder sent to user {$studentId} for session {$sessionId}");
        }

        return true;
    }

    /**
     * Get session chat messages
     */
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

    /**
     * Send chat message
     */
    public function sendChatMessage(int $sessionId, int $userId, array $data): array
    {
        $session = DB::table('elearning__live_sessions')
            ->where('id', $sessionId)
            ->where('is_active', true)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found or is not active');
        }

        // Check if user is participating in the session
        $participant = DB::table('elearning__live_session_participants')
            ->where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            throw new \Exception('You must be participating in the session to send chat messages');
        }

        $messageData = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'message' => $data['message'],
            'is_private' => $data['is_private'] ?? false,
            'recipient_id' => $data['recipient_id'] ?? null,
            'created_at' => now()
        ];

        $messageId = DB::table('elearning__live_session_chat')->insertGetId($messageData);

        return $this->getChatMessage($messageId);
    }

    /**
     * Get session status
     */
    private function getSessionStatus($session): string
    {
        $now = now();
        $startTime = $session->start_time;
        $endTime = $startTime->addMinutes($session->duration);

        if ($session->status === 'ended') {
            return 'ended';
        }

        if ($session->status === 'in_progress') {
            return 'in_progress';
        }

        if ($now < $startTime) {
            return 'scheduled';
        }

        if ($now >= $startTime && $now <= $endTime) {
            return 'in_progress';
        }

        return 'ended';
    }

    /**
     * Get chat message
     */
    private function getChatMessage(int $messageId): array
    {
        $message = DB::table('elearning__live_session_chat as lsc')
            ->join('elearning__users as u', 'lsc.user_id', '=', 'u.id')
            ->where('lsc.id', $messageId)
            ->select('lsc.*', 'u.name as user_name', 'u.avatar as user_avatar')
            ->first();

        if (!$message) {
            throw new \Exception('Message not found');
        }

        return (array) $message;
    }

    /**
     * Get live session statistics
     */
    public function getLiveSessionStatistics(int $courseId): array
    {
        $totalSessions = DB::table('elearning__live_sessions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->count();

        $completedSessions = DB::table('elearning__live_sessions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->where('status', 'ended')
            ->count();

        $totalParticipants = DB::table('elearning__live_session_participants as lsp')
            ->join('elearning__live_sessions as ls', 'lsp.session_id', '=', 'ls.id')
            ->where('ls.course_id', $courseId)
            ->where('ls.is_active', true)
            ->distinct('lsp.user_id')
            ->count('lsp.user_id');

        $averageDuration = DB::table('elearning__live_sessions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->where('status', 'ended')
            ->avg('duration');

        return [
            'total_sessions' => $totalSessions,
            'completed_sessions' => $completedSessions,
            'total_participants' => $totalParticipants,
            'average_duration' => round($averageDuration ?? 0, 2),
            'last_updated' => now()
        ];
    }
}
