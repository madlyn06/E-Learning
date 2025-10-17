@extends('core::admin.master')

@section('meta_title', __('zone::township.index.page_title'))

@section('page_title', __('zone::township.index.page_title'))

@section('page_subtitle', __('zone::township.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('zone::township.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('zone::township.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        <a href="{{ route('zone.admin.township.create', request()->id) }}" class="action-item">
                            <i class="fa fa-plus"></i>
                            {{ __('core::button.add') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('zone::township.name') }}</th>
                    <th>{{ __('zone::township.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($townships as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            @if($item->status)
                                <i class="fas fa-check text-success"></i>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('zone.admin.township.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <table-button-delete url-delete="{{ route('zone.admin.township.destroy', $item->id) }}"></table-button-delete>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">
                        <a class="btn btn-secondary" href="{{route('zone.admin.district.index', isset($item) ? $item->district()->get()[0]->province_id : '')}}">Back To List</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop
