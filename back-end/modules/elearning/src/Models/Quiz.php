<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $lesson_id
 * @property string $title
 * @property string|null $description
 * @property int|null $time_limit
 * @property int $passing_score
 * @property bool $is_active
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\QuizAttempt> $attempts
 * @property-read int|null $attempts_count
 * @property-read \Modules\Elearning\Models\User $creator
 * @property-read \Modules\Elearning\Models\Lesson $lesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\QuizQuestion> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz wherePassingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTimeLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Quiz extends Model
{
    protected $table = 'elearning__quizzes';

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'time_limit',
        'passing_score',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'time_limit' => 'integer',
        'passing_score' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the lesson that owns this quiz
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    /**
     * Get the user who created this quiz
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get quiz questions
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    /**
     * Get quiz attempts
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }

    /**
     * Scope for active quizzes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user has passed this quiz
     */
    public function hasUserPassed($userId): bool
    {
        $attempt = $this->attempts()
            ->where('user_id', $userId)
            ->where('passed', true)
            ->latest()
            ->first();

        return $attempt !== null;
    }
}
