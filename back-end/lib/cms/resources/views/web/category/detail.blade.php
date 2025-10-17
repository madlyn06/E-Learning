@extends('master')

@section('meta_title', object_get($category, 'seometa.title') ?: $category->name)

@section('meta')
    @seometa(['item' => $category])
@stop

@section('body-class', 'category-detail')

@section('content')
    @includeFirst(['contents.category', "cms::web.category.{$category->id}", 'cms::web.category.content'])
@stop
