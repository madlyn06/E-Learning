@extends('admin::master')

@section('meta_title', __('manage::reason.index.page_title'))

@section('page_title', __('manage::reason.index.page_title'))

@section('page_subtitle', __('manage::reason.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('manage::reason.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('setting.admin.setting.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('manage::reason.index.page_title') }}
                        </h6>
                    </div>
                    <div class="text-right">
                        <div class="btn-group">
                            <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'reason_name', 'label' => __('manage::reason.name')])
                        @textarea(['name' => 'reason_description', 'label' => __('manage::reason.description')])
                        @sumoselect(['name' => 'services', 'label' => __('manage::reason.service'), 'multiple' => true, 'options' => get_service_options()])
                        @input(['name' => 'reason_clients', 'label' => __('manage::reason.clients')])
                        @input(['name' => 'reason_projects', 'label' => __('manage::reason.projects')])
                        @input(['name' => 'reason_years', 'label' => __('manage::reason.years')])
                        @mediamanager(['name' => 'reason_image', 'label' => __('manage::reason.image')])
                        
                        @input(['name' => 'consulting_farm', 'label' => __('manage::reason.consulting_farm')])
                        @input(['name' => 'consulting_title', 'label' => __('manage::reason.consulting_title')])
                        @textarea(['name' => 'consulting_description', 'label' => __('manage::reason.consulting_description')])
                    </div>
                </div>

            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                </div>
            </div>
        </div>
    </form>
@stop
