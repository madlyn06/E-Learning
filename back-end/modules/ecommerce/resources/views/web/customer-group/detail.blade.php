@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'customer-group-detail')

@section('content')
    @includeFirst(['ecommerce::web.customer-group.content'])
@stop
