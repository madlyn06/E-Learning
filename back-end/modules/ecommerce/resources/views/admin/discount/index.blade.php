@extends('core::admin.master')

@section('meta_title', __('ecommerce::discount.index.page_title'))

@section('page_title', __('ecommerce::discount.index.page_title'))

@section('page_subtitle', __('ecommerce::discount.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('ecommerce::discount.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('ecommerce::discount.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('ecommerce.admin.discount.create')
	                        <a href="{{ route('ecommerce.admin.discounts.create') }}" class="action-item">
	                            <i class="fa fa-plus"></i>
	                            {{ __('core::button.add') }}
	                        </a>
                        @endadmincan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="form-inline newnet-table-search">
                <x-search-input/>
                <button type="submit" class="btn btn-primary mr-1">
                    {{ __('core::button.search') }}
                </button>
                <a href="{{ route('ecommerce.admin.discounts.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th>{{ __('ecommerce::discount.name') }}</th>
                        <th>{{ __('ecommerce::discount.type') }}</th>
                        <th>{{ __('ecommerce::discount.value') }}</th>
                        <th>{{ __('ecommerce::discount.valid_from') }}</th>
                        <th>{{ __('ecommerce::discount.valid_to') }}</th>
                        <th>{{ __('ecommerce::discount.is_active') }}</th>
                        <th>{{ __('ecommerce::discount.created_at') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->index + $items->firstItem() }}</td>
                            <td>
                                <a href="{{ route('ecommerce.admin.discounts.edit', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->type == 'percent' ? $item->value . '%' :  format_money($item->value) . ' đ' }}</td>
                            <td>{{ $item->valid_from ? $item->valid_from->format('Y-m-d H:i:s') : '' }}</td>
                            <td>{{ $item->valid_to ? $item->valid_to->format('Y-m-d H:i:s') : ''}}</td>
                            <td>
                                @if($item->is_active)
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td class="text-right">
                                @admincan('ecommerce.admin.discount.edit')
                                    <a href="{{ route('ecommerce.admin.discounts.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan

                                @admincan('ecommerce.admin.discount.destroy')
                                    <table-button-delete url-delete="{{ route('ecommerce.admin.discounts.destroy', $item->id) }}"></table-button-delete>
                                @endadmincan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {!! $items->appends(Request::all())->render() !!}
        </div>
    </div>
@stop
