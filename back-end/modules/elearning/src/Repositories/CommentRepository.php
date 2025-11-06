<?php

namespace Modules\Elearning\Repositories;

use Modules\Elearning\Models\Comment;
use Newnet\Core\Repositories\BaseRepository;

class CommentRepository extends BaseRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }
    
    /**
     * Get the model instance.
     *
     * @return \Modules\Elearning\Models\Comment
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Get comments for a specific lesson
     * 
     * @param int $lessonId
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByLesson($lessonId, $perPage = 15)
    {
        return $this->model
            ->where('lesson_id', $lessonId)
            ->with(['user'])
            ->paginate($perPage);
    }
    
    /**
     * Get top-level comments (no parent)
     * 
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTopLevelComments($perPage = 15)
    {
        return $this->model
            ->whereNull('parent_id')
            ->with(['user', 'lesson'])
            ->paginate($perPage);
    }
    
    /**
     * Get comments by spam status
     * 
     * @param bool $isSpam
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getBySpamStatus($isSpam = true, $perPage = 15)
    {
        return $this->model
            ->where('is_spam', $isSpam)
            ->with(['user', 'lesson'])
            ->paginate($perPage);
    }
}
