@extends('core::admin.master')

@section('meta_title', __('elearning::report.user_activity_report'))

@section('page_title', __('elearning::report.user_activity_report'))

@section('page_subtitle', __('elearning::report.view_report'))

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title">{{ __('elearning::report.user_activity_report') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
