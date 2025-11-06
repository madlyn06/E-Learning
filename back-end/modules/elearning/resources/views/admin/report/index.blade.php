@extends('core::admin.master')

@section('meta_title', __('elearning::report.model_name'))

@section('page_title', __('elearning::report.model_name'))

@section('page_subtitle', __('elearning::report.list'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::report.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{ __('elearning::student.model_name') }}</h5>
                                <span class="h2 font-weight-bold mb-0">{{ number_format($totalStudents) }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{ __('elearning::teacher.model_name') }}</h5>
                                <span class="h2 font-weight-bold mb-0">{{ number_format($totalTeachers) }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{ __('elearning::course.model_name') }}</h5>
                                <span class="h2 font-weight-bold mb-0">{{ number_format($totalCourses) }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{ __('elearning::report.total_revenue') }}</h5>
                                <span class="h2 font-weight-bold mb-0">${{ number_format($totalRevenue, 2) }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('elearning::report.revenue_report') }}</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ __('elearning::report.revenue_report_description') }}</p>
                        <a href="{{ route('elearning.admin.reports.revenue') }}" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> {{ __('elearning::report.view_report') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('elearning::report.enrollment_report') }}</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ __('elearning::report.enrollment_report_description') }}</p>
                        <a href="{{ route('elearning.admin.reports.enrollments') }}" class="btn btn-primary">
                            <i class="fas fa-user-graduate"></i> {{ __('elearning::report.view_report') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('elearning::report.user_activity_report') }}</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ __('elearning::report.user_activity_report_description') }}</p>
                        <a href="{{ route('elearning.admin.reports.user-activity') }}" class="btn btn-primary">
                            <i class="fas fa-users"></i> {{ __('elearning::report.view_report') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('elearning::report.course_performance_report') }}</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ __('elearning::report.course_performance_report_description') }}</p>
                        <a href="{{ route('elearning.admin.reports.course-performance') }}" class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i> {{ __('elearning::report.view_report') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
