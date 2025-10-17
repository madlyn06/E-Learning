@extends('core::admin.master')

@section('meta_title', __('dashboard::message.index.page_title'))

@section('content-header', '')

@section('content')
    <div class="row">
        @foreach($myDashboard as $dashboard)
            <div class="col-md-{{ $dashboard->col() }} mb-4">
                {{ $dashboard }}
            </div>
        @endforeach
    </div>
@stop
