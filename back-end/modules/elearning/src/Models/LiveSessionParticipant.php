<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $session_id
 * @property int $user_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $joined_at
 * @property \Illuminate\Support\Carbon|null $left_at
 * @property bool $is_muted
 * @property bool $is_video_off
 * @property bool $is_screen_sharing
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\LiveSession $session
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant byRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereIsMuted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereIsScreenSharing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereIsVideoOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereJoinedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereLeftAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionParticipant whereUserId($value)
 * @mixin \Eloquent
 */
class LiveSessionParticipant extends Model
{
    protected $table = 'elearning__live_session_participants';

    protected $fillable = [
        'session_id',
        'user_id',
        'role',
        'joined_at',
        'left_at',
        'is_muted',
        'is_video_off',
        'is_screen_sharing'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_video_off' => 'boolean',
        'is_screen_sharing' => 'boolean'
    ];

    /**
     * Get the live session
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class, 'session_id');
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for active participants
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('joined_at')
            ->whereNull('left_at');
    }

    /**
     * Scope for participants by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Check if participant is active
     */
    public function isActive(): bool
    {
        return $this->joined_at && !$this->left_at;
    }

    /**
     * Get participant roles
     */
    public static function getRoles(): array
    {
        return [
            'host' => 'Host',
            'co-host' => 'Co-Host',
            'participant' => 'Participant',
            'viewer' => 'Viewer'
        ];
    }
}
