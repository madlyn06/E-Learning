@extends('core::admin.master')

@section('meta_title', __('elearning::review.index.page_title'))

@section('page_title', __('elearning::review.index.page_title'))

@section('page_subtitle', __('elearning::review.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::review.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('elearning::review.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('elearning.admin.review.create')
                            <a href="{{ route('elearning.admin.reviews.create') }}" class="action-item">
                                <i class="fa fa-plus"></i>
                                {{ __('core::button.add') }}
                            </a>
                        @endadmincan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            </div>
        </div>
    </div>
@stop
