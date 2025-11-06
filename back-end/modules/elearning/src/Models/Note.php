<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Module\Elearning\Models\Note
 *
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property string $content
 * @property string $time_iso
 * @property string $time_seconds
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Lesson|null $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereTimeIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereTimeSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUserId($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
  use HasFactory;

  protected $table = 'elearning__notes';

  protected $fillable = [
    'lesson_id',
    'user_id',
    'content',
    'time_iso',
    'time_seconds',
  ];

  public function lesson()
  {
    return $this->belongsTo(Lesson::class);
  }
}
