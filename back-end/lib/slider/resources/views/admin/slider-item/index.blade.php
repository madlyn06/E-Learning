@extends('core::admin.master')

@section('meta_title', __('slider::slider-item.index.page_title'))

@section('page_title', __('slider::slider-item.index.page_title'))

@section('page_subtitle', $slider->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('slider.admin.slider.index') }}">{{ trans('slider::slider.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ $slider->name }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ $slider->name }}
                    </h6>
                    <div>
                        <code>{<span>!!</span> SliderRender::render('{{ $slider->slug }}') !!}</code>
                    </div>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('slider.admin.slider.create')
	                        <a href="{{ route('slider.admin.slider-item.create', ['slider_id' => $slider->id]) }}" class="action-item">
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
                    <th>{{ __('slider::slider-item.image') }}</th>
                    <th>{{ __('slider::slider-item.name') }}</th>
                    <th>{{ __('slider::slider-item.is_active') }}</th>
                    <th>{{ __('slider::slider-item.sort_order') }}</th>
                    <th>{{ __('slider::slider-item.created_at') }}</th>
                    <th>@translatableHeader</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <a href="{{ route('slider.admin.slider-item.edit', $item->id) }}" style="height: 100px; display: block;">
                                <img src="{{ $item->getFirstMediaUrl('image') }}" alt="{{ $item->name }}" class="img-thumbnail">
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('slider.admin.slider-item.edit', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>
                            @if($item->is_active)
                                <i class="fa fa-check text-success"></i>
                            @else
                                <i class="fa fa-times text-inverse"></i>
                            @endif
                        </td>
                        <td>{{ $item->sort_order }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            @translatableStatus(['editUrl' => route('slider.admin.slider-item.edit', $item->id)])
                        </td>
                        <td class="text-right">
                        	@admincan('slider.admin.slider-item.edit')
	                            <a href="{{ route('slider.admin.slider-item.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
	                                <i class="fas fa-pencil-alt"></i>
	                            </a>
                            @endadmincan

                            @admincan('slider.admin.slider-item.destroy')
                            	<table-button-delete url-delete="{{ route('slider.admin.slider-item.destroy', $item->id) }}"></table-button-delete>
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
