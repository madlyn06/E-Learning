<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Enrollment;
use Modules\Elearning\Models\Payment;
use Modules\Elearning\Models\User;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Summary statistics
        $totalStudents = User::where('is_teacher', false)->count();
        $totalTeachers = User::where('is_teacher', true)->where('teacher_status', 'approved')->count();
        $totalCourses = Course::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        
        return view('elearning::admin.report.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'totalRevenue'
        ));
    }
    
    /**
     * Display revenue report page.
     *
     * @return \Illuminate\View\View
     */
    public function revenue()
    {
        return view('elearning::admin.report.revenue');
    }
    
    /**
     * Display enrollment report page.
     *
     * @return \Illuminate\View\View
     */
    public function enrollments()
    {
        return view('elearning::admin.report.enrollments');
    }
    
    /**
     * Display user activity report page.
     *
     * @return \Illuminate\View\View
     */
    public function userActivity()
    {
        return view('elearning::admin.report.user-activity');
    }
    
    /**
     * Display course performance report page.
     *
     * @return \Illuminate\View\View
     */
    public function coursePerformance()
    {
        $courses = Course::orderBy('name')->pluck('name', 'id');
        
        return view('elearning::admin.report.course-performance', compact('courses'));
    }
}
