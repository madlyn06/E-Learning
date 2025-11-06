@extends('core::admin.master')

@section('meta_title', __('elearning::setting.index.page_title'))

@section('page_title', __('elearning::setting.index.page_title'))

@section('page_subtitle', __('elearning::setting.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a
                    href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::setting.index.breadcrumb') }}</li>
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
                            {{ __('elearning::setting.index.page_title') }}
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
                <ul class="nav nav-tabs" id="settingTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab"
                            aria-controls="general" aria-selected="true">
                            {{ __('elearning::setting.tabs.general') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="features-tab" data-toggle="tab" href="#features" role="tab"
                            aria-controls="features" aria-selected="false">
                            {{ __('elearning::setting.tabs.features') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="video-tab" data-toggle="tab" href="#video" role="tab"
                            aria-controls="video" aria-selected="false">
                            {{ __('elearning::setting.tabs.video') }}
                        </a>
                    </li>
                </ul>

                <div class="tab-content pt-4" id="settingTabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <div class="col-md-6">

                                @input(['type' => 'number', 'name' => 'max_courses_per_teacher', 'label' => __('elearning::setting.max_courses_per_teacher'), 'value' => $settings['max_courses_per_teacher'] ?? 10, 'min' => 1, 'max' => 100])

                                @input(['type' => 'number', 'name' => 'commission_rate', 'label' => __('elearning::setting.commission_rate'), 'value' => $settings['commission_rate'] ?? 20, 'min' => 0, 'max' => 100, 'help' => __('elearning::setting.commission_rate_help')])
                            </div>
                        </div>
                    </div>

                    <!-- Features Settings -->
                    <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                        <div class="row">
                            <div class="col-md-6">
                                @checkbox(['name' => 'enable_course_review', 'label' => '', 'placeholder' => __('elearning::setting.enable_course_review'), 'checked' => $settings['enable_course_review'] ?? true])

                                @checkbox(['name' => 'enable_teacher_application', 'label' => '', 'placeholder' => __('elearning::setting.enable_teacher_application'), 'checked' => $settings['enable_teacher_application'] ?? true])

                                @checkbox(['name' => 'enable_wishlist', 'label' => '', 'placeholder' => __('elearning::setting.enable_wishlist'), 'checked' => $settings['enable_wishlist'] ?? true])

                                @checkbox(['name' => 'enable_comments', 'label' => '', 'placeholder' => __('elearning::setting.enable_comments'), 'checked' => $settings['enable_comments'] ?? true])

                                @checkbox(['name' => 'enable_notes', 'label' => '', 'placeholder' => __('elearning::setting.enable_notes'), 'checked' => $settings['enable_notes'] ?? true])

                                @checkbox(['name' => 'enable_membership', 'label' => '', 'placeholder' => __('elearning::setting.enable_membership'), 'checked' => $settings['enable_membership'] ?? true])
                            </div>
                        </div>
                    </div>

                    <!-- Video Settings -->
                    <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                        <div class="row">
                            <div class="col-md-6">
                                @select([
                                    'name' => 'video_provider',
                                    'label' => __('elearning::setting.video_provider'),
                                    'options' => [
                                        [
                                            'value' => 'server',
                                            'label' => 'Server',
                                        ],
                                        [
                                            'value' => 'bunny',
                                            'label' => 'Bunny',
                                        ],
                                        [
                                            'value' => 'cloudflare',
                                            'label' => 'Cloudflare',
                                        ],
                                        [
                                            'value' => 'vimeo',
                                            'label' => 'Vimeo',
                                        ],
                                        [
                                            'value' => 'digitalocean',
                                            'label' => 'Digital Ocean',
                                        ],
                                    ],
                                ])
                                @input(['type' => 'text', 'name' => 'video_bunny_library_id', 'label' => __('elearning::setting.video_bunny_library_id')])
                                @input(['type' => 'text', 'name' => 'video_bunny_api_key', 'label' => __('elearning::setting.video_bunny_api_key')])
                                @input(['type' => 'text', 'name' => 'video_bunny_storage_zone', 'label' => __('elearning::setting.video_bunny_storage_zone')])

                                @input(['type' => 'text', 'name' => 'video_cloudflare_url', 'label' => __('elearning::setting.video_cloudflare_url'),])
                                @input(['type' => 'text', 'name' => 'video_cloudflare_api_key', 'label' => __('elearning::setting.video_cloudflare_api_key'),])
                                @input(['type' => 'text', 'name' => 'video_cloudflare_api_secret', 'label' => __('elearning::setting.video_cloudflare_api_secret'),])

                                @input(['type' => 'text', 'name' => 'video_vimeo_url', 'label' => __('elearning::setting.video_vimeo_url'),])
                                @input(['type' => 'text', 'name' => 'video_vimeo_api_key', 'label' => __('elearning::setting.video_vimeo_api_key'),])
                                @input(['type' => 'text', 'name' => 'video_vimeo_api_secret', 'label' => __('elearning::setting.video_vimeo_api_secret'),])

                                @input(['type' => 'text', 'name' => 'video_digitalocean_url', 'label' => __('elearning::setting.video_digitalocean_url'),])
                                @input(['type' => 'text', 'name' => 'video_digitalocean_api_key', 'label' => __('elearning::setting.video_digitalocean_api_key'),])
                                @input(['type' => 'text', 'name' => 'video_digitalocean_api_secret', 'label' => __('elearning::setting.video_digitalocean_api_secret'),])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
            </div>
        </div>
    </form>
@stop
