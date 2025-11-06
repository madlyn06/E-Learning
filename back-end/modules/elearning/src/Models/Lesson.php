<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Newnet\Media\Traits\HasMediaTrait;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

/**
 * @property int $id
 * @property string $uuid
 * @property array<array-key, mixed> $name
 * @property string $type
 * @property string|null $slug
 * @property int $section_id
 * @property int $user_id
 * @property array<array-key, mixed>|null $summary
 * @property array<array-key, mixed>|null $content
 * @property string|null $lesson_purpose
 * @property bool $is_free
 * @property int $is_enabled
 * @property string|null $video_id
 * @property int|null $continue_id
 * @property int|null $previous_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $display_order
 * @property string|null $duration_minutes
 * @property string|null $metadata
 * @property string|null $original_name
 * @property string|null $size
 * @property string|null $extension
 * @property string|null $mime_type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Kalnoy\Nestedset\Collection<int, \Modules\Elearning\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property mixed $documents
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MobileLearningSession> $mobileLearningSessions
 * @property-read int|null $mobile_learning_sessions_count
 * @property-read Lesson|null $nextLesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read Lesson|null $previousLesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read \Modules\Elearning\Models\Section $section
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\TrackingLesson> $trackingLessons
 * @property-read int|null $tracking_lessons_count
 * @property-read mixed $translations
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson document()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson enabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson free()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson quiz()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson video()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereContinueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereIsFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereLessonPurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson wherePreviousId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereVideoId($value)
 * @mixin \Eloquent
 */
class Lesson extends Model
{
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;

    protected $table = 'elearning__lessons';

    protected $fillable = [
        'uuid',
        'name',
        'type',
        'section_id',
        'user_id',
        'summary',
        'content',
        'lesson_purpose',
        'is_free',
        'is_enabled',
        'video_id',
        'continue_id',
        'previous_id',
        'display_order',
        'duration_minutes',
        'metadata',
        'documents',
        'original_name',
        'size',
        'extension',
        'mime_type',
        'hls_master_playlist',
        'hls_1080p_path',
        'hls_720p_path',
        'hls_480p_path',
    ];

