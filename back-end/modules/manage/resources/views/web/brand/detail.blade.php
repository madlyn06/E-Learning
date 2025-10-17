@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'brand-detail')

@section('content')
    @includeFirst(['manage::web.brand.content'])
@stop
