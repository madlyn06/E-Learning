<?php

namespace Modules\Elearning\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Elearning\Models\Assignment;
use Newnet\Core\Repositories\BaseRepository;

class AssignmentRepository extends BaseRepository
{
    public function __construct(Assignment $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?Assignment
    {
        return $this->model->find($id);
    }

    public function getByLessonId(int $lessonId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('lesson_id', $lessonId)
            ->where('is_active', true)
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getByCourseId(int $courseId, int $perPage = 20): LengthAwarePaginator
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

    public function create(array $data): Assignment
    {
        return $this->model->create($data);
    }

    public function updateAssignment(int $id, array $data): bool
    {
        $assignment = $this->model->find($id);
        if (!$assignment) {
            return false;
        }
        return $assignment->update($data);
    }

    public function deleteAssignment(int $id): bool
    {
        $assignment = $this->model->find($id);
        if (!$assignment) {
            return false;
        }
        return $assignment->update(['is_active' => false]);
    }

    public function getUpcomingForUser(int $userId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return DB::table('elearning__assignments as a')
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
    }

    public function getOverdueForUser(int $userId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return DB::table('elearning__assignments as a')
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
    }
}
