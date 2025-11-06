<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\AssignmentService;

class AssignmentController extends BaseController
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Get assignments for a lesson
     */
    public function index($lessonId): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            $assignments = $this->assignmentService->getLessonAssignments($lessonId);

            return $this->successResponse($assignments, 'Assignments retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve assignments: ' . $e->getMessage());
        }
    }

    /**
     * Create a new assignment
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            
            $request->validate([
                'lesson_id' => 'required|exists:elearning__lessons,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date|after:now',
                'max_score' => 'required|numeric|min:1|max:100',
                'attachment' => 'nullable|file|max:10240', // 10MB max
                'rubric' => 'nullable|array'
            ]);

            $assignment = $this->assignmentService->createAssignment($userId, $request->all());

            return $this->createdResponse($assignment, 'Assignment created successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create assignment: ' . $e->getMessage());
        }
    }

    /**
     * Get assignment details
     */
    public function show($assignmentId): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            $assignment = $this->assignmentService->getAssignment($assignmentId);

            return $this->successResponse($assignment, 'Assignment retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve assignment: ' . $e->getMessage());
        }
    }

    /**
     * Update assignment
     */
    public function update(Request $request, $assignmentId): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'due_date' => 'sometimes|date|after:now',
                'max_score' => 'sometimes|numeric|min:1|max:100',
                'rubric' => 'sometimes|array'
            ]);

            $assignment = $this->assignmentService->updateAssignment($assignmentId, $userId, $request->all());

            return $this->updatedResponse($assignment, 'Assignment updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update assignment: ' . $e->getMessage());
        }
    }

    /**
     * Delete assignment
     */
    public function destroy($assignmentId): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            $this->assignmentService->deleteAssignment($assignmentId, $userId);

            return $this->deletedResponse('Assignment deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to delete assignment: ' . $e->getMessage());
        }
    }

    /**
     * Submit assignment
     */
    public function submit(Request $request, $assignmentId): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            
            $request->validate([
                'content' => 'required|string',
                'attachment' => 'nullable|file|max:10240' // 10MB max
            ]);

            $submissionData = [
                'submission_text' => $request->content,
                'file' => $request->file('attachment')
            ];

            $submission = $this->assignmentService->submitAssignment($assignmentId, $userId, $submissionData);

            return $this->createdResponse($submission, 'Assignment submitted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to submit assignment: ' . $e->getMessage());
        }
    }

    /**
     * Get all submissions for an assignment (teachers only)
     */
    public function getSubmissions($assignmentId): JsonResponse
    {
        try {
            $userId = $this->assignmentService->getCurrentUserId();
            $submissions = $this->assignmentService->getAssignmentSubmissions($assignmentId);

            return $this->successResponse($submissions, 'Submissions retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve submissions: ' . $e->getMessage());
        }
    }
}
