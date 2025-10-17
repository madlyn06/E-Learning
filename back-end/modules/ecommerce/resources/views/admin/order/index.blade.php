@extends('core::admin.master')

@section('meta_title', __('ecommerce::order.index.page_title'))

@section('page_title', __('ecommerce::order.index.page_title'))

@section('page_subtitle', __('ecommerce::order.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('ecommerce::order.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('ecommerce::order.index.page_title') }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="form-inline newnet-table-search">
                {{-- <x-search-input/> --}}
                <div class="form-group row component-keyword">
                    <div class="col-12">
                        <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" placeholder="Nhập mã đơn hàng hoặc email người mua" class="form-control" style="width: 20rem;">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mr-1">
                    {{ __('core::button.search') }}
                </button>
                <a href="{{ route('ecommerce.admin.order.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th>{{ __('ecommerce::order.order_no') }}</th>
                        <th>{{ __('ecommerce::order.email') }}</th>
                        <th>{{ __('ecommerce::order.order_status') }}</th>
                        <th>{{ __('ecommerce::order.payment_status') }}</th>
                        <th>{{ __('ecommerce::order.total_price') }}</th>
                        <th>{{ __('ecommerce::order.created_at') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->index + $items->firstItem() }}</td>
                            <td>
                                <a href="{{ route('ecommerce.admin.order.edit', $item->id) }}">
                                    {{ $item->order_no }}
                                </a>
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->payment_status }}</td>
                            <td>@vnmoney($item->total_price) ₫</td>
                            <td>{{ $item->created_at }}</td>
                            <td class="text-right">
                                @admincan('ecommerce.admin.order.edit')
                                    <a href="{{ route('ecommerce.admin.order.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan

                                @admincan('ecommerce.admin.order.destroy')
                                    <table-button-delete url-delete="{{ route('ecommerce.admin.order.destroy', $item->id) }}"></table-button-delete>
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
