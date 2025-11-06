<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Newnet\Media\Traits\HasMediaTrait;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

/**
 * @property int $id
 * @property string $uuid
 * @property array<array-key, mixed> $name
 * @property string|null $slug
 * @property string $code
 * @property string|null $type
 * @property string|null $level
 * @property float|null $price
 * @property float|null $sale_price
 * @property float|null $pre_price
 * @property int $user_id
 * @property int $students_count
 * @property int $total_lesson
 * @property string $total_hour
 * @property array<array-key, mixed>|null $course_purpose
 * @property array<array-key, mixed>|null $summary
 * @property array<array-key, mixed>|null $content
 * @property bool $is_enable
 * @property bool $is_selling
 * @property bool $is_published
 * @property bool $is_coming_soon
 * @property array<array-key, mixed>|null $announcement
 * @property bool $is_pre_order
 * @property \Illuminate\Support\Carbon|null $end_of_course
 * @property float $average_rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\CourseRequirement> $requirements
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Kalnoy\Nestedset\Collection<int, \Modules\Elearning\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Kalnoy\Nestedset\Collection<int, \Modules\Elearning\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Discussion> $discussions
 * @property-read int|null $discussions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Enrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSession> $liveSessions
 * @property-read int|null $live_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MobileDownload> $mobileDownloads
 * @property-read int|null $mobile_downloads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MobileLearningSession> $mobileLearningSessions
 * @property-read int|null $mobile_learning_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\CoursePurpose> $purposes
 * @property-read int|null $purposes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\CourseQA> $qas
 * @property-read int|null $qas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read int|null $requirements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Section> $sections
 * @property-read int|null $sections_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read mixed $translations
 * @property-read \Modules\Elearning\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\User> $userLicenses
 * @property-read int|null $user_licenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\User> $wishlistedByUsers
 * @property-read int|null $wishlisted_by_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course enabled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course free()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course paid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course selling()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereAnnouncement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereAverageRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCoursePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereEndOfCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsComingSoon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsPreOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsSelling($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course wherePrePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereStudentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTotalHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTotalLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUuid($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;

    protected $table = 'elearning__courses';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'code',
        'type',
        'level',
        'price',
        'sale_price',
        'user_id',
        'students_count',
        'total_lesson',
        'total_hour',
        'course_purpose',
        'summary',
        'content',
        'video_id',
        'is_enable',
        'is_selling',
        'announcement',
        'average_rating',
        'category_id',
        'image',
    ];

    public $translatable = [
        'name',
        'summary',
        'content',
        'course_purpose',
        'announcement',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'is_enable' => 'boolean',
        'is_selling' => 'boolean',
        'average_rating' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($model->code)) {
                $model->code = \Illuminate\Support\Str::random(10);
            }
            if (empty($model->total_hour)) {
                $model->total_hour = 0;
            }
        });
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    protected function totalReviews(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->reviews()->count(),
        );
    }

    protected function totalStudents(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->enrollments()->count()
        );
    }

    protected function studentsCompleted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->enrollments()
                ->whereNotNull('completed_at')
                ->count()
        );
    }

    protected function studentsInProgress(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->enrollments()
                ->whereNull('completed_at')
                ->where('completion_percentage', '>', 0)
                ->count()
        );
    }


    /**
     * Get the URL for the course
     */
    public function getUrl()
    {
        return route('elearning.web.course.detail', $this->slug ?? $this->id);
    }

    /**
     * Get the user (instructor) for the course
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the categories for the course
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'elearning__category_course');
    }

    /**
     * Get the requirements for the course
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(CourseRequirement::class)->orderBy('display_order');
    }

    /**
     * Get the purposes for the course
     */
    public function purposes(): HasMany
    {
        return $this->hasMany(CoursePurpose::class)->orderBy('display_order');
    }

    /**
     * Get the Q&A for the course
     */
    public function qas(): HasMany
    {
        return $this->hasMany(CourseQA::class)->orderBy('display_order');
    }

    /**
     * Get the sections for the course
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('display_order');
    }

    /**
     * Get the enrollments for the course
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the reviews for the course
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the users who have wishlisted this course
     */
    public function wishlistedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'elearning__wishlists', 'course_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the wishlist entries for this course
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the discussions for the course
     */
    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Get the live sessions for the course
     */
    public function liveSessions(): HasMany
    {
        return $this->hasMany(LiveSession::class);
    }

    /**
     * Get the assignments for the course (through lessons)
     */
    public function assignments()
    {
        return $this->hasManyThrough(Assignment::class, Lesson::class, 'section_id', 'lesson_id')
            ->join('elearning__sections', 'elearning__sections.id', '=', 'elearning__assignments.lesson_id')
            ->where('elearning__sections.course_id', $this->id);
    }

    /**
     * Get the quizzes for the course (through lessons)
     */
    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Lesson::class, 'section_id', 'lesson_id')
            ->join('elearning__sections', 'elearning__sections.id', '=', 'elearning__quizzes.lesson_id')
            ->where('elearning__sections.course_id', $this->id);
    }

    /**
     * Get the mobile downloads for the course
     */
    public function mobileDownloads(): HasMany
    {
        return $this->hasMany(MobileDownload::class);
    }

    /**
     * Get the mobile learning sessions for the course
     */
    public function mobileLearningSessions(): HasMany
    {
        return $this->hasMany(MobileLearningSession::class);
    }

    /**
     * Get the comments for the course
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the notes for the course
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the payments for the course
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user licenses for the course
     */
    public function userLicenses(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'elearning__user_licenses', 'course_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Scope for published courses
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for enabled courses
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enable', true);
    }

    /**
     * Scope for selling courses
     */
    public function scopeSelling($query)
    {
        return $query->where('is_selling', true);
    }

    /**
     * Scope for free courses
     */
    public function scopeFree($query)
    {
        return $query->where('price', 0)->orWhere('sale_price', 0);
    }

    /**
     * Scope for paid courses
     */
    public function scopePaid($query)
    {
        return $query->where('price', '>', 0)->orWhere('sale_price', '>', 0);
    }

    /**
     * Get course levels
     */
    public static function getLevels(): array
    {
        return [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert'
        ];
    }

    /**
     * Get course types
     */
    public static function getTypes(): array
    {
        return [
            'course' => 'Course',
            'workshop' => 'Workshop',
            'tutorial' => 'Tutorial',
            'certification' => 'Certification'
        ];
    }
}
