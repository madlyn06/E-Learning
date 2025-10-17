@extends('core::admin.master')

@section('meta_title', __('cms::story.setting.index.page_title'))

@section('page_title', __('cms::story.setting.index.page_title'))

@section('page_subtitle', __('cms::story.setting.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cms.admin.stories.index') }}">{{ trans('cms::story.setting.index.breadcrumb') }}</a></li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('cms.admin.story.setting.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('cms::story.setting.index.page_title') }}
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
                    <div class="col-md-4">
                        @input(['name' => 'story_image_min', 'label' => __('cms::story.setting.story_image_min'), 'default' => 5])
                    </div>
                    <div class="col-md-4">
                        @input(['name' => 'story_image_max', 'label' => __('cms::story.setting.story_image_max'), 'default' => 10])
                    </div>
                    <div class="col-md-4">
                        @input(['name' => 'story_image_transfer', 'label' => __('cms::story.setting.story_image_transfer'), 'default' => 7])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        @input(['name' => 'story_text_link', 'label' => __('cms::story.setting.story_text_link'), 'default' => 'Xem thêm'])
                    </div>
                    <div class="col-md-7">
                        @select(['name' => 'story_text_point', 'allowClear' => false, 'label' => __('cms::story.setting.story_text_point'), 'options' => get_story_text_point_options()])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    @select(['name' => 'story_insert_to', 'allowClear' => false, 'label' => __('cms::story.setting.story_insert_to'), 'options' => get_story_position_in_post_options()])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    @checkbox(['name' => 'story_is_auto_create', 'label' => '', 'placeholder' => __('cms::story.setting.story_is_auto_create')])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    @sumoselect(['name' => 'story_is_auto_create_from', 'allowClear' => false, 'multiple' => true, 'label' => __('cms::story.setting.story_is_auto_create_from'), 'options' => get_category_parent_options()])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    @checkbox(['name' => 'story_is_draft', 'label' => '', 'placeholder' => __('cms::story.setting.story_is_draft'), 'default' => false])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    @checkbox(['name' => 'story_is_auto_delete', 'label' => '', 'placeholder' => __('cms::story.setting.story_is_auto_delete'), 'default' => true])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    @mediamanager(['name' => 'story_audio', 'label' => __('cms::story-item.audio'),])
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
