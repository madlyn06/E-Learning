<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Enrollment;
use Modules\Elearning\Models\Payment;
use Modules\Elearning\Models\User;

class ReportController extends Controller
{
    /**
     * Get revenue report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revenueReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'interval' => 'nullable|in:daily,weekly,monthly',
        ]);

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $interval = $request->input('interval', 'daily');

        // Get total revenue
        $totalRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Get revenue by time interval
        $formatString = '%Y-%m-%d'; // Default daily format
        $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date";
        
        if ($interval === 'weekly') {
            $formatString = '%Y-%u'; // Year and week number
            $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date, CONCAT('Week ', WEEK(created_at)) as label";
        } elseif ($interval === 'monthly') {
            $formatString = '%Y-%m'; // Year and month
            $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date, DATE_FORMAT(created_at, '%M %Y') as label";
        }

        $revenueByInterval = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw($selectRaw)
            ->selectRaw('SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get revenue by payment method
        $revenueByPaymentMethod = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Get top courses by revenue
        $topCoursesByRevenue = Payment::where('payments.status', 'completed')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->join('elearning__courses', 'payments.course_id', '=', 'elearning__courses.id')
            ->selectRaw('elearning__courses.id, elearning__courses.name, SUM(payments.amount) as total')
            ->groupBy('elearning__courses.id', 'elearning__courses.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return response()->json([
            'total_revenue' => $totalRevenue,
            'revenue_by_interval' => $revenueByInterval,
            'revenue_by_payment_method' => $revenueByPaymentMethod,
            'top_courses_by_revenue' => $topCoursesByRevenue,
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'interval' => $interval,
            ],
        ]);
    }

    /**
     * Get enrollment report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enrollmentReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'interval' => 'nullable|in:daily,weekly,monthly',
        ]);

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $interval = $request->input('interval', 'daily');

        // Get total enrollments
        $totalEnrollments = Enrollment::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Get enrollments by time interval
        $formatString = '%Y-%m-%d'; // Default daily format
        $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date";
        
        if ($interval === 'weekly') {
            $formatString = '%Y-%u'; // Year and week number
            $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date, CONCAT('Week ', WEEK(created_at)) as label";
        } elseif ($interval === 'monthly') {
            $formatString = '%Y-%m'; // Year and month
            $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date, DATE_FORMAT(created_at, '%M %Y') as label";
        }

        $enrollmentsByInterval = Enrollment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw($selectRaw)
            ->selectRaw('COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top courses by enrollments
        $topCoursesByEnrollments = Enrollment::whereBetween('enrollments.created_at', [$startDate, $endDate])
            ->join('elearning__courses', 'enrollments.course_id', '=', 'elearning__courses.id')
            ->selectRaw('elearning__courses.id, elearning__courses.name, COUNT(*) as total')
            ->groupBy('elearning__courses.id', 'elearning__courses.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Get completion rate by course
        $completionRateByCourse = Enrollment::whereBetween('enrollments.created_at', [$startDate, $endDate])
            ->join('elearning__courses', 'enrollments.course_id', '=', 'elearning__courses.id')
            ->selectRaw('
                elearning__courses.id, 
                elearning__courses.name, 
                COUNT(*) as total_enrollments,
                SUM(CASE WHEN enrollments.status = "completed" THEN 1 ELSE 0 END) as completed_count,
                ROUND(SUM(CASE WHEN enrollments.status = "completed" THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as completion_rate
            ')
            ->groupBy('elearning__courses.id', 'elearning__courses.name')
            ->having('total_enrollments', '>', 0)
            ->orderByDesc('total_enrollments')
            ->limit(10)
            ->get();

        return response()->json([
            'total_enrollments' => $totalEnrollments,
            'enrollments_by_interval' => $enrollmentsByInterval,
            'top_courses_by_enrollments' => $topCoursesByEnrollments,
            'completion_rate_by_course' => $completionRateByCourse,
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'interval' => $interval,
            ],
        ]);
    }

    /**
     * Get user activity report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userActivityReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'interval' => 'nullable|in:daily,weekly,monthly',
        ]);

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $interval = $request->input('interval', 'daily');

        // Get new user registrations
        $formatString = '%Y-%m-%d'; // Default daily format
        $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date";
        
        if ($interval === 'weekly') {
            $formatString = '%Y-%u'; // Year and week number
            $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date, CONCAT('Week ', WEEK(created_at)) as label";
        } elseif ($interval === 'monthly') {
            $formatString = '%Y-%m'; // Year and month
            $selectRaw = "DATE_FORMAT(created_at, '{$formatString}') as date, DATE_FORMAT(created_at, '%M %Y') as label";
        }

        $newUsersByInterval = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw($selectRaw)
            ->selectRaw('COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get active users
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Get teacher registrations
        $teacherRegistrations = User::where('teacher_status', 'approved')
            ->whereBetween('teacher_approved_at', [$startDate, $endDate])
            ->count();

        // Get teacher registrations by interval
        $teacherRegistrationsByInterval = User::where('teacher_status', 'approved')
            ->whereBetween('teacher_approved_at', [$startDate, $endDate])
            ->selectRaw($selectRaw)
            ->selectRaw('COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get user roles distribution
        $userRolesDistribution = User::selectRaw('
                SUM(CASE WHEN is_teacher = 1 AND teacher_status = "approved" THEN 1 ELSE 0 END) as teachers,
                SUM(CASE WHEN is_teacher = 0 OR teacher_status != "approved" THEN 1 ELSE 0 END) as students
            ')
            ->first();

        return response()->json([
            'new_users_by_interval' => $newUsersByInterval,
            'active_users' => $activeUsers,
            'teacher_registrations' => $teacherRegistrations,
            'teacher_registrations_by_interval' => $teacherRegistrationsByInterval,
            'user_roles_distribution' => $userRolesDistribution,
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'interval' => $interval,
            ],
        ]);
    }

    /**
     * Get course performance report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function coursePerformanceReport(Request $request)
    {
        $request->validate([
            'course_id' => 'nullable|exists:elearning__courses,id',
        ]);

        $courseId = $request->input('course_id');
        
        $query = Course::with(['user:id,name,email']);
        
        if ($courseId) {
            $query->where('id', $courseId);
        }
        
        $courses = $query->select([
                'id', 'name', 'user_id', 'students_count', 'total_lesson', 'total_hour', 'average_rating'
            ])
            ->withCount(['enrollments', 'reviews', 'wishlists'])
            ->limit(20)
            ->get();
            
        // Calculate completion rate for each course
        foreach ($courses as $course) {
            $totalEnrollments = $course->enrollments_count;
            $completedEnrollments = Enrollment::where('course_id', $course->id)
                ->where('status', 'completed')
                ->count();
                
            $course->completion_rate = $totalEnrollments > 0 
                ? round(($completedEnrollments / $totalEnrollments) * 100, 2) 
                : 0;
                
            // Calculate revenue for this course
            $courseRevenue = Payment::where('course_id', $course->id)
                ->where('status', 'completed')
                ->sum('amount');
                
            $course->revenue = $courseRevenue;
        }

        return response()->json([
            'courses' => $courses,
        ]);
    }

    /**
     * Get dashboard summary report
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardSummary()
    {
        // Get total counts
        $totalStudents = User::where('is_teacher', false)->count();
        $totalTeachers = User::where('is_teacher', true)->where('teacher_status', 'approved')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        
        // Get revenue
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');
            
        // Get recent enrollments
        $recentEnrollments = Enrollment::with(['user:id,name,email', 'course:id,name'])
            ->latest()
            ->limit(10)
            ->get();
            
        // Get pending teacher applications
        $pendingTeacherApplications = User::where('teacher_status', 'pending')
            ->count();
            
        // Get top rated courses
        $topRatedCourses = Course::orderByDesc('average_rating')
            ->limit(5)
            ->select(['id', 'name', 'user_id', 'average_rating'])
            ->with(['user:id,name'])
            ->get();
            
        // Get most enrolled courses
        $mostEnrolledCourses = Course::orderByDesc('students_count')
            ->limit(5)
            ->select(['id', 'name', 'user_id', 'students_count'])
            ->with(['user:id,name'])
            ->get();

        return response()->json([
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_courses' => $totalCourses,
            'total_enrollments' => $totalEnrollments,
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'recent_enrollments' => $recentEnrollments,
            'pending_teacher_applications' => $pendingTeacherApplications,
            'top_rated_courses' => $topRatedCourses,
            'most_enrolled_courses' => $mostEnrolledCourses,
        ]);
    }
}
