<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $course_id
 * @property int|null $lesson_id
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property int $duration_seconds
 * @property string $device_type
 * @property string|null $app_version
 * @property string|null $os_version
 * @property array<array-key, mixed>|null $session_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course|null $course
 * @property-read \Modules\Elearning\Models\Lesson|null $lesson
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession byDeviceType($deviceType)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereDurationSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereSessionData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileLearningSession whereUserId($value)
 * @mixin \Eloquent
 */
class MobileLearningSession extends Model
{
    protected $table = 'elearning__mobile_learning_sessions';

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'device_type',
        'app_version',
        'os_version',
        'session_data'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_seconds' => 'integer',
        'session_data' => 'array'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the lesson
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('started_at')
            ->whereNull('ended_at');
    }

    /**
     * Scope for completed sessions
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('started_at')
            ->whereNotNull('ended_at');
    }

    /**
     * Scope for sessions by device type
     */
    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    /**
     * Get device types
     */
    public static function getDeviceTypes(): array
    {
        return [
            'ios' => 'iOS',
            'android' => 'Android',
            'web' => 'Web'
        ];
    }

    /**
     * Calculate session duration
     */
    public function calculateDuration(): int
    {
        if ($this->started_at && $this->ended_at) {
            return $this->started_at->diffInSeconds($this->ended_at);
        }
        return 0;
    }

    /**
     * Check if session is active
     */
    public function isActive(): bool
    {
        return $this->started_at && !$this->ended_at;
    }

    /**
     * Check if session is completed
     */
    public function isCompleted(): bool
    {
        return $this->started_at && $this->ended_at;
    }
}
