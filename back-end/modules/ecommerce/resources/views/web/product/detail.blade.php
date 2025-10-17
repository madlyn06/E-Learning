@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'product-detail')

@section('content')
    @includeFirst(['ecommerce::web.product.content'])
@stop
