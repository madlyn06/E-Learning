<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Category;
use Newnet\Core\Repositories\BaseRepository;
use Newnet\Core\Repositories\NestedRepositoryInterface;
use Newnet\Core\Repositories\NestedRepositoryTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository implements NestedRepositoryInterface
{
    use NestedRepositoryTrait;

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getTree($columns = ['*'])
    {
        $columns = array_merge($columns, [
            'parent_id',
            '_lft',
            '_rgt'
        ]);

        return $this->model
            ->defaultOrder()
            ->get($columns)
            ->toTree();
    }

    public function create(array $data)
    {
        $model = parent::create($data);

        $this->model->fixTree();

        return $model;
    }

    public function updateById(array $data, $id)
    {
        $model = parent::updateById($data, $id);

        $this->model->fixTree();

        return $model;
    }

    public function findBySlug($slug)
    {
        return $this->model
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function findByIds(array $ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function getWithCourseCount(): Collection
    {
        return $this->model->withCount('courses')->where('is_active', true)->get();
    }

    public function getPopularCategories(int $limit = 10): Collection
    {
        return $this->model->withCount('courses')
            ->where('is_active', true)
            ->orderBy('courses_count', 'desc')
            ->limit($limit)
            ->get();
    }


    public function paginateTree($itemPerPage)
    {
        $data = $this->model->query();

        if ($name = request('name')) {
            $data->where(function ($q) use ($name) {
                foreach (explode(' ', $name) as $keyword) {
                    if ($keyword = trim($keyword)) {
                        $q->where('name', 'like', "%{$keyword}%");
                    }
                }
            });
        }

        return $data
            ->withDepth()
            ->defaultOrder()
            ->paginate($itemPerPage);
    }

    public function listRoot()
    {
        return Category::defaultOrder()->whereIsRoot()->get();
    }

    public function getCategoryStatistics(int $categoryId): array
    {
        $totalCourses = DB::table('elearning__courses as c')
            ->join('elearning__category_course as cc', 'c.id', '=', 'cc.course_id')
            ->where('cc.category_id', $categoryId)
            ->where('c.is_published', true)
            ->count();

        $totalStudents = DB::table('elearning__enrollments as e')
            ->join('elearning__courses as c', 'e.course_id', '=', 'c.id')
            ->join('elearning__category_course as cc', 'c.id', '=', 'cc.course_id')
            ->where('cc.category_id', $categoryId)
            ->distinct('e.user_id')
            ->count('e.user_id');

        $totalInstructors = DB::table('elearning__courses as c')
            ->join('elearning__category_course as cc', 'c.id', '=', 'cc.course_id')
            ->join('elearning__users as u', 'c.user_id', '=', 'u.id')
            ->where('cc.category_id', $categoryId)
            ->where('u.is_teacher', true)
            ->where('u.teacher_status', 'approved')
            ->distinct('u.id')
            ->count('u.id');

        return [
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'total_instructors' => $totalInstructors,
            'last_updated' => now()
        ];
    }
}
