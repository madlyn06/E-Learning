@extends('core::admin.master')

@section('meta_title', __('elearning::report.course_performance_report'))

@section('page_title', __('elearning::report.course_performance_report'))

@section('page_subtitle', __('elearning::report.view_report'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('elearning::report.course_performance_report') }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
