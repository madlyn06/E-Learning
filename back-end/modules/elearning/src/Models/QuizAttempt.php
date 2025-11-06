<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string $status
 * @property int|null $score
 * @property int|null $max_score
 * @property numeric|null $percentage
 * @property bool|null $passed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\QuizAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \Modules\Elearning\Models\Quiz $quiz
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt passed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt wherePassed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereUserId($value)
 * @mixin \Eloquent
 */
class QuizAttempt extends Model
{
    protected $table = 'elearning__quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'completed_at',
        'status',
        'score',
        'max_score',
        'percentage',
        'passed'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'integer',
        'max_score' => 'integer',
        'percentage' => 'decimal:2',
        'passed' => 'boolean'
    ];

    /**
     * Get the quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get quiz answers for this attempt
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    /**
     * Scope for completed attempts
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for passed attempts
     */
    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    /**
     * Scope for failed attempts
     */
    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    /**
     * Check if attempt is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if attempt is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
