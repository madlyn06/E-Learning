@extends('core::admin.master')

@section('meta_title', __('Cài đặt chung cào dữ liệu'))

@section('page_title', __('Cài đặt chung cào dữ liệu'))

@section('page_subtitle', __('cms::setting.index.page_subtitle'))

@section('breadcrumb')
<nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
        <li class="breadcrumb-item">{{ trans('cms::setting.index.breadcrumb') }}</li>
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
                        {{ __('Cài đặt chung cào dữ liệu') }}
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
                    @input(['name' => 'elements_except', 'label' => __('Các thẻ loại bỏ khi cào dữ liệu'),])
                    @select(['name' => 'chatgpt_model', 'label' => __('ChatGPT model'), 'allowClear' => false, 'options' => get_chatgpt_model_options(), 'required' => true])
                    @checkbox(['name' => 'allow_convert_image_to_webp', 'label' => '', 'placeholder' => __('Cho phép chuyển đổi ảnh sang định dạng webp'), 'default' => true])
                    @checkbox(['name' => 'allow_rotate_image_180', 'label' => '', 'placeholder' => __('Cho phép xoay lật ảnh'), 'default' => true])
                    @checkbox(['name' => 'resize_image_to_1200x675', 'label' => '', 'placeholder' => __('Resize hình ảnh về kích thước 1200x675')])
                    @input(['name' => 'chatgpt_api_key', 'label' => __('ChatGPT API key')])
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

@assetadd('seo.script', 'vendor/cms/assets/admin/js/setting.js', ['jquery'])
