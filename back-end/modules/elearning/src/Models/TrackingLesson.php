<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Module\Elearning\Models\TrackingLesson
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $lesson_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Module\Elearning\Models\Lesson|null $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLesson whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrackingLesson whereCourseId($value)
 * @mixin \Eloquent
 */
class TrackingLesson extends Model
{
  use HasFactory;

  protected $table = 'elearning__tracking_lessons';

  protected $fillable = [
    'course_id',
    'user_id',
    'lesson_id',
  ];

  public function lesson()
  {
    return $this->belongsTo(Lesson::class);
  }
}
