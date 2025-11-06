@extends('core::admin.master')

@section('meta_title', __('elearning::payment_method.edit.page_title'))

@section('page_title', __('elearning::payment_method.edit.page_title'))

@section('page_subtitle', __('elearning::payment_method.edit.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('elearning.admin.payment-methods.index') }}">{{ trans('elearning::payment_method.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::payment_method.edit.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('elearning.admin.payment-methods.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('elearning::payment_method.edit.page_title') }}
                        </h6>
                    </div>
                    <div class="text-right">
                        @if($item)
                            <a class="btn btn-primary" href="{{ route('elearning.admin.payment-methods.config', $item->id) }}">Cấu hình</a>
                        @endif
                        <div class="btn-group">
                            <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('elearning::admin.payment-method._fields', ['item' => $item])
            </div>
            <div class="card-footer text-right">
                @if($item)
                    <a class="btn btn-primary" href="{{ route('elearning.admin.payment-methods.config', $item->id) }}">Cấu hình</a>
                @endif
                <div class="btn-group">
                    <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                </div>
            </div>
        </div>
    </form>
@stop
