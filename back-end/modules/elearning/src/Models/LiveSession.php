<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $course_id
 * @property int $instructor_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property string $status
 * @property string|null $meeting_url
 * @property string|null $meeting_id
 * @property string|null $meeting_password
 * @property int|null $max_participants
 * @property bool $is_recording_enabled
 * @property bool $is_chat_enabled
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSessionChat> $chatMessages
 * @property-read int|null $chat_messages_count
 * @property-read \Modules\Elearning\Models\Course $course
 * @property-read \Modules\Elearning\Models\User $instructor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSessionParticipant> $participants
 * @property-read int|null $participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSessionRecording> $recordings
 * @property-read int|null $recordings_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereIsChatEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereIsRecordingEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereMaxParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereMeetingPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereMeetingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSession whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LiveSession extends Model
{
    protected $table = 'elearning__live_sessions';

    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'scheduled_at',
        'started_at',
        'ended_at',
        'status',
        'meeting_url',
        'meeting_id',
        'meeting_password',
        'max_participants',
        'is_recording_enabled',
        'is_chat_enabled',
        'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'max_participants' => 'integer',
        'is_recording_enabled' => 'boolean',
        'is_chat_enabled' => 'boolean'
    ];

    /**
     * Get the course
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the instructor
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get session participants
     */
    public function participants(): HasMany
    {
        return $this->hasMany(LiveSessionParticipant::class, 'session_id');
    }

    /**
     * Get session chat messages
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(LiveSessionChat::class, 'session_id');
    }

    /**
     * Get session recordings
     */
    public function recordings(): HasMany
    {
        return $this->hasMany(LiveSessionRecording::class, 'session_id');
    }

    /**
     * Scope for upcoming sessions
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
            ->where('status', 'scheduled');
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed sessions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if session is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if session is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if session is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
