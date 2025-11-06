<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $notification_type
 * @property bool $email_enabled
 * @property bool $push_enabled
 * @property bool $sms_enabled
 * @property bool $in_app_enabled
 * @property string|null $frequency_settings
 * @property string|null $time_settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $quiet_hours_start
 * @property mixed $quiet_hours_end
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference enabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereEmailEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereFrequencySettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereInAppEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference wherePushEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereSmsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereTimeSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationPreference whereUserId($value)
 * @mixin \Eloquent
 */
class NotificationPreference extends Model
{
    protected $table = 'elearning__notification_preferences';

    protected $fillable = [
        'user_id',
        'notification_type',
        'email_enabled',
        'push_enabled',
        'sms_enabled',
        'in_app_enabled',
        'frequency',
        'quiet_hours_start',
        'quiet_hours_end'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
        'quiet_hours_start' => 'time',
        'quiet_hours_end' => 'time'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for enabled preferences
     */
    public function scopeEnabled($query)
    {
        return $query->where(function($q) {
            $q->where('email_enabled', true)
              ->orWhere('push_enabled', true)
              ->orWhere('sms_enabled', true)
              ->orWhere('in_app_enabled', true);
        });
    }

    /**
     * Scope for preferences by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Get frequency options
     */
    public static function getFrequencies(): array
    {
        return [
            'immediate' => 'Immediate',
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ];
    }

    /**
     * Check if any notification method is enabled
     */
    public function isAnyEnabled(): bool
    {
        return $this->email_enabled || 
               $this->push_enabled || 
               $this->sms_enabled || 
               $this->in_app_enabled;
    }

    /**
     * Check if quiet hours are active
     */
    public function isQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now();
        $start = $this->quiet_hours_start;
        $end = $this->quiet_hours_end;

        // Handle overnight quiet hours
        if ($start > $end) {
            return $now->format('H:i:s') >= $start || $now->format('H:i:s') <= $end;
        }

        return $now->format('H:i:s') >= $start && $now->format('H:i:s') <= $end;
    }
}
