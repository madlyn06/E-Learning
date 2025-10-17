@extends('core::admin.master')

@section('meta_title', __('seo::short-link.index.page_title'))

@section('page_title', __('seo::short-link.index.page_title'))

@section('page_subtitle', __('seo::short-link.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('seo::short-link.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('seo::short-link.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('seo.admin.short-link.create')
	                        <a href="{{ route('seo.admin.short-links.create') }}" class="action-item">
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
                <a href="{{ route('seo.admin.short-links.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th nowrap>{{ __('seo::short-link.code') }}</th>
                        <th>{{ __('seo::short-link.content_urls') }}</th>
                        <th nowrap>{{ __('seo::short-link.text') }}</th>
                        <th nowrap>{{ __('seo::short-link.is_active') }}</th>
                        <th>@translatableHeader</th>
                        <th nowrap>{{ __('seo::short-link.created_at') }}</th>
                        <th nowrap></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->index + $items->firstItem() }}</td>
                            <td nowrap>link_short_code[{{ $item->code }}]</td>
                            <td>{{ $item->content_urls }}</td>
                            <td nowrap>{{ $item->text }}</td>
                            <td nowrap>
                                @if($item->is_active)
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </td>
                            <td>@translatableStatus(['editUrl' => route('seo.admin.short-links.edit', $item->id)])</td>
                            <td nowrap>{{ $item->created_at }}</td>
                            <td nowrap class="text-right">
                                @admincan('seo.admin.short-link.edit')
                                    <a href="{{ route('seo.admin.short-links.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan

                                @admincan('seo.admin.short-link.destroy')
                                    <table-button-delete url-delete="{{ route('seo.admin.short-links.destroy', $item->id) }}"></table-button-delete>
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
