<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

/**
 * @property int $id
 * @property int $course_id
 * @property array<array-key, mixed> $name
 * @property array<array-key, mixed>|null $description
 * @property array<array-key, mixed>|null $content
 * @property int $display_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course $course
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Section extends Model
{
    use TranslatableTrait;
    use SeoableTrait;

    protected $table = 'elearning__sections';

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'content',
        'display_order',
        'is_active',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the URL for the section
     */
    public function getUrl()
    {
        return route('elearning.web.section.detail', $this->id);
    }

    /**
     * Get the course that owns the section
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for the section
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('display_order');
    }
}
