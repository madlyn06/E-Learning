<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $lesson_id
 * @property string $question
 * @property array<array-key, mixed> $choices
 * @property int $correct
 * @property string|null $explanation
 * @property int $display_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Lesson $lesson
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereChoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    protected $table = 'elearning__questions';

    protected $fillable = [
        'lesson_id',
        'question',
        'choices',
        'correct',
        'explanation',
        'display_order',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    /**
     * Get the lesson that owns the question
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
