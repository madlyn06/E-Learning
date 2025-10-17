@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'portfolio-category-detail')

@section('content')
    @includeFirst(['manage::web.portfolio-category.content'])
@stop
