@extends('core::admin.master')

@section('meta_title', __('elearning::enrollment.index.page_title'))

@section('page_title', __('elearning::enrollment.index.page_title'))

@section('page_subtitle', __('elearning::enrollment.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::enrollment.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('elearning::enrollment.index.page_title') }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('elearning::enrollment.user') }}</th>
                            <th>{{ __('elearning::enrollment.course') }}</th>
                            <th>{{ __('elearning::enrollment.price') }}</th>
                            <th>{{ __('elearning::enrollment.status') }}</th>
                            <th>{{ __('elearning::enrollment.created_at') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $enrollment)
                            <tr>
                                <td>{{ $enrollment->id }}</td>
                                <td>{{ $enrollment->user->name }}</td>
                                <td>{{ $enrollment->course->name }}</td>
                                <td>@vnmoney($enrollment->price_paid) đ</td>
                                <td>{{ $enrollment->status }}</td>
                                <td>{{ $enrollment->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('elearning.admin.enrollments.edit', $enrollment->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
