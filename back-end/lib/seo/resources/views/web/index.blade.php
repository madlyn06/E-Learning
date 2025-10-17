@extends('master')

@section('meta')
    @seometa(['item' => null])
@stop

@section('body-class', 'home-page')

@section('content')
    @include('index')
@stop
