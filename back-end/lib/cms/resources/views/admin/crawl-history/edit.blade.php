@extends('core::admin.master')

@section('meta_title', __('Sửa bài viết khi dịch'))

@section('page_title', __('Sửa bài viết khi dịch'))

@section('page_subtitle', __('Sửa bài viết khi cào & dịch'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a
                    href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cms.admin.crawl-history.index') }}">{{ trans('Crawl') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('Error') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('cms.admin.crawl-history.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id }}">

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('Sửa bài viết') }}
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
                    <div class="col-12 col-md-9">
                        @input(['name' => 'name', 'label' => __('cms::post.name'), 'required' => true])
                        @textarea(['name' => 'description', 'label' => __('cms::post.description'), 'autoResize' => true])
                        @editor(['name' => 'content', 'label' => __('cms::post.content')])
                    </div>
                    <div class="col-12 col-md-3">
                        @checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('cms::post.is_active'), 'default' => true])
                        @checkbox(['name' => 'is_created_story', 'label' => '', 'placeholder' => __('cms::post.is_created_story')])
                        @checkbox(['name' => 'append_internal_link', 'label' => '', 'placeholder' => __('cms::post.append_internal_link'),])
        
                        <div class="allway-open-sumoselect">
                            @sumoselect(['name' => 'categories', 'label' => __('cms::post.category'), 'multiple' => true, 'options' => get_category_parent_options()])
                        </div>
                        @if(config('cms.cms.media_manager') && config('cms.cms.media_manager') == true)
                        @mediamanager(['name' => 'image', 'label' => __('cms::post.image')])
                        @else
                        @mediafile(['name' => 'image', 'label' => __('cms::post.image')])
                        @endif
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
