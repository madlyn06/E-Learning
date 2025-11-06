<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Module\Elearning\Models\Requirement
 *
 * @property int $id
 * @property int $course_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Requirement extends Model
{
    use HasFactory;

    protected $table = 'elearning__requirements';

    protected $fillable = [
        'course_id',
        'content',
    ];
}
