@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'category-detail')

@section('content')
    @includeFirst(['ecommerce::web.category.content'])
@stop
