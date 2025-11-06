@extends('core::admin.master')

@section('meta_title', __('elearning::payment_method.config.page_title'))

@section('page_title', __('elearning::payment_method.config.page_title'))

@section('page_subtitle', __('elearning::payment_method.config.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('elearning.admin.payment-methods.index') }}">{{ trans('elearning::payment_method.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('elearning.admin.payment-methods.edit', $item->id) }}">{{ trans('elearning::payment_method.edit.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('elearning::payment_method.config.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('elearning.admin.payment-methods.config.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('elearning::payment_method.config.page_title') }}
                        </h6>
                    </div>
                    <div class="text-right">
                        <div class="btn-group">
                            <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client_id">{{ __('elearning::payment_method.client_id') }}</label>
                            <input type="text" class="form-control" id="client_id" name="config[client_id]" value="{{ $item->config['client_id'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="api_key">{{ __('elearning::payment_method.api_key') }}</label>
                            <input type="text" class="form-control" id="api_key" name="config[api_key]" value="{{ $item->config['api_key'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="partner_code">{{ __('elearning::payment_method.partner_code') }}</label>
                            <input type="text" class="form-control" id="partner_code" name="config[partner_code]" value="{{ $item->config['partner_code'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="checksum_key">{{ __('elearning::payment_method.checksum_key') }}</label>
                            <input type="text" class="form-control" id="checksum_key" name="config[checksum_key]" value="{{ $item->config['checksum_key'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                </div>
            </div>
        </div>
    </form>
@stop
