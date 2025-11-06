<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $course_id
 * @property string $purpose
 * @property int $display_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoursePurpose whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CoursePurpose extends Model
{
    protected $table = 'elearning__course_purposes';

    protected $fillable = [
        'course_id',
        'purpose',
        'display_order',
    ];

    /**
     * Get the course that owns the purpose
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
