<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\LiveSessionService;

class LiveSessionController extends BaseController
{
    protected $liveSessionService;

    public function __construct(LiveSessionService $liveSessionService)
    {
        $this->liveSessionService = $liveSessionService;
    }

    /**
     * Get course live sessions
     */
    public function getCourseSessions(int $courseId): JsonResponse
    {
        try {
            $sessions = $this->liveSessionService->getCourseSessions($courseId);
            return $this->successResponse($sessions, 'Live sessions retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get live session details
     */
    public function getSession(int $sessionId): JsonResponse
    {
        try {
            $session = $this->liveSessionService->getSession($sessionId);
            return $this->successResponse($session, 'Live session retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Create live session
     */
    public function createSession(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'course_id' => 'required|integer|exists:elearning__courses,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date|after:now',
                'duration' => 'required|integer|min:15|max:480', // 15 minutes to 8 hours
                'max_participants' => 'nullable|integer|min:1|max:1000',
                'is_public' => 'boolean',
                'platform' => 'required|string|in:zoom,teams,meet,custom',
                'meeting_url' => 'nullable|url',
                'meeting_id' => 'nullable|string',
                'password' => 'nullable|string',
                'record_session' => 'boolean'
            ]);

            $session = $this->liveSessionService->createSession(auth('sanctum')->id(), $data);
            return $this->createdResponse($session, 'Live session created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update live session
     */
    public function updateSession(int $sessionId, Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date|after:now',
                'duration' => 'sometimes|integer|min:15|max:480',
                'max_participants' => 'nullable|integer|min:1|max:1000',
                'is_public' => 'boolean',
                'platform' => 'sometimes|string|in:zoom,teams,meet,custom',
                'meeting_url' => 'nullable|url',
                'meeting_id' => 'nullable|string',
                'password' => 'nullable|string',
                'record_session' => 'boolean'
            ]);

            $session = $this->liveSessionService->updateSession($sessionId, auth('sanctum')->id(), $data);
            return $this->updatedResponse($session, 'Live session updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Delete live session
     */
    public function deleteSession(int $sessionId): JsonResponse
    {
        try {
            $this->liveSessionService->deleteSession($sessionId, auth('sanctum')->id());
            return $this->deletedResponse('Live session deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Join live session
     */
    public function joinSession(int $sessionId): JsonResponse
    {
        try {
            $sessionData = $this->liveSessionService->joinSession($sessionId, auth('sanctum')->id());
            return $this->successResponse($sessionData, 'Joined live session successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Leave live session
     */
    public function leaveSession(int $sessionId): JsonResponse
    {
        try {
            $this->liveSessionService->leaveSession($sessionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Left live session successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get session participants
     */
    public function getParticipants(int $sessionId): JsonResponse
    {
        try {
            $participants = $this->liveSessionService->getSessionParticipants($sessionId);
            return $this->successResponse($participants, 'Session participants retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Start live session
     */
    public function startSession(int $sessionId): JsonResponse
    {
        try {
            $this->liveSessionService->startSession($sessionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Live session started successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * End live session
     */
    public function endSession(int $sessionId): JsonResponse
    {
        try {
            $this->liveSessionService->endSession($sessionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Live session ended successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get session recording
     */
    public function getRecording(int $sessionId): JsonResponse
    {
        try {
            $recording = $this->liveSessionService->getSessionRecording($sessionId);
            return $this->successResponse($recording, 'Session recording retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get upcoming sessions
     */
    public function getUpcomingSessions(Request $request): JsonResponse
    {
        try {
            $courseId = $request->get('course_id');
            $limit = $request->get('limit', 10);
            
            $sessions = $this->liveSessionService->getUpcomingSessions(auth('sanctum')->id(), $courseId, $limit);
            return $this->successResponse($sessions, 'Upcoming sessions retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve upcoming sessions');
        }
    }

    /**
     * Get past sessions
     */
    public function getPastSessions(Request $request): JsonResponse
    {
        try {
            $courseId = $request->get('course_id');
            $perPage = $request->get('per_page', 20);
            
            $sessions = $this->liveSessionService->getPastSessions(auth('sanctum')->id(), $courseId, $perPage);
            return $this->paginatedResponse($sessions, 'Past sessions retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve past sessions');
        }
    }

    /**
     * Send session reminder
     */
    public function sendReminder(int $sessionId): JsonResponse
    {
        try {
            $this->liveSessionService->sendSessionReminder($sessionId, auth('sanctum')->id());
            return $this->successResponse(null, 'Session reminder sent successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get session chat messages
     */
    public function getChatMessages(int $sessionId, Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 50);
            $lastMessageId = $request->get('last_message_id');
            
            $messages = $this->liveSessionService->getChatMessages($sessionId, $perPage, $lastMessageId);
            return $this->paginatedResponse($messages, 'Chat messages retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Send chat message
     */
    public function sendChatMessage(int $sessionId, Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'message' => 'required|string|max:1000',
                'is_private' => 'boolean',
                'recipient_id' => 'nullable|integer|exists:elearning__users,id'
            ]);

            $message = $this->liveSessionService->sendChatMessage($sessionId, auth('sanctum')->id(), $data);
            return $this->createdResponse($message, 'Chat message sent successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
