@extends('core::admin.master')

@section('meta_title', __('cms::setting.index.page_title'))

@section('page_title', __('cms::setting.index.page_title'))

@section('page_subtitle', __('cms::setting.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a
                    href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
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
                            {{ __('media::setting.index.page_title') }}
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
                        @checkbox(['name' => 'media_is_cloud', 'placeholder' => __('media::setting.media_is_cloud'), 'default' => true, 'label' => ''])
                    </div>
                </div>
                <div class="row" id="cloud-provider" style="display: none">
                    <div class="col-md-12">
                        @select(['name' => 'media_provider', 'allowClear' => false, 'label' => __('media::setting.media_provider'), 'options' => get_cloud_providers()])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'media_max_file_size', 'label' => __('media::setting.media_max_file_size'), 'default' => 25])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'DIGITAL_AWS_ACCESS_KEY_ID', 'label' => __('DIGITAL AWS ACCESS KEY ID'), 'default' => 'DO008EF8HC6TYVM7LRZQ'])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'DIGITAL_AWS_SECRET_ACCESS_KEY', 'label' => __('DIGITAL AWS SECRET ACCESS KEY'), 'default' => 'hn4ggzvIbQ0cbiiH/Vlm4reYrTo6ydHwrbLZArHFbaI'])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'DIGITAL_AWS_DEFAULT_REGION', 'label' => __('DIGITAL AWS DEFAULT REGION'), 'default' => 'sgp1'])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'DIGITAL_AWS_BUCKET', 'label' => __('DIGITAL AWS BUCKET'), 'default' => 'cdn.dichvuhaiquan.vn'])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'DIGITAL_AWS_URL', 'label' => __('DIGITAL AWS URL'), 'default' => 'https://cdn.dichvuhaiquan.vn'])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @input(['name' => 'DIGITAL_AWS_ENDPOINT', 'label' => __('DIGITAL AWS ENDPOINT'), 'default' => 'https://sgp1.digitaloceanspaces.com'])
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
@assetadd('seo.script', 'vendor/media/js/admin/setting.js', ['jquery'])
