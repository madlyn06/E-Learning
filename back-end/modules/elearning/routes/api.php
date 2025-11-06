<?php

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Elearning\Http\Controllers\Api\Auth\AuthController;
use Modules\Elearning\Http\Controllers\Api\UserController;
use Modules\Elearning\Http\Controllers\Api\CategoryController;

Route::middleware([ApiKeyMiddleware::class, 'throttle:elearning-auth'])->group(function () {
    Route::prefix('v1/elearning')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/login', [AuthController::class, 'login'])->name('elearning.api.auth.login');
            Route::post('/register', [AuthController::class, 'register'])->name('elearning.api.auth.register');
            Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('elearning.api.auth.forgotPassword');
            Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('elearning.api.auth.resetPassword');
            Route::post('/resend-email-verify', [UserController::class, 'resendEmailVerify'])->name('elearning.api.user.resendEmailVerify');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('elearning.api.categories.index');
        });

        Route::prefix('users')->group(function () {
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/profile', [UserController::class, 'profile'])->name('elearning.api.user.profile');
                Route::post('/logout', [UserController::class, 'logout'])->name('elearning.api.user.logout');
                Route::put('/profile', [UserController::class, 'updateProfile'])->name('elearning.api.user.updateProfile');
                Route::post('/verify-account', [UserController::class, 'verifyAccount'])->name('elearning.api.user.verifyAccount');

                // Teacher-related routes
                Route::post('/apply-teacher', [UserController::class, 'applyTeacher'])->name('elearning.api.user.applyTeacher');
                Route::get('/teacher-status', [UserController::class, 'getTeacherStatus'])->name('elearning.api.user.getTeacherStatus');
                Route::get('/taught-courses', [UserController::class, 'getTaughtCourses'])->name('elearning.api.user.getTaughtCourses');

                // User settings and courses
                Route::put('/settings', [UserController::class, 'updateSettings'])->name('elearning.api.user.updateSettings');
                Route::get('/enrolled-courses', [UserController::class, 'getEnrolledCourses'])->name('elearning.api.user.getEnrolledCourses');

                // Wishlist routes
                Route::get('/wishlists', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'index'])->name('elearning.api.wishlist.index');
                Route::post('/wishlists', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'store'])->name('elearning.api.wishlist.store');
                Route::delete('/wishlists/{id}', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'destroy'])->name('elearning.api.wishlist.destroy');
            });

            // Public teacher profile
            Route::get('/teachers/{id}', [UserController::class, 'getTeacherProfile'])->name('elearning.api.user.getTeacherProfile');
        });
        Route::get('payment-methods', [\Modules\Elearning\Http\Controllers\Api\PaymentController::class, 'getPaymentMethods'])->name('elearning.api.payment.getPaymentMethods');

        Route::middleware('auth:sanctum')->group(function () {
            // Course routes
            Route::get('instructor-courses', [\Modules\Elearning\Http\Controllers\Api\CourseController::class, 'getByUserId']);
            Route::get('dashboard-courses', [\Modules\Elearning\Http\Controllers\Api\CourseController::class, 'getDashboardCourses']);
            Route::get('best-selling-courses', [\Modules\Elearning\Http\Controllers\Api\CourseController::class, 'getBestSellingCourses']);
            Route::apiResource('courses', \Modules\Elearning\Http\Controllers\Api\CourseController::class)->only(['store', 'update', 'destroy']);
            // Section routes (nested under course)
            Route::post('courses/{course}/sections', [\Modules\Elearning\Http\Controllers\Api\SectionController::class, 'store']);
            Route::put('sections/{section}', [\Modules\Elearning\Http\Controllers\Api\SectionController::class, 'update']);
            Route::delete('sections/{section}', [\Modules\Elearning\Http\Controllers\Api\SectionController::class, 'destroy']);
            // Lesson routes (nested under section)
            Route::post('sections/{section}/lessons', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'store']);
            Route::get('lessons/{lesson}', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'show']);
            Route::put('lessons/{lesson}', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'update']);
            Route::delete('lessons/{lesson}', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'destroy']);
            Route::post('sections/{section}/lessons/reorder', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'reorder']);
            Route::post('lessons/{lesson}/duplicate', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'duplicate']);
            // Membership routes
            Route::post('memberships/register', [\Modules\Elearning\Http\Controllers\Api\MembershipController::class, 'register']);
            Route::get('lessons/{lesson}/hls-progress', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'hlsProgress']);
            Route::get('lessons/{lesson}/video-streaming', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'getVideoStreamingUrls']);

            // Note Routes
            Route::get('lessons/{lesson}/notes', [\Modules\Elearning\Http\Controllers\Api\NoteController::class, 'index']);
            Route::post('notes', [\Modules\Elearning\Http\Controllers\Api\NoteController::class, 'store']);
            Route::get('notes/{note}', [\Modules\Elearning\Http\Controllers\Api\NoteController::class, 'show']);
            Route::put('notes/{note}', [\Modules\Elearning\Http\Controllers\Api\NoteController::class, 'update']);
            Route::delete('notes/{note}', [\Modules\Elearning\Http\Controllers\Api\NoteController::class, 'destroy']);

            // Comment Routes
            Route::get('lessons/{lesson}/comments', [\Modules\Elearning\Http\Controllers\Api\CommentController::class, 'index']);
            Route::post('comments', [\Modules\Elearning\Http\Controllers\Api\CommentController::class, 'store']);
            Route::put('comments/{comment}', [\Modules\Elearning\Http\Controllers\Api\CommentController::class, 'update']);
            Route::delete('comments/{comment}', [\Modules\Elearning\Http\Controllers\Api\CommentController::class, 'destroy']);
            Route::post('comments/{comment}/like', [\Modules\Elearning\Http\Controllers\Api\CommentController::class, 'like']);
            Route::post('comments/{comment}/dislike', [\Modules\Elearning\Http\Controllers\Api\CommentController::class, 'dislike']);

            // Payment routes
            Route::get('payments/{referenceId}/status', [\Modules\Elearning\Http\Controllers\Api\PaymentController::class, 'checkPaymentStatus']);
            Route::get('payments/history', [\Modules\Elearning\Http\Controllers\Api\PaymentController::class, 'getPaymentHistory']);

            // Coupon routes
            Route::post('coupons/validate', [\Modules\Elearning\Http\Controllers\Api\CouponController::class, 'validate']);

            // Enrollment routes
            Route::post('courses/enroll', [\Modules\Elearning\Http\Controllers\Api\EnrollmentController::class, 'enroll'])->name('elearning.api.enrollment.enroll');
            Route::get('enrollments', [\Modules\Elearning\Http\Controllers\Api\EnrollmentController::class, 'myEnrollments'])->name('elearning.api.enrollment.myEnrollments');
            Route::get('courses/{courseId}/enrollment', [\Modules\Elearning\Http\Controllers\Api\EnrollmentController::class, 'checkEnrollment'])->name('elearning.api.enrollment.checkEnrollment');

            // Review routes
            Route::get('courses/{course}/reviews', [\Modules\Elearning\Http\Controllers\Api\ReviewController::class, 'index']);
            Route::post('reviews', [\Modules\Elearning\Http\Controllers\Api\ReviewController::class, 'store']);
            Route::get('reviews/{review}', [\Modules\Elearning\Http\Controllers\Api\ReviewController::class, 'show']);
            Route::put('reviews/{review}', [\Modules\Elearning\Http\Controllers\Api\ReviewController::class, 'update']);
            Route::delete('reviews/{review}', [\Modules\Elearning\Http\Controllers\Api\ReviewController::class, 'destroy']);

            // Wishlist routes
            Route::get('wishlist', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'index']);
            Route::post('courses/{course}/wishlist', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'add']);
            Route::delete('courses/{course}/wishlist', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'remove']);
            Route::get('courses/{course}/wishlist/check', [\Modules\Elearning\Http\Controllers\Api\WishlistController::class, 'check']);

            Route::get('teacher-applications', [\Modules\Elearning\Http\Controllers\Api\TeacherController::class, 'getApplications']);
            Route::post('teacher-applications/{id}/approve', [\Modules\Elearning\Http\Controllers\Api\TeacherController::class, 'approveApplication']);
            Route::post('teacher-applications/{id}/reject', [\Modules\Elearning\Http\Controllers\Api\TeacherController::class, 'rejectApplication']);
            Route::get('teachers', [\Modules\Elearning\Http\Controllers\Api\TeacherController::class, 'getTeachers']);
            Route::post('teachers/{id}/disable', [\Modules\Elearning\Http\Controllers\Api\TeacherController::class, 'disableTeacher']);

            // Upload media
            Route::post('media/upload', [\Modules\Elearning\Http\Controllers\Api\UploadController::class, 'uploadMedia']);

            // NEW: Progress Tracking Routes
            Route::get('courses/{course}/progress', [\Modules\Elearning\Http\Controllers\Api\ProgressController::class, 'getCourseProgress']);
            Route::post('lessons/{lesson}/complete', [\Modules\Elearning\Http\Controllers\Api\ProgressController::class, 'markLessonComplete']);
            Route::post('lessons/{lesson}/track', [\Modules\Elearning\Http\Controllers\Api\ProgressController::class, 'trackLessonProgress']);
            Route::get('progress/overview', [\Modules\Elearning\Http\Controllers\Api\ProgressController::class, 'getProgressOverview']);
            Route::get('courses/{course}/certificate', [\Modules\Elearning\Http\Controllers\Api\ProgressController::class, 'getCertificate']);

            // NEW: Assignment/Quiz Routes
            Route::get('lessons/{lesson}/assignments', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'index']);
            Route::post('assignments', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'store']);
            Route::get('assignments/{assignment}', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'show']);
            Route::put('assignments/{assignment}', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'update']);
            Route::delete('assignments/{assignment}', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'destroy']);
            Route::post('assignments/{assignment}/submit', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'submit']);
            Route::get('assignments/{assignment}/submissions', [\Modules\Elearning\Http\Controllers\Api\AssignmentController::class, 'getSubmissions']);

            // NEW: Quiz Routes
            Route::get('lessons/{lesson}/quizzes', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'index']);
            Route::post('quizzes', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'store']);
            Route::get('quizzes/{quiz}', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'show']);
            Route::put('quizzes/{quiz}', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'update']);
            Route::delete('quizzes/{quiz}', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'destroy']);
            Route::post('quizzes/{quiz}/take', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'takeQuiz']);
            Route::get('quizzes/{quiz}/results', [\Modules\Elearning\Http\Controllers\Api\QuizController::class, 'getResults']);

            // NEW: Notification Routes
            Route::get('notifications', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'index']);
            Route::get('notifications/unread', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'getUnread']);
            Route::patch('notifications/{id}/read', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
            Route::patch('notifications/read-all', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
            Route::put('notifications/settings', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'updateSettings']);
            Route::get('notifications/settings', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'getSettings']);
            Route::delete('notifications/{id}', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'destroy']);
            Route::delete('notifications', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'deleteAll']);
            Route::get('notifications/preferences', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'getPreferences']);
            Route::put('notifications/preferences', [\Modules\Elearning\Http\Controllers\Api\NotificationController::class, 'updatePreferences']);

            // NEW: Analytics Routes
            Route::get('analytics/dashboard', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getDashboardAnalytics']);
            Route::get('analytics/courses/{course}', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getCourseAnalytics']);
            Route::get('analytics/learning', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getUserLearningAnalytics']);
            Route::get('analytics/enrollments', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getEnrollmentAnalytics']);
            Route::get('analytics/revenue', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getRevenueAnalytics']);
            Route::get('analytics/courses/{course}/student-progress', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getStudentProgressAnalytics']);
            Route::get('analytics/courses/{course}/content-engagement', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getContentEngagementAnalytics']);
            Route::get('analytics/time-spent', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getTimeSpentAnalytics']);
            Route::get('analytics/courses/{course}/completion-rate', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getCompletionRateAnalytics']);
            Route::get('analytics/courses/{course}/assessments', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getAssessmentAnalytics']);
            Route::get('analytics/export-report', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'exportReport']);
            Route::get('analytics/real-time', [\Modules\Elearning\Http\Controllers\Api\AnalyticsController::class, 'getRealTimeAnalytics']);

            // NEW: Discussion Forum Routes
            Route::get('courses/{course}/discussions', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'getCourseDiscussions']);
            Route::get('discussions/{discussion}', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'getDiscussion']);
            Route::post('discussions', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'createDiscussion']);
            Route::put('discussions/{discussion}', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'updateDiscussion']);
            Route::delete('discussions/{discussion}', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'deleteDiscussion']);
            Route::get('discussions/{discussion}/replies', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'getReplies']);
            Route::post('discussions/{discussion}/replies', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'addReply']);
            Route::put('replies/{reply}', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'updateReply']);
            Route::delete('replies/{reply}', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'deleteReply']);
            Route::post('discussions/{discussion}/like', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'likeDiscussion']);
            Route::delete('discussions/{discussion}/like', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'unlikeDiscussion']);
            Route::patch('discussions/{discussion}/solve', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'markAsSolved']);
            Route::get('courses/{course}/discussion-categories', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'getCategories']);
            Route::get('discussions/search', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'searchDiscussions']);
            Route::get('user-discussions', [\Modules\Elearning\Http\Controllers\Api\DiscussionController::class, 'getUserDiscussions']);

            // NEW: Live Session Routes
            Route::get('courses/{course}/live-sessions', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getCourseSessions']);
            Route::get('live-sessions/{session}', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getSession']);
            Route::post('live-sessions', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'createSession']);
            Route::put('live-sessions/{session}', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'updateSession']);
            Route::delete('live-sessions/{session}', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'deleteSession']);
            Route::post('live-sessions/{session}/join', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'joinSession']);
            Route::post('live-sessions/{session}/leave', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'leaveSession']);
            Route::get('live-sessions/{session}/participants', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getParticipants']);
            Route::post('live-sessions/{session}/start', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'startSession']);
            Route::post('live-sessions/{session}/end', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'endSession']);
            Route::get('live-sessions/{session}/recording', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getRecording']);
            Route::get('live-sessions/upcoming', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getUpcomingSessions']);
            Route::get('live-sessions/past', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getPastSessions']);
            Route::post('live-sessions/{session}/reminder', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'sendReminder']);
            Route::get('live-sessions/{session}/chat', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'getChatMessages']);
            Route::post('live-sessions/{session}/chat', [\Modules\Elearning\Http\Controllers\Api\LiveSessionController::class, 'sendChatMessage']);

            // NEW: Mobile-specific Routes
            Route::get('mobile/dashboard', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getDashboard']);
            Route::get('mobile/offline-content', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getOfflineContent']);
            Route::post('mobile/courses/{course}/download', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'downloadCourse']);
            Route::get('mobile/courses/{course}/download-progress', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getDownloadProgress']);
            Route::delete('mobile/courses/{course}/download', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'cancelDownload']);
            Route::post('mobile/sync-offline-progress', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'syncOfflineProgress']);
            Route::get('mobile/notifications', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getMobileNotifications']);
            Route::patch('mobile/notifications/{notification}/read', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'markNotificationRead']);
            Route::get('mobile/app-settings', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getAppSettings']);
            Route::put('mobile/app-settings', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'updateAppSettings']);
            Route::get('mobile/courses', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getMobileCourseList']);
            Route::get('mobile/lessons/{lesson}/content', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getMobileLessonContent']);
            Route::post('mobile/learning-session', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'updateLearningSession']);
            Route::get('mobile/search-suggestions', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getMobileSearchSuggestions']);
            Route::get('mobile/app-version', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'getAppVersionInfo']);
            Route::post('mobile/crash-report', [\Modules\Elearning\Http\Controllers\Api\MobileController::class, 'reportCrash']);
        });

        // Public routes
        Route::apiResource('courses', \Modules\Elearning\Http\Controllers\Api\CourseController::class)->only(['index', 'show']);

        Route::get('courses/{course}/sections', [\Modules\Elearning\Http\Controllers\Api\SectionController::class, 'index']);
        Route::get('sections/{section}', [\Modules\Elearning\Http\Controllers\Api\SectionController::class, 'show']);
        Route::get('sections/{section}/lessons', [\Modules\Elearning\Http\Controllers\Api\LessonController::class, 'index']);

        // NEW: Public Search and Filter Routes
        Route::get('search', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'search']);
        Route::get('courses/filter', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'filterCourses']);
        Route::get('instructors', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getInstructors']);
        Route::get('courses/popular', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getPopularCourses']);
        Route::get('courses/newest', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getNewestCourses']);
        Route::get('courses/free', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getFreeCourses']);
        Route::get('courses/featured', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getFeaturedCourses']);
        Route::get('trending-topics', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getTrendingTopics']);
        Route::get('search-suggestions', [\Modules\Elearning\Http\Controllers\Api\SearchController::class, 'getSuggestions']);

        Route::get('ping', [\Modules\Elearning\Http\Controllers\Api\ELearningController::class, 'ping'])->name('elearning.api.elearning.ping');
        Route::get('available-uploader-drivers', [\Modules\Elearning\Http\Controllers\Api\VideoController::class, 'getAvailableUploaderDrivers'])->name('elearning.api.video.getAvailableUploaderDrivers');
        Route::post('presigned-url', [\Modules\Elearning\Http\Controllers\Api\VideoController::class, 'presignedUrl'])->name('elearning.api.video.presignedUrl');
        Route::post('preflight', [\Modules\Elearning\Http\Controllers\Api\VideoController::class, 'preflight'])->name('elearning.api.video.preflight');
        Route::post('sign-part', [\Modules\Elearning\Http\Controllers\Api\VideoController::class, 'signPart'])->name('elearning.api.video.signPart');
        Route::post('complete', [\Modules\Elearning\Http\Controllers\Api\VideoController::class, 'complete'])->name('elearning.api.video.complete');
        Route::post('abort', [\Modules\Elearning\Http\Controllers\Api\VideoController::class, 'abort'])->name('elearning.api.video.abort');

        // Payment callbacks (no auth required)
        Route::prefix('payments')->name('elearning.payment.')->group(function () {
            Route::post('ipn/{gateway}', [\Modules\Elearning\Http\Controllers\Api\PaymentController::class, 'handleCallback'])->name('elearning.api.payment.ipn');
            Route::get('success', [\Modules\Elearning\Http\Controllers\Api\PaymentController::class, 'handleCallback'])->name('elearning.api.payment.success');
            Route::get('cancel', [\Modules\Elearning\Http\Controllers\Api\PaymentController::class, 'handleCallback'])->name('elearning.api.payment.cancel');
        });
    });
});
