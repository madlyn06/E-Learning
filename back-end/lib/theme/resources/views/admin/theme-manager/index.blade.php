@extends('core::admin.master')

@section('meta_title', __('theme::theme-manager.index.page_title'))

@section('page_title', __('theme::theme-manager.index.page_title'))

@section('page_subtitle', __('theme::theme-manager.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('theme::theme-manager.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('theme::theme-manager.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($items as $key => $item)
                <div class="col-md-3 col-sm-6">
                    <div class="card theme-item mb-4">
                        <div class="nn-theme-image">
                            @if(empty($item['thumb']))
                                <img class="card-img-top"
                                     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAIAAADTED8xAAADMElEQVR4nOzVwQnAIBQFQYXff81RUkQCOyDj1YOPnbXWPmeTRef+/3O/OyBjzh3CD95BfqICMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMK0CMO0TAAD//2Anhf4QtqobAAAAAElFTkSuQmCC"
                                     alt="{{ $item['description'] }}">
                            @else
                                <img class="card-img-top"
                                     src="{{ $item['thumb'] }}"
                                     alt="{{ $item['description'] }}">
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $item['description'] ?? $item['name'] }}
                            </h5>

                            @if(admin_can('theme.admin.theme-manager.activate'))
                                <form action="{{ route('theme.admin.theme-manager.activate') }}" method="POST">
                                    @csrf

                                    @if($item['active'] == false)
                                        <input type="text" name="theme_name" value="{{ $item['name'] }}" hidden>
                                        <button class="btn btn-success js-active-theme" type="submit">{{ __('theme::theme-manager.activate') }}</button>
                                    @else
                                        <input type="text" name="theme_name" value="{{ $item['name'] }}" hidden>
                                        <button disabled class="btn btn-info" type="submit">{{ __('theme::theme-manager.activated') }}</button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop

