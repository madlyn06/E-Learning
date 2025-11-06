<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Chapter> $childChapters
 * @property-read int|null $child_chapters_count
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property-read Chapter|null $nextChapter
 * @property-read Chapter|null $parentChapter
 * @property-read Chapter|null $previousChapter
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read mixed $translations
 * @property-read \Modules\Elearning\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chapter whereLocales(string $column, array $locales)
 * @mixin \Eloquent
 */
class Chapter extends Model
{
    use TranslatableTrait;
    use SeoableTrait;

    protected $table = 'elearning__chapters';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'chapter_id',
        'user_id',
        'summary',
        'content',
        'lesson_purpose',
        'is_free',
        'is_enable',
        'is_selling',
        'is_published',
        'is_completed',
        'video',
        'video_id',
        'video_type',
        'video_url',
        'end_of_free',
        'continue_id',
        'previous_id',
        'position',
    ];

    public $translatable = [
        'name',
        'summary',
        'content',
        'lesson_purpose',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_enable' => 'boolean',
        'is_selling' => 'boolean',
        'is_published' => 'boolean',
        'is_completed' => 'boolean',
        'end_of_free' => 'datetime',
    ];

    /**
     * Get the URL for the chapter
     */
    public function getUrl()
    {
        return route('elearning.web.chapter.detail', $this->slug ?? $this->id);
    }

    /**
     * Get the parent chapter
     */
    public function parentChapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    /**
     * Get the child chapters
     */
    public function childChapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'chapter_id');
    }

    /**
     * Get the user that owns the chapter
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lessons for the chapter
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    /**
     * Get the next chapter
     */
    public function nextChapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'continue_id');
    }

    /**
     * Get the previous chapter
     */
    public function previousChapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'previous_id');
    }
}
