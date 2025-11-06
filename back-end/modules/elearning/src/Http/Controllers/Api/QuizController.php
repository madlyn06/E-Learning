<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Services\QuizService;

class QuizController extends BaseController
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Get quizzes for a lesson
     */
    public function index($lessonId): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            $quizzes = $this->quizService->getLessonQuizzes($lessonId, $userId);

            return $this->successResponse($quizzes, 'Quizzes retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve quizzes: ' . $e->getMessage());
        }
    }

    /**
     * Create a new quiz
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            
            $request->validate([
                'lesson_id' => 'required|exists:elearning__lessons,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'time_limit' => 'nullable|integer|min:1',
                'passing_score' => 'required|integer|min:1|max:100',
                'questions' => 'required|array|min:1',
                'questions.*.question' => 'required|string',
                'questions.*.type' => 'required|in:multiple_choice,single_choice,true_false,essay',
                'questions.*.options' => 'required_if:questions.*.type,multiple_choice,single_choice|array',
                'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice,single_choice,true_false',
                'questions.*.points' => 'required|integer|min:1'
            ]);

            $quiz = $this->quizService->createQuiz($userId, $request->all());

            return $this->createdResponse($quiz, 'Quiz created successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create quiz: ' . $e->getMessage());
        }
    }

    /**
     * Get quiz details
     */
    public function show($quizId): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            $quiz = $this->quizService->getQuiz($quizId, $userId);

            return $this->successResponse($quiz, 'Quiz retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve quiz: ' . $e->getMessage());
        }
    }

    /**
     * Update quiz
     */
    public function update(Request $request, $quizId): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'time_limit' => 'sometimes|integer|min:1',
                'passing_score' => 'sometimes|integer|min:1|max:100',
                'questions' => 'sometimes|array|min:1'
            ]);

            $quiz = $this->quizService->updateQuiz($quizId, $userId, $request->all());

            return $this->updatedResponse($quiz, 'Quiz updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update quiz: ' . $e->getMessage());
        }
    }

    /**
     * Delete quiz
     */
    public function destroy($quizId): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            $this->quizService->deleteQuiz($quizId, $userId);

            return $this->deletedResponse('Quiz deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to delete quiz: ' . $e->getMessage());
        }
    }

    /**
     * Take quiz
     */
    public function takeQuiz(Request $request, $quizId): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            
            $request->validate([
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|exists:elearning__quiz_questions,id',
                'answers.*.answer' => 'required|string'
            ]);

            $result = $this->quizService->takeQuiz($quizId, $userId, $request->answers);

            return $this->createdResponse($result, 'Quiz completed successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to complete quiz: ' . $e->getMessage());
        }
    }

    /**
     * Get quiz results
     */
    public function getResults($quizId): JsonResponse
    {
        try {
            $userId = $this->quizService->getCurrentUserId();
            $results = $this->quizService->getQuizResults($quizId, $userId);

            return $this->successResponse($results, 'Quiz results retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve quiz results: ' . $e->getMessage());
        }
    }
}
