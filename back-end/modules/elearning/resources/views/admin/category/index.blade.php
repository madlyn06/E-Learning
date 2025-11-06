@extends('core::admin.master')

@section('meta_title', __('elearning::category.index.page_title'))

@section('page_title', __('elearning::category.index.page_title'))

@section('page_subtitle', __('elearning::category.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::category.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('elearning::category.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('elearning.admin.categories.create')
                            <a href="{{ route('elearning.admin.categories.create') }}" class="action-item">
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
                <a href="{{ route('elearning.admin.categories.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
                <a href="#" data-toggle="modal" data-target="#deleteItem" class="btn btn-danger ml-2">
                    Delete
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>{{ __('elearning::category.id') }}</th>
                            <th nowrap>{{ __('elearning::category.name') }}</th>
                            <th nowrap>{{ __('elearning::category.parent') }}</th>
                            <th>{{ __('elearning::category.is_active') }}</th>
                            <th nowrap>{{ __('elearning::category.created_at') }}</th>
                            <th nowrap></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td nowrap>
                                    <a href="{{ route('cms.admin.category.edit', $item->id) }}">
                                        {{ trim(str_pad('', $item->depth * 3, '-')) }}
                                        {{ $item->name }}
                                    </a>
                                    <a href="{{ config('app.frontend_url') . '/' . Newnet\Core\Utils\Common::buildSlug($item->url) }}"
                                        target="_blank" title="{{ __('core::button.view') }}">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    {{ $item->parent ? $item->parent->name : '' }}
                                </td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="badge badge-success">{{ __('elearning::message.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('elearning::message.inactive') }}</span>
                                    @endif
                                </td>
                                <td nowrap>{{ $item->created_at }}</td>
                                <td class="text-right" nowrap>
                                    @admincan('elearning.admin.category.create')
                                        <a href="{{ route('elearning.admin.categories.create', ['id' => $item->id, 'parent_id' => $item->id]) }}"
                                            class="btn btn-primary-soft btn-sm mr-1">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    @endadmincan

                                    @admincan('elearning.admin.category.edit')
                                        <a href="{{ route('elearning.admin.categories.move-up', $item->id) }}"
                                            class="btn btn-info-soft btn-sm mr-1">
                                            <i class="fas fa-chevron-up"></i>
                                        </a>
                                    @endadmincan

                                    @admincan('elearning.admin.category.edit')
                                        <a href="{{ route('elearning.admin.categories.move-down', $item->id) }}"
                                            class="btn btn-info-soft btn-sm mr-1">
                                            <i class="fas fa-chevron-down"></i>
                                        </a>
                                    @endadmincan

                                    @admincan('elearning.admin.category.edit')
                                        <a href="{{ route('elearning.admin.categories.edit', $item->id) }}"
                                            class="btn btn-success-soft btn-sm mr-1">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endadmincan

                                    @admincan('elearning.admin.category.destroy')
                                        <table-button-delete
                                            url-delete="{{ route('elearning.admin.categories.destroy', $item->id) }}"></table-button-delete>
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
