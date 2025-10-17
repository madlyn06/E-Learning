@extends('core::admin.master')

@section('meta_title', __('dashboard::message.setting.page_title'))

@section('page_title', __('dashboard::message.setting.page_title'))

@section('page_subtitle', __('dashboard::message.setting.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('dashboard::message.setting.page_title') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('admin.dashboard.save') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">{{ __('dashboard::message.setting.page_title') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-nestable">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="title">{{ __('dashboard::message.setting.my_dashboard') }}</div>
                                    <div class="dd" id="myDashboard"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="title">{{ __('dashboard::message.setting.all_dashboard') }}</div>
                                    <div class="dd" id="allDashboard"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('page_scripts')
    <script>
        var myDashboard = @json($myDashboard);
        var allDashboard = @json($allDashboard);
    </script>
@endpush

@assetadd('jquery.nestable', 'vendor/newnet-admin/plugins/nestable2/jquery.nestable.min.css')
@assetadd('jquery.nestable', 'vendor/newnet-admin/plugins/nestable2/jquery.nestable.min.js', ['jquery'])
@assetadd('toastr', 'vendor/newnet-admin/plugins/toastr/toastr.min.js', ['jquery'])
@assetadd('toastr', 'vendor/newnet-admin/plugins/toastr/toastr.css')
@assetadd('dashboard-seting', 'vendor/dashboard/admin/js/dashboard-seting.js', ['jquery'])
@assetadd('dashboard-seting', 'vendor/dashboard/admin/css/dashboard-seting.css')
