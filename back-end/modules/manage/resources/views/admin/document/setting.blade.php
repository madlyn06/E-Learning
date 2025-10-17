@extends('core::admin.master')

@section('meta_title', __('manage::document.setting.index.page_title'))

@section('page_title', __('manage::document.setting.index.page_title'))

@section('page_subtitle', __('manage::document.setting.index.page_subtitle'))

@section('breadcrumb')
<nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
  <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
    <li class="breadcrumb-item">{{ trans('manage::document.setting.index.breadcrumb') }}</li>
  </ol>
</nav>
@stop

@section('content')
<form action="{{ route('manage.admin.files.setting.save') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="fs-17 font-weight-600 mb-0">
            {{ __('manage::document.setting.index.page_title') }}
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
        <div class="col-md-5">
          @input(['name' => 'file_setting_name1', 'label' => __('manage::document.setting.name1'), 'default' => 'Tải xuống'])
        </div>
        <div class="col-md-7">
          @input(['name' => 'file_setting_value1', 'label' => __('manage::document.setting.value1'),])
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          @input(['name' => 'file_setting_name2', 'label' => __('manage::document.setting.name2'), 'default' => 'Tải xuống'])
        </div>
        <div class="col-md-7">
          @input(['name' => 'file_setting_value2', 'label' => __('manage::document.setting.value2'),])
        </div>
      </div>
      <div class="row">
      <div class="col-md-12">
        @mediamanager(['name' => 'banner_doc_search', 'label' => __('manage::document.setting.banner_doc_search')])
      </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          @textarea(['name' => 'doc_description', 'label' => __('manage::document.setting.doc_description')])
        </div>
        <div class="col-md-12">
          @textarea(['name' => 'form_description', 'label' => __('manage::document.setting.form_description')])
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
