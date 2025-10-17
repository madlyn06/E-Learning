@extends('core::admin.master')

@section('meta_title', __('contact::contact.index.page_title'))

@section('page_title', __('contact::contact.index.page_title'))

@section('page_subtitle', __('contact::contact.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('contact::contact.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('contact::contact.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('contact.admin.contact.create')
	                        <a href="{{ route('contact.admin.contact.create') }}" class="action-item">
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
                @input(['item' => null, 'name' => 'search', 'label' => __('Search'), 'value' => request('search')])
                @select(['item' => null, 'name' => 'label', 'label' => __('contact::contact.label'), 'value' => request('label'), 'options' => get_contact_label_options()])

                <button type="submit" class="btn btn-primary mr-1">
                    {{ __('core::button.search') }}
                </button>
                <a href="{{ route('contact.admin.contact.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('contact::contact.info') }}</th>
                    <th>{{ __('contact::contact.label') }}</th>
                    <th>{{ __('contact::contact.note') }}</th>
                    <th>{{ __('contact::contact.content') }}</th>
                    <th>{{ __('contact::contact.created_at') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <a href="#">{{ mb_strtoupper($item->name) }} </a>
                            @if ($item->is_handle) <i class="fas fa-check text-success"></i> @endif
                            <br>
                            {{ $item->email }} | {{ $item->phone }}
                        </td>
                        <td>@if ($item->label_id) {!! $item->label->button() !!} @endif</td>
                        <td>{!! implode('<br>', explode("\n", $item->note)) !!}</td>
                        <td>{{ $item->content }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td class="text-right">
                            @admincan('contact.admin.contact.edit')
                                <a href="{{ route('contact.admin.contact.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endadmincan
                            @admincan('contact.admin.contact.destroy')
                            	<table-button-delete url-delete="{{ route('contact.admin.contact.destroy', $item->id) }}"></table-button-delete>
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
