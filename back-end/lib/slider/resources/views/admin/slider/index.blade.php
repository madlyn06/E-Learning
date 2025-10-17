@extends('core::admin.master')

@section('meta_title', __('slider::slider.index.page_title'))

@section('page_title', __('slider::slider.index.page_title'))

@section('page_subtitle', __('slider::slider.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('slider::slider.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('slider::slider.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('slider.admin.slider.create')
	                        <a href="{{ route('slider.admin.slider.create') }}" class="action-item">
	                            <i class="fa fa-plus"></i>
	                            {{ __('core::button.add') }}
	                        </a>
                        @endadmincan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('slider::slider.name') }}</th>
                    <th>{{ __('slider::slider.slug') }}</th>
                    <th>{{ __('slider::slider.layout') }}</th>
                    <th>{{ __('slider::slider.is_active') }}</th>
                    <th>{{ __('slider::slider.created_at') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <a href="{{ route('slider.admin.slider.edit', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>
                            <code>{<span>!!</span> SliderRender::render('{{ $item->slug }}') !!}</code>
                        </td>
                        <td>{{ get_slider_layout_label($item->layout) }}</td>
                        <td>
                            @if($item->is_active)
                                <i class="fa fa-check text-success"></i>
                            @else
                                <i class="fa fa-times text-inverse"></i>
                            @endif
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td class="text-right">
                            @admincan('slider.admin.slider.edit')
                                <a href="{{ route('slider.admin.slider-item.index', $item->id) }}" class="btn btn-info-soft btn-sm mr-1" title="{{ trans('slider::slider-item.builder') }}">
                                    <i class="fas fa-drafting-compass"></i>
                                </a>
                            @endadmincan

                        	@admincan('slider.admin.slider.edit')
	                            <a href="{{ route('slider.admin.slider.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
	                                <i class="fas fa-pencil-alt"></i>
	                            </a>
                            @endadmincan

                            @admincan('slider.admin.slider.destroy')
                            	<table-button-delete url-delete="{{ route('slider.admin.slider.destroy', $item->id) }}"></table-button-delete>
                            @endadmincan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{--{!! $items->appends(Request::all())->render() !!}--}}
        </div>
    </div>
@stop
