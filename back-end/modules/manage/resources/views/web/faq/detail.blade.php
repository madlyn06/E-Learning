@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'faq-detail')

@section('content')
    @includeFirst(['manage::web.faq.content'])
@stop
