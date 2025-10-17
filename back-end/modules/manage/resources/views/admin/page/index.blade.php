@extends('core::admin.master')

@section('meta_title', __('manage::page.index.page_title'))

@section('page_title', __('manage::page.index.page_title'))

@section('page_subtitle', __('manage::page.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('manage::page.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('manage::page.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('manage.admin.page.create')
	                        <a href="{{ route('manage.admin.pages.create') }}" class="action-item">
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
                <a href="{{ route('manage.admin.pages.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th>{{ __('manage::page.name') }}</th>
                        <th>{{ __('manage::page.sort_order') }}</th>
                        <th>{{ __('manage::page.is_active') }}</th>
                        <th>@translatableHeader</th>
                        <th>{{ __('manage::page.created_at') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->index + $items->firstItem() }}</td>
                            <td>
                                <a href="{{ route('manage.admin.pages.edit', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                @if($item->is_active)
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </td>
                            <td>@translatableStatus(['editUrl' => route('manage.admin.pages.edit', $item->id)])</td>
                            <td>{{ $item->created_at }}</td>
                            <td class="text-right">
                                @admincan('manage.admin.page.edit')
                                    <a href="{{ route('manage.admin.pages.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan

                                @admincan('manage.admin.page.destroy')
                                    <table-button-delete url-delete="{{ route('manage.admin.pages.destroy', $item->id) }}"></table-button-delete>
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
