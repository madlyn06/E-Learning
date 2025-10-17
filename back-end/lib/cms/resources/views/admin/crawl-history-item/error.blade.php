@extends('core::admin.master')

@section('meta_title', __('Lỗi crawl'))

@section('page_title', __('Lỗi crawl'))

@section('page_subtitle', __('Chi tiết lỗi khi cào'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cms.admin.crawl-history.index') }}">{{ trans('History') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cms.admin.crawl-history-item.index', $item->crawlHistory->id) }}">{{ trans('Crawl') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('Error') }}</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('Chi tiết lỗi') }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            @textarea(['name' => 'message', 'label' => __('Chi tiết lỗi'), 'autoResize' => true])
        </div>
    </div>
@stop
