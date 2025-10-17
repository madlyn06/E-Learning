@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'customer-detail')

@section('content')
    @includeFirst(['ecommerce::web.customer.content'])
@stop
