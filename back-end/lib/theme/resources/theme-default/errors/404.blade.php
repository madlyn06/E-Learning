@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))

@if(setting('redirect_404_to_home'))
    @section('footer')
        <script>
            setTimeout(function () {
                window.location.href = '{{ url('/') }}';
            }, 2000);
        </script>
    @stop
@endif
