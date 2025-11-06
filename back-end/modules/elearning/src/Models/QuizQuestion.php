<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $quiz_id
 * @property string $question
 * @property string $type
 * @property array<array-key, mixed>|null $options
 * @property string|null $correct_answer
 * @property int $points
 * @property string|null $explanation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\QuizAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \Modules\Elearning\Models\Quiz $quiz
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizQuestion extends Model
{
    protected $table = 'elearning__quiz_questions';

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'points',
        'explanation'
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer'
    ];

    /**
     * Get the quiz that owns this question
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Get quiz answers for this question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }

    /**
     * Scope for questions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get question types
     */
    public static function getTypes(): array
    {
        return [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'fill_blank' => 'Fill in the Blank',
            'essay' => 'Essay'
        ];
    }
}
