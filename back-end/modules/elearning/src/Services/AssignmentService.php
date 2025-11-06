<?php

namespace Modules\Elearning\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Modules\Elearning\Repositories\CourseRepository;
use Modules\Elearning\Repositories\UserRepository;

class AssignmentService
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
     * Get lesson assignments
     */
    public function getLessonAssignments(int $lessonId): array
    {
        $assignments = DB::table('elearning__assignments')
            ->where('lesson_id', $lessonId)
            ->where('is_active', true)
            ->orderBy('due_date', 'asc')
            ->get();

        return $assignments->toArray();
    }

    /**
     * Get all assignments for a course
     */
    public function getCourseAssignments(int $courseId, int $perPage = 20): LengthAwarePaginator
    {
        return DB::table('elearning__assignments as a')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->where('s.course_id', $courseId)
            ->where('a.is_active', true)
            ->select('a.*', 'l.name as lesson_name', 's.name as section_name')
            ->orderBy('a.due_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get assignment details
     */
    public function getAssignment(int $assignmentId): array
    {
        $assignment = DB::table('elearning__assignments as a')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('a.id', $assignmentId)
            ->where('a.is_active', true)
            ->select('a.*', 'l.name as lesson_name', 's.name as section_name', 'c.name as course_name')
            ->first();

        if (!$assignment) {
            throw new \Exception('Assignment not found');
        }

        // Get submission count
        $submissionCount = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->count();

        return [
            'assignment' => $assignment,
            'submission_count' => $submissionCount
        ];
    }

    /**
     * Create assignment
     */
    public function createAssignment(int $instructorId, array $data): array
    {
        // Check if user owns the course
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('l.id', $data['lesson_id'])
            ->select('l.*', 'c.user_id as course_owner_id')
            ->first();

        if (!$lesson || $lesson->course_owner_id !== $instructorId) {
            throw new \Exception('You can only create assignments for your own courses');
        }

        $assignmentData = [
            'lesson_id' => $data['lesson_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'instructions' => $data['instructions'] ?? null,
            'due_date' => $data['due_date'],
            'max_score' => $data['max_score'] ?? 100,
            'allow_late_submission' => $data['allow_late_submission'] ?? false,
            'late_penalty' => $data['late_penalty'] ?? 0,
            'submission_type' => $data['submission_type'] ?? 'file', // file, text, url
            'allowed_file_types' => $data['allowed_file_types'] ?? null,
            'max_file_size' => $data['max_file_size'] ?? null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $assignmentId = DB::table('elearning__assignments')->insertGetId($assignmentData);

        return $this->getAssignment($assignmentId);
    }

    /**
     * Update assignment
     */
    public function updateAssignment(int $assignmentId, int $instructorId, array $data): array
    {
        $assignment = DB::table('elearning__assignments as a')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('a.id', $assignmentId)
            ->where('c.user_id', $instructorId)
            ->first();

        if (!$assignment) {
            throw new \Exception('Assignment not found or you do not have permission to edit it');
        }

        // Check if session can be updated (not started or ended)
        if (in_array($assignment->status, ['in_progress', 'ended'])) {
            throw new \Exception('Cannot update assignment that is in progress or has ended');
        }

        $updateData = array_filter($data, function ($value) {
            return $value !== null;
        });

        if (!empty($updateData)) {
            $updateData['updated_at'] = now();
            DB::table('elearning__assignments')
                ->where('id', $assignmentId)
                ->update($updateData);
        }

        return $this->getAssignment($assignmentId);
    }

    /**
     * Delete assignment
     */
    public function deleteAssignment(int $assignmentId, int $instructorId): bool
    {
        $assignment = DB::table('elearning__assignments as a')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('a.id', $assignmentId)
            ->where('c.user_id', $instructorId)
            ->first();

        if (!$assignment) {
            throw new \Exception('Assignment not found or you do not have permission to delete it');
        }

        // Check if there are submissions
        $submissionCount = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->count();

        if ($submissionCount > 0) {
            throw new \Exception('Cannot delete assignment that has submissions');
        }

        // Soft delete - mark as inactive
        return DB::table('elearning__assignments')
            ->where('id', $assignmentId)
            ->update(['is_active' => false, 'updated_at' => now()]) > 0;
    }

    /**
     * Submit assignment
     */
    public function submitAssignment(int $assignmentId, int $userId, array $data): array
    {
        // Check if assignment exists and is active
        $assignment = DB::table('elearning__assignments')
            ->where('id', $assignmentId)
            ->where('is_active', true)
            ->first();

        if (!$assignment) {
            throw new \Exception('Assignment not found or is not active');
        }

        // Check if user is enrolled in the course
        $lesson = DB::table('elearning__lessons as l')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->where('l.id', $assignment->lesson_id)
            ->first();

        $enrollment = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment) {
            throw new \Exception('You must be enrolled in this course to submit assignments');
        }

        // Check if assignment is due
        if (now() > $assignment->due_date && !$assignment->allow_late_submission) {
            throw new \Exception('Assignment is due and late submissions are not allowed');
        }

        // Check if user has already submitted
        $existingSubmission = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->where('user_id', $userId)
            ->first();

        if ($existingSubmission) {
            throw new \Exception('You have already submitted this assignment');
        }

        // Handle file upload if submission type is file
        $submissionData = [
            'assignment_id' => $assignmentId,
            'user_id' => $userId,
            'submission_text' => $data['submission_text'] ?? null,
            'submission_url' => $data['submission_url'] ?? null,
            'submitted_at' => now(),
            'is_late' => now() > $assignment->due_date,
            'status' => 'submitted',
            'created_at' => now(),
            'updated_at' => now()
        ];

        if ($assignment->submission_type === 'file' && isset($data['file'])) {
            $filePath = $this->uploadAssignmentFile($data['file'], $assignmentId, $userId);
            $submissionData['file_path'] = $filePath;
        }

        $submissionId = DB::table('elearning__assignment_submissions')->insertGetId($submissionData);

        return $this->getSubmission($submissionId);
    }

    /**
     * Get assignment submissions
     */
    public function getAssignmentSubmissions(int $assignmentId, int $perPage = 20): LengthAwarePaginator
    {
        return DB::table('elearning__assignment_submissions as as')
            ->join('elearning__users as u', 'as.user_id', '=', 'u.id')
            ->where('as.assignment_id', $assignmentId)
            ->select('as.*', 'u.name as user_name', 'u.email as user_email')
            ->orderBy('as.submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Grade assignment submission
     */
    public function gradeSubmission(int $submissionId, int $instructorId, array $data): array
    {
        $submission = DB::table('elearning__assignment_submissions as as')
            ->join('elearning__assignments as a', 'as.assignment_id', '=', 'a.id')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('as.id', $submissionId)
            ->where('c.user_id', $instructorId)
            ->first();

        if (!$submission) {
            throw new \Exception('Submission not found or you do not have permission to grade it');
        }

        $gradeData = [
            'score' => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'graded_at' => now(),
            'graded_by' => $instructorId,
            'status' => 'graded',
            'updated_at' => now()
        ];

        // Apply late penalty if applicable
        if ($submission->is_late && $submission->late_penalty > 0) {
            $penalty = ($submission->max_score * $submission->late_penalty) / 100;
            $gradeData['score'] = max(0, $gradeData['score'] - $penalty);
            $gradeData['late_penalty_applied'] = $penalty;
        }

        DB::table('elearning__assignment_submissions')
            ->where('id', $submissionId)
            ->update($gradeData);

        return $this->getSubmission($submissionId);
    }

    /**
     * Get user's assignment submissions
     */
    public function getUserSubmissions(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return DB::table('elearning__assignment_submissions as as')
            ->join('elearning__assignments as a', 'as.assignment_id', '=', 'a.id')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->where('as.user_id', $userId)
            ->select('as.*', 'a.title as assignment_title', 'l.name as lesson_name', 'c.name as course_name')
            ->orderBy('as.submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get assignment statistics
     */
    public function getAssignmentStatistics(int $assignmentId): array
    {
        $totalSubmissions = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->count();

        $gradedSubmissions = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->where('status', 'graded')
            ->count();

        $lateSubmissions = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->where('is_late', true)
            ->count();

        $averageScore = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->where('status', 'graded')
            ->avg('score') ?? 0;

        $scoreDistribution = [
            '90-100' => 0,
            '80-89' => 0,
            '70-79' => 0,
            '60-69' => 0,
            '0-59' => 0
        ];

        $scores = DB::table('elearning__assignment_submissions')
            ->where('assignment_id', $assignmentId)
            ->where('status', 'graded')
            ->pluck('score')
            ->toArray();

        foreach ($scores as $score) {
            if ($score >= 90) {
                $scoreDistribution['90-100']++;
            } elseif ($score >= 80) {
                $scoreDistribution['80-89']++;
            } elseif ($score >= 70) {
                $scoreDistribution['70-79']++;
            } elseif ($score >= 60) {
                $scoreDistribution['60-69']++;
            } else {
                $scoreDistribution['0-59']++;
            }
        }

        return [
            'total_submissions' => $totalSubmissions,
            'graded_submissions' => $gradedSubmissions,
            'late_submissions' => $lateSubmissions,
            'average_score' => round($averageScore, 2),
            'score_distribution' => $scoreDistribution,
            'completion_rate' => $totalSubmissions > 0 ? round(($gradedSubmissions / $totalSubmissions) * 100, 2) : 0,
            'last_updated' => now()
        ];
    }

    /**
     * Upload assignment file
     */
    private function uploadAssignmentFile($file, int $assignmentId, int $userId): string
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = "assignments/{$assignmentId}/{$userId}/{$fileName}";
        
        Storage::disk('public')->put($filePath, file_get_contents($file));
        
        return $filePath;
    }

    /**
     * Get submission details
     */
    private function getSubmission(int $submissionId): array
    {
        $submission = DB::table('elearning__assignment_submissions as as')
            ->join('elearning__assignments as a', 'as.assignment_id', '=', 'a.id')
            ->join('elearning__users as u', 'as.user_id', '=', 'u.id')
            ->where('as.id', $submissionId)
            ->select('as.*', 'a.title as assignment_title', 'u.name as user_name')
            ->first();

        if (!$submission) {
            throw new \Exception('Submission not found');
        }

        return (array) $submission;
    }

    /**
     * Get upcoming assignments for user
     */
    public function getUpcomingAssignments(int $userId, int $limit = 10): array
    {
        $assignments = DB::table('elearning__assignments as a')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->join('elearning__enrollments as e', 'c.id', '=', 'e.course_id')
            ->where('e.user_id', $userId)
            ->where('a.is_active', true)
            ->where('a.due_date', '>', now())
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('elearning__assignment_submissions')
                    ->whereColumn('assignment_id', 'a.id')
                    ->where('user_id', $userId);
            })
            ->select('a.*', 'l.name as lesson_name', 'c.name as course_name')
            ->orderBy('a.due_date', 'asc')
            ->limit($limit)
            ->get();

        return $assignments->toArray();
    }

    /**
     * Get overdue assignments for user
     */
    public function getOverdueAssignments(int $userId, int $limit = 10): array
    {
        $assignments = DB::table('elearning__assignments as a')
            ->join('elearning__lessons as l', 'a.lesson_id', '=', 'l.id')
            ->join('elearning__sections as s', 'l.section_id', '=', 's.id')
            ->join('elearning__courses as c', 's.course_id', '=', 'c.id')
            ->join('elearning__enrollments as e', 'c.id', '=', 'e.course_id')
            ->where('e.user_id', $userId)
            ->where('a.is_active', true)
            ->where('a.due_date', '<', now())
            ->where('a.allow_late_submission', true)
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('elearning__assignment_submissions')
                    ->whereColumn('assignment_id', 'a.id')
                    ->where('user_id', $userId);
            })
            ->select('a.*', 'l.name as lesson_name', 'c.name as course_name')
            ->orderBy('a.due_date', 'asc')
            ->limit($limit)
            ->get();

        return $assignments->toArray();
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

    /**
     * Check if user is enrolled in course
     */
    public function isUserEnrolled(int $userId, int $courseId): bool
    {
        return DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

    /**
     * Check if user owns course
     */
    public function doesUserOwnCourse(int $userId, int $courseId): bool
    {
        return DB::table('elearning__courses')
            ->where('id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }
}
