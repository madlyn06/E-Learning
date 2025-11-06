<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $lesson_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string|null $instructions
 * @property string $type
 * @property int $max_score
 * @property int $max_attempts
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $start_date
 * @property bool $is_active
 * @property int $allow_late_submissions
 * @property int $late_penalty_percentage
 * @property array<array-key, mixed>|null $rubric
 * @property string|null $attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\User|null $creator
 * @property-read int $submission_count
 * @property-read \Modules\Elearning\Models\Lesson $lesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\AssignmentSubmission> $submissions
 * @property-read int|null $submissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment open()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereAllowLateSubmissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereLatePenaltyPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereRubric($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereUserId($value)
 * @mixin \Eloquent
 */
class Assignment extends Model
{
    use HasFactory;

    protected $table = 'elearning__assignments';

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'attachment_path',
        'rubric',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_score' => 'integer',
        'rubric' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the lesson that owns the assignment
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the user who created the assignment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the submissions for this assignment
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * Scope a query to only include active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include assignments that are still open
     */
    public function scopeOpen($query)
    {
        return $query->where('due_date', '>', now());
    }

    /**
     * Check if the assignment is still open for submissions
     */
    public function isOpen(): bool
    {
        return now()->isBefore($this->due_date);
    }

    /**
     * Get the submission count for this assignment
     */
    public function getSubmissionCountAttribute(): int
    {
        return $this->submissions()->count();
    }
}
