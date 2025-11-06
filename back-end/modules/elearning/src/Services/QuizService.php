<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;

class QuizService
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
     * Get quizzes for a lesson
     */
    public function getLessonQuizzes(int $lessonId, int $userId): array
    {
        // Check if user is enrolled in the course
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->where('l.id', $lessonId)
            ->first();

        if (!$lesson) {
            throw new \Exception('Lesson not found');
        }

        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to access quizzes');
        }

        $quizzes = DB::table('elearning__quizzes')
            ->where('lesson_id', $lessonId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return $quizzes->toArray();
    }

    /**
     * Create quiz
     */
    public function createQuiz(int $instructorId, array $data): array
    {
        // Check if user owns the course
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('l.id', $data['lesson_id'])
            ->select('l.*', 'c.user_id as course_owner_id')
            ->first();

        if (!$lesson || $lesson->course_owner_id !== $instructorId) {
            throw new \Exception('You can only create quizzes for your own courses');
        }

        DB::beginTransaction();

        try {
            // Create quiz
            $quizData = [
                'lesson_id' => $data['lesson_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'time_limit' => $data['time_limit'] ?? null,
                'passing_score' => $data['passing_score'],
                'is_active' => true,
                'created_by' => $instructorId,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $quizId = DB::table('elearning__quizzes')->insertGetId($quizData);

            // Create questions
            foreach ($data['questions'] as $questionData) {
                $questionId = DB::table('elearning__quiz_questions')->insertGetId([
                    'quiz_id' => $quizId,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => json_encode($questionData['options'] ?? []),
                    'correct_answer' => $questionData['correct_answer'] ?? null,
                    'points' => $questionData['points'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return $this->getQuiz($quizId, $instructorId);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get quiz details
     */
    public function getQuiz(int $quizId, int $userId): array
    {
        $quiz = DB::table('elearning__quizzes as q')
            ->join('elearning__lessons as l', 'q.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('q.id', $quizId)
            ->where('q.is_active', true)
            ->select('q.*', 'l.name as lesson_name', 'c.name as course_name')
            ->first();

        if (!$quiz) {
            throw new \Exception('Quiz not found');
        }

        // Check if user is enrolled in the course
        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $quiz->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to access this quiz');
        }

        // Get questions
        $questions = DB::table('elearning__quiz_questions')
            ->where('quiz_id', $quizId)
            ->orderBy('id', 'asc')
            ->get();

        // Check if user has already taken this quiz
        $existingAttempt = DB::table('elearning__quiz_attempts')
            ->where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        return [
            'quiz' => $quiz,
            'questions' => $questions,
            'has_attempted' => $existingAttempt !== null,
            'total_questions' => $questions->count(),
            'total_points' => $questions->sum('points')
        ];
    }

    /**
     * Update quiz
     */
    public function updateQuiz(int $quizId, int $instructorId, array $data): array
    {
        $quiz = DB::table('elearning__quizzes as q')
            ->join('elearning__lessons as l', 'q.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('q.id', $quizId)
            ->where('c.user_id', $instructorId)
            ->first();

        if (!$quiz) {
            throw new \Exception('Quiz not found or you do not have permission to edit it');
        }

        $updateData = array_filter($data, function ($value) {
            return $value !== null;
        });

        if (!empty($updateData)) {
            $updateData['updated_at'] = now();
            DB::table('elearning__quizzes')
                ->where('id', $quizId)
                ->update($updateData);
        }

        return $this->getQuiz($quizId, $instructorId);
    }

    /**
     * Delete quiz
     */
    public function deleteQuiz(int $quizId, int $instructorId): bool
    {
        $quiz = DB::table('elearning__quizzes as q')
            ->join('elearning__lessons as l', 'q.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('q.id', $quizId)
            ->where('c.user_id', $instructorId)
            ->first();

        if (!$quiz) {
            throw new \Exception('Quiz not found or you do not have permission to delete it');
        }

        // Check if there are attempts
        $attemptCount = DB::table('elearning__quiz_attempts')
            ->where('quiz_id', $quizId)
            ->count();

        if ($attemptCount > 0) {
            throw new \Exception('Cannot delete quiz that has attempts');
        }

        // Soft delete - mark as inactive
        return DB::table('elearning__quizzes')
            ->where('id', $quizId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    /**
     * Take quiz
     */
    public function takeQuiz(int $quizId, int $userId, array $answers): array
    {
        // Check if quiz exists and is active
        $quiz = DB::table('elearning__quizzes')
            ->where('id', $quizId)
            ->where('is_active', true)
            ->first();

        if (!$quiz) {
            throw new \Exception('Quiz not found or is not active');
        }

        // Check if user is enrolled in the course
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->where('l.id', $quiz->lesson_id)
            ->first();

        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to take this quiz');
        }

        // Check if user has already taken this quiz
        $existingAttempt = DB::table('elearning__quiz_attempts')
            ->where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        if ($existingAttempt) {
            throw new \Exception('You have already taken this quiz');
        }

        DB::beginTransaction();

        try {
            // Create quiz attempt
            $attemptId = DB::table('elearning__quiz_attempts')->insertGetId([
                'quiz_id' => $quizId,
                'user_id' => $userId,
                'started_at' => now(),
                'completed_at' => now(),
                'status' => 'completed',
                'created_at' => now()
            ]);

            // Process answers and calculate score
            $totalScore = 0;
            $maxScore = 0;

            foreach ($answers as $answer) {
                $question = DB::table('elearning__quiz_questions')
                    ->where('id', $answer['question_id'])
                    ->where('quiz_id', $quizId)
                    ->first();

                if (!$question) {
                    continue;
                }

                $maxScore += $question->points;
                $isCorrect = $this->checkAnswer($question, $answer['answer']);
                
                if ($isCorrect) {
                    $totalScore += $question->points;
                }

                // Store answer
                DB::table('elearning__quiz_answers')->insert([
                    'attempt_id' => $attemptId,
                    'question_id' => $answer['question_id'],
                    'user_answer' => $answer['answer'],
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $question->points : 0,
                    'created_at' => now()
                ]);
            }

            // Calculate percentage and determine if passed
            $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
            $passed = $percentage >= $quiz->passing_score;

            // Update attempt with results
            DB::table('elearning__quiz_attempts')
                ->where('id', $attemptId)
                ->update([
                    'score' => $totalScore,
                    'max_score' => $maxScore,
                    'percentage' => $percentage,
                    'passed' => $passed,
                    'updated_at' => now()
                ]);

            DB::commit();

            return [
                'attempt_id' => $attemptId,
                'score' => $totalScore,
                'max_score' => $maxScore,
                'percentage' => $percentage,
                'passed' => $passed,
                'passing_score' => $quiz->passing_score
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get quiz results
     */
    public function getQuizResults(int $quizId, int $userId): array
    {
        $attempt = DB::table('elearning__quiz_attempts')
            ->where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        if (!$attempt) {
            throw new \Exception('No quiz attempt found');
        }

        $answers = DB::table('elearning__quiz_answers as qa')
            ->join('elearning__quiz_questions as qq', 'qa.question_id', '=', 'qq.id')
            ->where('qa.attempt_id', $attempt->id)
            ->select('qa.*', 'qq.question', 'qq.type', 'qq.options', 'qq.correct_answer', 'qq.points')
            ->get();

        return [
            'attempt' => $attempt,
            'answers' => $answers,
            'summary' => [
                'total_questions' => $answers->count(),
                'correct_answers' => $answers->where('is_correct', true)->count(),
                'incorrect_answers' => $answers->where('is_correct', false)->count(),
                'score' => $attempt->score,
                'max_score' => $attempt->max_score,
                'percentage' => $attempt->percentage,
                'passed' => $attempt->passed
            ]
        ];
    }

    /**
     * Check if answer is correct
     */
    private function checkAnswer($question, $userAnswer): bool
    {
        switch ($question->type) {
            case 'multiple_choice':
            case 'single_choice':
                return $userAnswer === $question->correct_answer;
            
            case 'true_false':
                return strtolower($userAnswer) === strtolower($question->correct_answer);
            
            case 'essay':
                // For essay questions, we'll need manual grading
                return false;
            
            default:
                return false;
        }
    }

    /**
     * Get current user ID safely
     */
    public function getCurrentUserId(): int
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('User not authenticated');
        }
        return $user->getAuthIdentifier();
    }
}
