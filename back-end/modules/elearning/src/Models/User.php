<?php

namespace Modules\Elearning\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string|null $profile_image
 * @property string $email
 * @property string $password
 * @property string|null $code
 * @property string|null $social_platform
 * @property string|null $social_avatar
 * @property string|null $skill
 * @property string|null $introduce
 * @property string|null $bio
 * @property string|null $video_intro
 * @property string|null $star
 * @property string|null $phone
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $instagram
 * @property string|null $website
 * @property string|null $github
 * @property string|null $linkedin
 * @property string|null $topic
 * @property string|null $address
 * @property string $default_language
 * @property array<array-key, mixed>|null $settings
 * @property bool $is_enable
 * @property bool $is_teacher
 * @property string|null $teaching_experience
 * @property string|null $education_background
 * @property string|null $certificates
 * @property array<array-key, mixed>|null $teaching_categories
 * @property string $teacher_status
 * @property \Illuminate\Support\Carbon|null $teacher_approved_at
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone_number
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\AssignmentSubmission> $assignmentSubmissions
 * @property-read int|null $assignment_submissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Kalnoy\Nestedset\Collection<int, \Modules\Elearning\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\DiscussionLike> $discussionLikes
 * @property-read int|null $discussion_likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\DiscussionReply> $discussionReplies
 * @property-read int|null $discussion_replies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Discussion> $discussions
 * @property-read int|null $discussions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Course> $enrolledCourses
 * @property-read int|null $enrolled_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSessionChat> $liveSessionChatMessages
 * @property-read int|null $live_session_chat_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSessionParticipant> $liveSessionParticipations
 * @property-read int|null $live_session_participations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\LiveSession> $liveSessions
 * @property-read int|null $live_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MemberShip> $memberships
 * @property-read int|null $memberships_count
 * @property-read \Modules\Elearning\Models\MobileAppSetting|null $mobileAppSettings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MobileCrashReport> $mobileCrashReports
 * @property-read int|null $mobile_crash_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MobileDownload> $mobileDownloads
 * @property-read int|null $mobile_downloads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\MobileLearningSession> $mobileLearningSessions
 * @property-read int|null $mobile_learning_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\NotificationPreference> $notificationPreferences
 * @property-read int|null $notification_preferences_count
 * @property-read \Modules\Elearning\Models\NotificationSetting|null $notificationSettings
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\PaymentMethod> $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\QuizAttempt> $quizAttempts
 * @property-read int|null $quiz_attempts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Course> $teacherCourses
 * @property-read int|null $teacher_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\TrackingLesson> $trackingLessons
 * @property-read int|null $tracking_lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\TrackingLogin> $trackingLogins
 * @property-read int|null $tracking_logins_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Course> $userLicenses
 * @property-read int|null $user_licenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Course> $wishlistedCourses
 * @property-read int|null $wishlisted_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCertificates($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDefaultLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEducationBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGithub($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIntroduce($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsTeacher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSocialAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSocialPlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeacherApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeacherStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeachingCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeachingExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVideoIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWebsite($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'elearning__users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
        'profile_image',
        'social_platform',
        'social_avatar',
        'skill',
        'introduce',
        'bio',
        'video_intro',
        'star',
        'phone',
        'facebook',
        'twitter',
        'instagram',
        'website',
        'github',
        'linkedin',
        'topic',
        'address',
        'default_language',
        'settings',
        'is_enable',
        'is_teacher',
        'teaching_experience',
        'education_background',
        'certificates',
        'teaching_categories',
        'teacher_status',
        'teacher_approved_at',
        'rejection_reason',
        'created_at',
        'updated_at',
        'first_name',
        'last_name',
        'phone_number',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_enable' => 'boolean',
            'is_teacher' => 'boolean',
            'settings' => 'array',
            'teaching_categories' => 'array',
            'teacher_approved_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    protected function totalStudents(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->enrollments()->count(),
        );
    }

    protected function totalCourses(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->teacherCourses()->count(),
        );
    }



    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->teacherCourses()->avg('average_rating') ?? 0,
        );
    }

    protected function totalReviews(): Attribute
    {
        return Attribute::make(
            get: fn() => Review::whereIn(
                'course_id',
                $this->teacherCourses()->pluck('id')
            )->count()
        );
    }

    public function enrollments()
    {
        return $this->hasManyThrough(
            Enrollment::class,   // bảng cuối cùng muốn đến
            Course::class,       // bảng trung gian
            'user_id',        // foreign key trên courses (trỏ tới user)
            'course_id',         // foreign key trên enrollments (trỏ tới courses)
            'id',                // local key trên users
            'id'                 // local key trên courses
        );
    }

    /**
     * Get the courses that the user has wishlisted.
     */
    public function wishlistedCourses()
    {
        return $this->belongsToMany(Course::class, 'elearning__wishlists', 'user_id', 'course_id')
            ->withTimestamps();
    }

    /**
     * Get user's wishlist items.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the courses created by this user (as a teacher).
     */
    public function teacherCourses()
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    /**
     * Get the courses enrolled by this user (as a student).
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'elearning__enrollments', 'user_id', 'course_id')
            ->withPivot(['status', 'progress', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Get the user's reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the user's notes.
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the user's comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if the user is a teacher.
     *
     * @return bool
     */
    public function isTeacher(): bool
    {
        return $this->is_teacher && $this->teacher_status === 'approved';
    }

    /**
     * Check if the user has a pending teacher application.
     *
     * @return bool
     */
    public function hasPendingTeacherApplication(): bool
    {
        return $this->teacher_status === 'pending';
    }

    /**
     * Get the user's lessons
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the user's assignments
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the user's assignment submissions
     */
    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * Get the user's quizzes
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    /**
     * Get the user's quiz attempts
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get the user's discussions
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Get the user's discussion replies
     */
    public function discussionReplies()
    {
        return $this->hasMany(DiscussionReply::class);
    }

    /**
     * Get the user's discussion likes
     */
    public function discussionLikes()
    {
        return $this->hasMany(DiscussionLike::class);
    }

    /**
     * Get the user's live sessions (as instructor)
     */
    public function liveSessions()
    {
        return $this->hasMany(LiveSession::class, 'instructor_id');
    }

    /**
     * Get the user's live session participations
     */
    public function liveSessionParticipations()
    {
        return $this->hasMany(LiveSessionParticipant::class);
    }

    /**
     * Get the user's live session chat messages
     */
    public function liveSessionChatMessages()
    {
        return $this->hasMany(LiveSessionChat::class);
    }

    /**
     * Get the user's mobile downloads
     */
    public function mobileDownloads()
    {
        return $this->hasMany(MobileDownload::class);
    }

    /**
     * Get the user's mobile app settings
     */
    public function mobileAppSettings()
    {
        return $this->hasOne(MobileAppSetting::class);
    }

    /**
     * Get the user's mobile learning sessions
     */
    public function mobileLearningSessions()
    {
        return $this->hasMany(MobileLearningSession::class);
    }

    /**
     * Get the user's mobile crash reports
     */
    public function mobileCrashReports()
    {
        return $this->hasMany(MobileCrashReport::class);
    }

    /**
     * Get the user's notification settings
     */
    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    /**
     * Get the user's notification preferences
     */
    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }

    /**
     * Get the user's tracking logins
     */
    public function trackingLogins()
    {
        return $this->hasMany(TrackingLogin::class);
    }

    /**
     * Get the user's tracking lessons
     */
    public function trackingLessons()
    {
        return $this->hasMany(TrackingLesson::class);
    }

    /**
     * Get the user's payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user's payment methods
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get the user's memberships
     */
    public function memberships()
    {
        return $this->hasMany(MemberShip::class);
    }

    /**
     * Get the user's user licenses
     */
    public function userLicenses()
    {
        return $this->belongsToMany(Course::class, 'elearning__user_licenses', 'user_id', 'course_id')
            ->withTimestamps();
    }
}
