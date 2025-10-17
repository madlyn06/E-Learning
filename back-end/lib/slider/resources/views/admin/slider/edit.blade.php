@extends('core::admin.master')

@section('meta_title', __('slider::slider.edit.page_title'))

@section('page_title', __('slider::slider.edit.page_title'))

@section('page_subtitle', __('slider::slider.edit.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('slider.admin.slider.index') }}">{{ trans('slider::slider.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('slider::slider.edit.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
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
                        @foreach($items as $sliderItem)
                            <tr>
                                <td>{{ $sliderItem->id }}</td>
                                <td>
                                    <a href="{{ route('slider.admin.slider-item.edit', $sliderItem->id) }}" style="height: 100px; display: block;">
                                        <img src="{{ $sliderItem->getFirstMediaUrl('image') }}" alt="{{ $sliderItem->name }}" class="img-thumbnail">
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('slider.admin.slider-item.edit', $sliderItem->id) }}">
                                        {{ $sliderItem->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($sliderItem->is_active)
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-times text-inverse"></i>
                                    @endif
                                </td>
                                <td>{{ $sliderItem->sort_order }}</td>
                                <td>{{ $sliderItem->created_at }}</td>
                                <td>
                                    @translatableStatus(['editUrl' => route('slider.admin.slider-item.edit', $sliderItem->id), 'item' => $sliderItem])
                                </td>
                                <td class="text-right">
                                    @admincan('slider.admin.slider.edit')
                                    <a href="{{ route('slider.admin.slider-item.edit', $sliderItem->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    @endadmincan

                                    @admincan('slider.admin.slider.destroy')
                                    <table-button-delete url-delete="{{ route('slider.admin.slider-item.destroy', $sliderItem->id) }}"></table-button-delete>
                                    @endadmincan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <form action="{{ route('slider.admin.slider.update', $item->id) }}" method="POST">
                @method('PUT')
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">
                                    {{ __('slider::slider.edit.page_title') }}
                                </h6>
                            </div>
                            <div class="text-right">
                                <div class="btn-group">
                                    <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                                    <button class="btn btn-primary" name="continue" value="1" type="submit">{{ __('core::button.save_and_edit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('slider::admin.slider._fields', ['item' => $item])
                    </div>
                    <div class="card-footer text-right">
                        <div class="btn-group">
                            <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                            <button class="btn btn-primary" name="continue" value="1" type="submit">{{ __('core::button.save_and_edit') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
