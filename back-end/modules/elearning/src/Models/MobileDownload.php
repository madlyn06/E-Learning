<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property string $status
 * @property numeric $progress
 * @property int|null $total_size
 * @property int $downloaded_size
 * @property string|null $download_path
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course $course
 * @property-read float $progress_percentage
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereDownloadPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereDownloadedSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereTotalSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MobileDownload whereUserId($value)
 * @mixin \Eloquent
 */
class MobileDownload extends Model
{
    protected $table = 'elearning__mobile_downloads';

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress',
        'total_size',
        'downloaded_size',
        'download_path',
        'started_at',
        'completed_at',
        'error_message'
    ];

    protected $casts = [
        'progress' => 'decimal:2',
        'total_size' => 'integer',
        'downloaded_size' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
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
     * Scope for active downloads
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'downloading');
    }

    /**
     * Scope for completed downloads
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed downloads
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if download is active
     */
    public function isActive(): bool
    {
        return $this->status === 'downloading';
    }

    /**
     * Check if download is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if download failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get download progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->total_size > 0) {
            return round(($this->downloaded_size / $this->total_size) * 100, 2);
        }
        return 0;
    }
}
