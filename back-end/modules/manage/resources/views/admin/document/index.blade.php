@extends('core::admin.master')

@section('meta_title', __('manage::document.index.page_title'))

@section('page_title', __('manage::document.index.page_title'))

@section('page_subtitle', __('manage::document.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('manage::document.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('manage::document.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('manage.admin.document.create')
	                        <a href="{{ route('manage.admin.documents.create') }}" class="action-item">
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
                <a href="{{ route('manage.admin.documents.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th>{{ __('manage::document.name') }}</th>
                        <th>{{ __('Mã download') }}</th>
                        <th>{{ __('manage::document.file_version') }}</th>
                        <th>{{ __('manage::document.file_size') }}</th>
                        <th nowrap>{{ __('manage::document.is_active') }}</th>
                        <th>@translatableHeader</th>
                        <th nowrap>{{ __('manage::document.created_at') }}</th>
                        <th nowrap></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->index + $items->firstItem() }}</td>
                            <td>
                                <a href="{{ route('manage.admin.documents.edit', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td>
                                @if ($item->download_code)
                                    <a target="_blank" href="{{ route('seo.admin.ads.edit', get_ads_id_by_code($item->download_code)) }}">{{ $item->download_code }}</a>
                                @endif
                            </td>
                            <td>{{ $item->file_version }}</td>
                            <td>{{ $item->file_size }} MB</td>
                            <td nowrap>
                                @if($item->is_active)
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </td>
                            <td>@translatableStatus(['editUrl' => route('manage.admin.documents.edit', $item->id)])</td>
                            <td nowrap>{{ $item->created_at }}</td>
                            <td nowrap class="text-right">
                                @admincan('manage.admin.document.edit')
                                    <a href="{{ route('manage.admin.documents.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan

                                @admincan('manage.admin.document.destroy')
                                    <table-button-delete url-delete="{{ route('manage.admin.documents.destroy', $item->id) }}"></table-button-delete>
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
