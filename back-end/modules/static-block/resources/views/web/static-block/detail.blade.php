@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'static-block-detail')

@section('content')
    @includeFirst(['staticblock::web.static-block.content'])
@stop
