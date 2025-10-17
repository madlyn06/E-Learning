@extends('core::admin.master')

@section('meta_title', __('seo::error-redirect.index.page_title'))

@section('page_title', __('seo::error-redirect.index.page_title'))

@section('page_subtitle', __('seo::error-redirect.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('seo::error-redirect.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('seo::error-redirect.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('seo.admin.error-redirect.create')
                        <a href="{{ route('seo.admin.error-redirect.create') }}" class="action-item">
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
                @input(['item' => null, 'name' => 'from_path', 'label' => __('seo::error-redirect.from_path'), 'value' => request('from_path')])
                @input(['item' => null, 'name' => 'to_url', 'label' => __('seo::error-redirect.to_url'), 'value' => request('to_url')])

                <button type="submit" class="btn btn-primary mr-1">
                    {{ __('core::button.search') }}
                </button>
                <a href="{{ route('seo.admin.error-redirect.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>{{ __('#') }}</th>
                    <th>{{ __('seo::error-redirect.from_path') }}</th>
                    <th>{{ __('seo::error-redirect.to_url') }}</th>
                    <th>{{ __('seo::error-redirect.status_code') }}</th>
                    <th nowrap>{{ __('seo::error-redirect.created_at') }}</th>
                    <th nowrap></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->index + $items->firstItem() }}</td>
                        <td>
                            <a href="{{ route('seo.admin.error-redirect.edit', $item->id) }}">
                                {{ $item->from_path }}
                            </a>
                            <a href="{{ url($item->from_path) }}" class="text-success" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                        <td>
                            {{ $item->to_url }}
                            <a href="{{ url($item->to_url) }}" class="text-success" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                        <td>{{ $item->status_code }}</td>
                        <td nowrap>{{ $item->created_at }}</td>
                        <td nowrap class="text-right">
                            @admincan('seo.admin.error-redirect.edit')
                                <a href="{{ route('seo.admin.error-redirect.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endadmincan

                            @admincan('seo.admin.error-redirect.destroy')
                                <table-button-delete url-delete="{{ route('seo.admin.error-redirect.destroy', $item->id) }}"></table-button-delete>
                            @endadmincan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $items->appends(Request::all())->render() !!}
        </div>
    </div>
@stop
