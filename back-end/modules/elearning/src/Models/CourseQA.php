<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \Modules\Elearning\Models\Course|null $course
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQA newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQA newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQA query()
 * @mixin \Eloquent
 */
class CourseQA extends Model
{
    protected $table = 'elearning__course_qa';

    protected $fillable = [
        'course_id',
        'question',
        'answer',
        'display_order',
    ];

    /**
     * Get the course that owns the Q&A
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
