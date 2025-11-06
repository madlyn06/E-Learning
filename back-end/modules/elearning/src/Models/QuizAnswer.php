<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $attempt_id
 * @property int $question_id
 * @property string $user_answer
 * @property bool $is_correct
 * @property int $points_earned
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\QuizAttempt $attempt
 * @property-read \Modules\Elearning\Models\QuizQuestion $question
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer correct()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer incorrect()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer wherePointsEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAnswer whereUserAnswer($value)
 * @mixin \Eloquent
 */
class QuizAnswer extends Model
{
    protected $table = 'elearning__quiz_answers';

    protected $fillable = [
        'attempt_id',
        'question_id',
        'user_answer',
        'is_correct',
        'points_earned',
        'feedback'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'integer'
    ];

    /**
     * Get the quiz attempt
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    /**
     * Get the quiz question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    /**
     * Scope for correct answers
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope for incorrect answers
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }
}
