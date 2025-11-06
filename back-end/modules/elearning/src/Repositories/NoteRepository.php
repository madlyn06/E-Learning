<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Note;
use Newnet\Core\Repositories\BaseRepository;

class NoteRepository extends BaseRepository
{
    public function __construct(Note $model)
    {
        parent::__construct($model);
    }
    
    /**
     * Get the model instance.
     *
     * @return \Modules\Elearning\Models\Note
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Get notes for a specific lesson
     * 
     * @param int $lessonId
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByLesson($lessonId, $perPage = 15)
    {
        return $this->model
            ->where('lesson_id', $lessonId)
            ->paginate($perPage);
    }
    
    /**
     * Get notes for a specific user
     * 
     * @param int $userId
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByUser($userId, $perPage = 15)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('lesson')
            ->paginate($perPage);
    }
    
    /**
     * Get notes for a specific user and lesson
     * 
     * @param int $userId
     * @param int $lessonId
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByUserAndLesson($userId, $lessonId, $perPage = 15)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->paginate($perPage);
    }
}