    public $translatable = [
        'name',
        'summary',
        'content',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_enabled' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function setDocumentsAttribute($value)
    {
        $this->mediaAttributes['documents'] = $value;
    }

    public function getDocumentsAttribute()
    {
        return $this->getMedia('documents');
    }

    /**
     * Get the URL for the lesson
     */
    public function getUrl()
    {
        return route('elearning.web.lesson.detail', $this->id);
    }

    /**
     * Get HLS streaming URLs for different qualities
     */
    public function getHlsUrls(): array
    {
        $baseUrl = config('app.url') . '/storage/app/hls/' . $this->id . '/';

        return [
            'master' => $this->hls_master_playlist ? $baseUrl . $this->hls_master_playlist : null,
            '1080p' => $this->hls_1080p_path ? $baseUrl . $this->hls_1080p_path : null,
            '720p' => $this->hls_720p_path ? $baseUrl . $this->hls_720p_path : null,
            '480p' => $this->hls_480p_path ? $baseUrl . $this->hls_480p_path : null,
        ];
    }

    /**
     * Check if HLS conversion is completed
     */
    public function isHlsConverted(): bool
    {
        return !empty($this->hls_master_playlist) &&
            !empty($this->hls_1080p_path) &&
            !empty($this->hls_720p_path) &&
            !empty($this->hls_480p_path);
    }

    /**
     * Get available HLS qualities
     */
    public function getAvailableHlsQualities(): array
    {
        $qualities = [];

        if (!empty($this->hls_1080p_path)) $qualities[] = '1080p';
        if (!empty($this->hls_720p_path)) $qualities[] = '720p';
        if (!empty($this->hls_480p_path)) $qualities[] = '480p';

        return $qualities;
    }

    /**
     * Get video attribute with multi-bitrate HLS support
     */
    public function getVideoAttribute()
    {
        if ($this->type !== 'video') {
            return null;
        }

        // Check if HLS conversion is completed
        if ($this->isHlsConverted()) {
            return [
                'type' => 'hls',
                'hls_enabled' => true,
                'multi_bitrate' => true,
                'master_playlist' => $this->getHlsMasterPlaylistUrl(),
                'qualities' => $this->getHlsQualitiesWithUrls(),
                'fallback' => $this->getVideoFallbackUrl(),
                'metadata' => [
                    'duration' => $this->duration_minutes,
                    'original_name' => $this->original_name,
                    'size' => $this->size,
                    'mime_type' => $this->mime_type,
                    'extension' => $this->extension,
                ]
            ];
        }

        // Fallback to original video if HLS not ready
        return [
            'type' => 'direct',
            'hls_enabled' => false,
            'multi_bitrate' => false,
            'url' => $this->getVideoFallbackUrl(),
            'metadata' => [
                'duration' => $this->duration_minutes,
                'original_name' => $this->original_name,
                'size' => $this->size,
                'mime_type' => $this->mime_type,
                'extension' => $this->extension,
            ]
        ];
    }

    /**
     * Get HLS master playlist URL
     */
    public function getHlsMasterPlaylistUrl(): ?string
    {
        if (empty($this->hls_master_playlist)) {
            return null;
        }

        return config('app.url') . '/storage/app/hls/' . $this->id . '/' . $this->hls_master_playlist;
    }

    /**
     * Get HLS qualities with URLs and metadata
     */
    public function getHlsQualitiesWithUrls(): array
    {
        $qualities = [];
        $baseUrl = config('app.url') . '/storage/app/hls/' . $this->id . '/';

        if (!empty($this->hls_1080p_path)) {
            $qualities['1080p'] = [
                'url' => $baseUrl . $this->hls_1080p_path,
                'width' => 1920,
                'height' => 1080,
                'bitrate' => '5000k',
                'audio_bitrate' => '128k',
                'bandwidth' => 5128000,
                'codecs' => 'avc1.640028,mp4a.40.2'
            ];
        }

        if (!empty($this->hls_720p_path)) {
            $qualities['720p'] = [
                'url' => $baseUrl . $this->hls_720p_path,
                'width' => 1280,
                'height' => 720,
                'bitrate' => '2800k',
                'audio_bitrate' => '128k',
                'bandwidth' => 2928000,
                'codecs' => 'avc1.640028,mp4a.40.2'
            ];
        }

        if (!empty($this->hls_480p_path)) {
            $qualities['480p'] = [
                'url' => $baseUrl . $this->hls_480p_path,
                'width' => 854,
                'height' => 480,
                'bitrate' => '1400k',
                'audio_bitrate' => '96k',
                'bandwidth' => 1496000,
                'codecs' => 'avc1.640028,mp4a.40.2'
            ];
        }

        return $qualities;
    }

    /**
     * Get video fallback URL (original video)
     */
    public function getVideoFallbackUrl(): ?string
    {
        if (empty($this->video_id)) {
            return null;
        }

        return config('app.url') . '/storage/app/' . $this->video_id;
    }

    /**
     * Get recommended quality based on network conditions
     */
    public function getRecommendedQuality(string $networkType = 'auto'): string
    {
        $availableQualities = $this->getAvailableHlsQualities();

        if (empty($availableQualities)) {
            return 'auto';
        }

        switch ($networkType) {
            case 'slow':
            case '2g':
            case '3g':
                return in_array('480p', $availableQualities) ? '480p' : $availableQualities[0];

            case 'medium':
            case '4g':
                return in_array('720p', $availableQualities) ? '720p' : $availableQualities[0];

            case 'fast':
            case '5g':
            case 'wifi':
                return in_array('1080p', $availableQualities) ? '1080p' : $availableQualities[0];

            case 'auto':
            default:
                // Return the middle quality as default
                if (count($availableQualities) >= 2) {
                    return $availableQualities[1]; // Usually 720p
                }
                return $availableQualities[0];
        }
    }

    /**
     * Get the section that owns the lesson
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user that owns the lesson
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the questions for the lesson (for quiz type)
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the next lesson
     */
    public function nextLesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'continue_id');
    }

    /**
     * Get the previous lesson
     */
    public function previousLesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'previous_id');
    }

    /**
     * Get the notes for the lesson
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the comments for the lesson
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the quizzes for the lesson
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the assignments for the lesson
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the tracking lessons for the lesson
     */
    public function trackingLessons(): HasMany
    {
        return $this->hasMany(TrackingLesson::class);
    }

    /**
     * Get the mobile learning sessions for the lesson
     */
    public function mobileLearningSessions(): HasMany
    {
        return $this->hasMany(MobileLearningSession::class);
    }

    /**
     * Scope for video lessons
     */
    public function scopeVideo($query)
    {
        return $query->where('type', 'video');
    }

    /**
     * Scope for quiz lessons
     */
    public function scopeQuiz($query)
    {
        return $query->where('type', 'quiz');
    }

    /**
     * Scope for document lessons
     */
    public function scopeDocument($query)
    {
        return $query->where('type', 'document');
    }

    /**
     * Scope for enabled lessons
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enable', true);
    }

    /**
     * Scope for free lessons
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Get lesson types
     */
    public static function getTypes(): array
    {
        return [
            'video' => 'Video',
            'quiz' => 'Quiz',
            'file' => 'File',
            'document' => 'Document',
            'mixed' => 'Mixed'
        ];
    }
}
