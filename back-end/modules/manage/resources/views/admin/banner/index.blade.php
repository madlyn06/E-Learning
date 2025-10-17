@extends('admin::master')

@section('meta_title', __('manage::banner.index.page_title'))

@section('page_title', __('manage::banner.index.page_title'))

@section('page_subtitle', __('manage::banner.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('manage::banner.index.breadcrumb') }}</li>
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
                            {{ __('manage::banner.index.page_title') }}
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
                        @mediamanager(['name' => 'banner_contact', 'label' => __('manage::banner.contact')])
                        @mediamanager(['name' => 'banner_why_choose_us', 'label' => __('manage::banner.why_choose_us')])
                        @mediamanager(['name' => 'banner_support', 'label' => __('manage::banner.support')])
                        @mediamanager(['name' => 'banner_consulting', 'label' => __('manage::banner.consulting')])
                        @mediamanager(['name' => 'banner_testimonal', 'label' => __('manage::banner.testimonal')])
                        @mediamanager(['name' => 'banner_blog', 'label' => __('manage::banner.blog')])
                        @mediamanager(['name' => 'banner_category', 'label' => __('manage::banner.category')])
                        @mediamanager(['name' => 'banner_service', 'label' => __('manage::banner.service')])
                        @mediamanager(['name' => 'banner_team', 'label' => __('manage::banner.team')])
                        @mediamanager(['name' => 'banner_faqs', 'label' => __('manage::banner.faqs')])
                        @mediamanager(['name' => 'banner_portfolio', 'label' => __('manage::banner.portfolio')])
                        @mediamanager(['name' => 'banner_stories', 'label' => __('manage::banner.story')])
                        @mediamanager(['name' => 'banner_404', 'label' => __('manage::banner.404')])
                        @mediamanager(['name' => 'banner_shop', 'label' => __('manage::banner.shop')])
                        @mediamanager(['name' => 'banner_download', 'label' => __('manage::banner.download')])
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

