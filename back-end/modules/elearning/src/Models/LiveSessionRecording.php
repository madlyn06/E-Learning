<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $session_id
 * @property string $recording_url
 * @property string|null $recording_id
 * @property string $file_name
 * @property string $file_path
 * @property string $file_size
 * @property string $duration
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\LiveSession $session
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording processing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereRecordingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereRecordingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LiveSessionRecording whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LiveSessionRecording extends Model
{
    protected $table = 'elearning__live_session_recordings';

    protected $fillable = [
        'session_id',
        'recording_url',
        'recording_id',
        'file_name',
        'file_path',
        'file_size',
        'duration',
        'status',
        'notes'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'duration' => 'integer'
    ];

    /**
     * Get the live session
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class, 'session_id');
    }

    /**
     * Scope for completed recordings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for processing recordings
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for failed recordings
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if recording is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if recording is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if recording failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeAttribute($value): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $value;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get duration in human readable format
     */
    public function getDurationAttribute($value): string
    {
        $hours = floor($value / 3600);
        $minutes = floor(($value % 3600) / 60);
        $seconds = $value % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
