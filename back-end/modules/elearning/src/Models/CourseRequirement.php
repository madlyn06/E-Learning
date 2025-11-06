<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $course_id
 * @property string $requirement
 * @property int $display_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement whereRequirement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseRequirement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseRequirement extends Model
{
    protected $table = 'elearning__course_requirements';

    protected $fillable = [
        'course_id',
        'requirement',
        'display_order',
    ];

    /**
     * Get the course that owns the requirement
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
