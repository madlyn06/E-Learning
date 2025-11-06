<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $assignment_id
 * @property int $user_id
 * @property string|null $submission_text
 * @property string|null $file_path
 * @property string|null $file_name
 * @property int|null $file_size
 * @property string|null $file_type
 * @property string $status
 * @property int|null $score
 * @property int|null $max_score
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property int|null $graded_by
 * @property int $attempt_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Assignment $assignment
 * @property-read \Modules\Elearning\Models\User|null $grader
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission graded()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission submitted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereAttemptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmissionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUserId($value)
 * @mixin \Eloquent
 */
class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = 'elearning__assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'user_id',
        'content',
        'attachment_path',
        'submitted_at',
        'score',
        'feedback',
        'status',
        'graded_by',
        'graded_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score' => 'integer',
        'graded_at' => 'datetime'
    ];

    /**
     * Get the assignment that owns the submission
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the user who submitted the assignment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who graded the submission
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Scope a query to only include submitted submissions
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope a query to only include graded submissions
     */
    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    /**
     * Check if the submission has been graded
     */
    public function isGraded(): bool
    {
        return $this->status === 'graded';
    }

    /**
     * Check if the submission is late
     */
    public function isLate(): bool
    {
        return $this->submitted_at->isAfter($this->assignment->due_date);
    }
}
