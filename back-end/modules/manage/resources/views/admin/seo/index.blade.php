@extends('core::admin.master')

@section('meta_title', __('manage::seo.index.page_title'))

@section('page_title', __('manage::seo.index.page_title'))

@section('page_subtitle', __('manage::seo.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('manage::seo.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('manage::seo.index.page_title') }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th>{{ __('manage::seo.name') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                              <a href="{{ route('manage.admin.seo.edit', $item['key']) }}">
                                {{ $item['name'] }}
                              </a>
                            </td>
                            <td class="text-right">
                                @admincan('manage.admin.seo.edit')
                                    <a href="{{ route('manage.admin.seo.edit', $item['key']) }}" class="btn btn-success-soft btn-sm mr-1">
                                      <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan
                            </td>
                        </tr>
                        @php($key ++)
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
