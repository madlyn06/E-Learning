<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\User;
use Newnet\Core\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    
    /**
     * Get teachers (users with role = teacher)
     * 
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTeachers($perPage = 20)
    {
        return $this->model
            ->where('is_teacher', true)
            ->paginate($perPage);
    }
    
    /**
     * Get students (users with role = student)
     * 
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getStudents($perPage = 20)
    {
        return $this->model
            ->where('is_teacher', false)
            ->paginate($perPage);
    }

    public function getInstructors(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->where('is_teacher', true)
            ->where('teacher_status', 'approved');

        if (isset($filters['category_id'])) {
            $query->whereHas('courses.categories', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        return $query->paginate($perPage);
    }

    public function searchInstructors(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('is_teacher', true)
            ->where('teacher_status', 'approved')
            ->where('name', 'like', "%{$query}%")
            ->paginate($perPage);
    }

    public function getPopularInstructors(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_teacher', true)
            ->where('teacher_status', 'approved')
            ->withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTeacherApplications(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('is_teacher', true)
            ->where('teacher_status', 'pending')
            ->paginate($perPage);
    }

    public function approveTeacherApplication(int $userId): bool
    {
        return $this->model->where('id', $userId)
            ->update(['teacher_status' => 'approved']) > 0;
    }

    public function rejectTeacherApplication(int $userId, string $reason): bool
    {
        return $this->model->where('id', $userId)
            ->update(['teacher_status' => 'rejected', 'rejection_reason' => $reason]) > 0;
    }

    public function getUserStatistics(int $userId): array
    {
        $enrollments = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->count();

        $completedCourses = DB::table('elearning__enrollments')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $totalTimeSpent = DB::table('elearning__tracking_lessons')
            ->where('user_id', $userId)
            ->sum('time_spent') ?? 0;

        return [
            'total_enrollments' => $enrollments,
            'completed_courses' => $completedCourses,
            'total_time_spent' => $totalTimeSpent,
            'completion_rate' => $enrollments > 0 ? round(($completedCourses / $enrollments) * 100, 2) : 0
        ];
    }

    public function getUserEnrolledCourses(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->find($userId)->enrollments()->with('course')->get();
    }

    public function getUserTaughtCourses(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->find($userId)->courses()->get();
    }
}
