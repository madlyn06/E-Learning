<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property bool $email_enabled
 * @property bool $push_enabled
 * @property bool $sms_enabled
 * @property bool $in_app_enabled
 * @property array<array-key, mixed>|null $email_preferences
 * @property array<array-key, mixed>|null $push_preferences
 * @property array<array-key, mixed>|null $sms_preferences
 * @property array<array-key, mixed>|null $in_app_preferences
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting emailEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting inAppEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting pushEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting smsEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereEmailEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereEmailPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereInAppEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereInAppPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting wherePushEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting wherePushPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereSmsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereSmsPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationSetting whereUserId($value)
 * @mixin \Eloquent
 */
class NotificationSetting extends Model
{
    protected $table = 'elearning__notification_settings';

    protected $fillable = [
        'user_id',
        'email_enabled',
        'push_enabled',
        'sms_enabled',
        'in_app_enabled',
        'email_preferences',
        'push_preferences',
        'sms_preferences',
        'in_app_preferences'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
        'email_preferences' => 'array',
        'push_preferences' => 'array',
        'sms_preferences' => 'array',
        'in_app_preferences' => 'array'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for users with email notifications enabled
     */
    public function scopeEmailEnabled($query)
    {
        return $query->where('email_enabled', true);
    }

    /**
     * Scope for users with push notifications enabled
     */
    public function scopePushEnabled($query)
    {
        return $query->where('push_enabled', true);
    }

    /**
     * Scope for users with SMS notifications enabled
     */
    public function scopeSmsEnabled($query)
    {
        return $query->where('sms_enabled', true);
    }

    /**
     * Scope for users with in-app notifications enabled
     */
    public function scopeInAppEnabled($query)
    {
        return $query->where('in_app_enabled', true);
    }

    /**
     * Check if email notifications are enabled
     */
    public function isEmailEnabled(): bool
    {
        return $this->email_enabled;
    }

    /**
     * Check if push notifications are enabled
     */
    public function isPushEnabled(): bool
    {
        return $this->push_enabled;
    }

    /**
     * Check if SMS notifications are enabled
     */
    public function isSmsEnabled(): bool
    {
        return $this->sms_enabled;
    }

    /**
     * Check if in-app notifications are enabled
     */
    public function isInAppEnabled(): bool
    {
        return $this->in_app_enabled;
    }

    /**
     * Get notification types
     */
    public static function getNotificationTypes(): array
    {
        return [
            'course_updates' => 'Course Updates',
            'lesson_completions' => 'Lesson Completions',
            'assignments' => 'Assignments',
            'discussions' => 'Discussions',
            'live_sessions' => 'Live Sessions',
            'payments' => 'Payments',
            'system' => 'System Notifications',
            'marketing' => 'Marketing'
        ];
    }
}
