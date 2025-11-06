<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $session_id
 * @property int $user_id
 * @property string $message
 * @property string $type
 * @property bool $is_private
 * @property int|null $reply_to_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LiveSessionChat> $replies
 * @property-read int|null $replies_count
 * @property-read LiveSessionChat|null $replyTo
 * @property-read \Modules\Elearning\Models\LiveSession $session
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat private()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereReplyToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionChat whereUserId($value)
 * @mixin \Eloquent
 */
class LiveSessionChat extends Model
{
    protected $table = 'elearning__live_session_chat';

    protected $fillable = [
        'session_id',
        'user_id',
        'message',
        'type',
        'is_private',
        'reply_to_id'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    /**
     * Get the live session
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class, 'session_id');
    }

    /**
     * Get the user who sent the message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the message this is replying to
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(LiveSessionChat::class, 'reply_to_id');
    }

    /**
     * Get replies to this message
     */
    public function replies()
    {
        return $this->hasMany(LiveSessionChat::class, 'reply_to_id');
    }

    /**
     * Scope for public messages
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope for private messages
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope for messages by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get message types
     */
    public static function getTypes(): array
    {
        return [
            'text' => 'Text',
            'file' => 'File',
            'reaction' => 'Reaction',
            'system' => 'System'
        ];
    }
}
