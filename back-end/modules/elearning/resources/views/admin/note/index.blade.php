@extends('core::admin.master')

@section('meta_title', __('elearning::note.index.page_title'))

@section('page_title', __('elearning::note.index.page_title'))

@section('page_subtitle', __('elearning::note.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::note.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('elearning::note.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('elearning.admin.notes.create')
                            <a href="{{ route('elearning.admin.notes.create') }}" class="action-item">
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
                <div class="form-group mb-2 mr-1">
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('core::button.search') }}">
                </div>
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fa fa-search"></i>
                </button>
                <button type="button" class="btn btn-danger mb-2 ml-1" onclick="window.location.href='{{ route('elearning.admin.notes.index') }}'">
                    <i class="fa fa-times"></i>
                </button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                    <tr>
                        <th>{{ __('elearning::note.id') }}</th>
                        <th>{{ __('elearning::note.user') }}</th>
                        <th>{{ __('elearning::note.lesson') }}</th>
                        <th>{{ __('elearning::note.content') }}</th>
                        <th>{{ __('elearning::note.time') }}</th>
                        <th>{{ __('elearning::note.created_at') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->user->name ?? '' }}</td>
                            <td>{{ $item->lesson->title ?? '' }}</td>
                            <td>{{ Str::limit($item->content, 50) }}</td>
                            <td>{{ $item->time_iso }}</td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @admincan('elearning.admin.notes.edit')
                                    <a href="{{ route('elearning.admin.notes.edit', $item->id) }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endadmincan

                                @admincan('elearning.admin.notes.destroy')
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('elearning.admin.notes.destroy', $item->id) }}')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endadmincan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{ $items->appends(request()->all())->links() }}
        </div>
    </div>
@stop
