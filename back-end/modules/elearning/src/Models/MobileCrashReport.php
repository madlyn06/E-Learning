<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $device_id
 * @property string $app_version
 * @property string $os_version
 * @property string|null $device_model
 * @property string $crash_log
 * @property string|null $stack_trace
 * @property string|null $crash_type
 * @property array<array-key, mixed>|null $user_actions
 * @property array<array-key, mixed>|null $device_info
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport byAppVersion($version)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport resolved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereCrashLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereCrashType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereDeviceInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereDeviceModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereStackTrace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereUserActions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileCrashReport whereUserId($value)
 * @mixin \Eloquent
 */
class MobileCrashReport extends Model
{
    protected $table = 'elearning__mobile_crash_reports';

    protected $fillable = [
        'user_id',
        'device_id',
        'app_version',
        'os_version',
        'device_model',
        'crash_log',
        'stack_trace',
        'crash_type',
        'user_actions',
        'device_info',
        'status',
        'notes'
    ];

    protected $casts = [
        'device_info' => 'array',
        'user_actions' => 'array'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for pending crash reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for resolved crash reports
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope for crash reports by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('crash_type', $type);
    }

    /**
     * Scope for crash reports by app version
     */
    public function scopeByAppVersion($query, $version)
    {
        return $query->where('app_version', $version);
    }

    /**
     * Get crash types
     */
    public static function getCrashTypes(): array
    {
        return [
            'crash' => 'Application Crash',
            'freeze' => 'Application Freeze',
            'memory_leak' => 'Memory Leak',
            'performance' => 'Performance Issue',
            'ui_issue' => 'UI/UX Issue'
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending Review',
            'investigating' => 'Under Investigation',
            'resolved' => 'Resolved',
            'wont_fix' => 'Won\'t Fix',
            'duplicate' => 'Duplicate'
        ];
    }

    /**
     * Check if crash report is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if crash report is resolved
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }
}
