@extends('core::admin.master')

@section('meta_title', __('cms::satellite.index.page_title'))

@section('page_title', __('cms::satellite.index.page_title'))

@section('page_subtitle', __('cms::satellite.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('cms::satellite.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('cms::satellite.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('cms.admin.satellite.create')
                            <a href="{{ route('cms.admin.satellite.create') }}" class="action-item">
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
                <a href="{{ route('cms.admin.page.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('cms::satellite.name') }}</th>
                    <th>{{ __('cms::satellite.url') }}</th>
                    <th>{{ __('cms::satellite.category') }}</th>
                    <th>{{ __('cms::satellite.is_active') }}</th>
                    <th>{{ __('cms::satellite.created_at') }}</th>
                    <th>@translatableHeader</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->index + $items->firstItem() }}</td>
                        <td>
                            <a href="{{ route('cms.admin.satellite.edit', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>{{ $item->url }}</td>
                        <td>{{ $item->category->name ?? null }}</td>
                        <td>
                            @if($item->is_active)
                                <i class="fas fa-check text-success"></i>
                            @endif
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            @translatableStatus(['editUrl' => route('cms.admin.satellite.edit', $item->id)])
                        </td>
                        <td class="text-right">
                            @admincan('cms.admin.satellite.edit')
                                <a href="{{ route('cms.admin.satellite.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endadmincan

                            @admincan('cms.admin.satellite.destroy')
                                <table-button-delete url-delete="{{ route('cms.admin.satellite.destroy', $item->id) }}"></table-button-delete>
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
