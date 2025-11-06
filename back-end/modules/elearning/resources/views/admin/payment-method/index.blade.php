@extends('core::admin.master')

@section('meta_title', __('elearning::payment_method.index.page_title'))

@section('page_title', __('elearning::payment_method.index.page_title'))

@section('page_subtitle', __('elearning::payment_method.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::payment_method.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('elearning::payment_method.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('elearning.admin.payment.create')
                            <a href="{{ route('elearning.admin.payment-methods.create') }}" class="action-item">
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

                <x-search-input />

                <button type="submit" class="btn btn-primary mr-1">
                    {{ __('core::button.search') }}
                </button>
                <a href="{{ route('elearning.admin.payment-methods.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('elearning::payment_method.name') }}</th>
                            <th>{{ __('elearning::payment_method.code') }}</th>
                            <th>{{ __('elearning::payment_method.is_active') }}</th>
                            <th>{{ __('elearning::payment_method.display_order') }}</th>
                            <th>{{ __('elearning::payment_method.created_at') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->code }}</td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="badge badge-success">{{ __('elearning::message.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('elearning::message.inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->display_order }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    @admincan('elearning.admin.payment_method.edit')
                                        <a href="{{ route('elearning.admin.payment-methods.config', $item->id) }}"
                                            class="btn btn-success-soft btn-sm mr-1">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    @endadmincan
                                    @admincan('elearning.admin.payment_method.edit')
                                        <a href="{{ route('elearning.admin.payment-methods.edit', $item->id) }}"
                                            class="btn btn-success-soft btn-sm mr-1">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endadmincan

                                    @admincan('elearning.admin.payment_method.destroy')
                                        <table-button-delete
                                            url-delete="{{ route('elearning.admin.payment-methods.destroy', $item->id) }}"></table-button-delete>
                                    @endadmincan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $items->links() }}
                <div class="text-right">
                    <a href="{{ route('elearning.admin.payment-methods.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        {{ __('core::button.add') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
