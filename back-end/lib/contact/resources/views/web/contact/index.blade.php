@extends('master')

@section('meta_title', __('contact::menu.contact.index'))

@section('body-class', 'contact')

@section('content')
    @include('contact::web.contact.content')
@stop
