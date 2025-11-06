<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property bool $push_notifications_enabled
 * @property bool $email_notifications_enabled
 * @property bool $auto_download_enabled
 * @property bool $offline_mode_enabled
 * @property string $download_quality
 * @property bool $auto_sync_enabled
 * @property int $sync_interval
 * @property array<array-key, mixed>|null $notification_preferences
 * @property array<array-key, mixed>|null $ui_preferences
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting autoDownloadEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting offlineModeEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting pushEnabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereAutoDownloadEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereAutoSyncEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereDownloadQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereEmailNotificationsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereNotificationPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereOfflineModeEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting wherePushNotificationsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereSyncInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereUiPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileAppSetting whereUserId($value)
 * @mixin \Eloquent
 */
class MobileAppSetting extends Model
{
    protected $table = 'elearning__mobile_app_settings';

    protected $fillable = [
        'user_id',
        'push_notifications_enabled',
        'email_notifications_enabled',
        'auto_download_enabled',
        'offline_mode_enabled',
        'download_quality',
        'auto_sync_enabled',
        'sync_interval',
        'notification_preferences',
        'ui_preferences'
    ];

    protected $casts = [
        'push_notifications_enabled' => 'boolean',
        'email_notifications_enabled' => 'boolean',
        'auto_download_enabled' => 'boolean',
        'offline_mode_enabled' => 'boolean',
        'auto_sync_enabled' => 'boolean',
        'sync_interval' => 'integer',
        'notification_preferences' => 'array',
        'ui_preferences' => 'array'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for users with push notifications enabled
     */
    public function scopePushEnabled($query)
    {
        return $query->where('push_notifications_enabled', true);
    }

    /**
     * Scope for users with auto-download enabled
     */
    public function scopeAutoDownloadEnabled($query)
    {
        return $query->where('auto_download_enabled', true);
    }

    /**
     * Scope for users with offline mode enabled
     */
    public function scopeOfflineModeEnabled($query)
    {
        return $query->where('offline_mode_enabled', true);
    }

    /**
     * Get download quality options
     */
    public static function getDownloadQualities(): array
    {
        return [
            'low' => 'Low (480p)',
            'medium' => 'Medium (720p)',
            'high' => 'High (1080p)',
            'ultra' => 'Ultra (4K)'
        ];
    }

    /**
     * Get sync interval options
     */
    public static function getSyncIntervals(): array
    {
        return [
            300 => '5 minutes',
            900 => '15 minutes',
            1800 => '30 minutes',
            3600 => '1 hour',
            7200 => '2 hours',
            86400 => '1 day'
        ];
    }
}
