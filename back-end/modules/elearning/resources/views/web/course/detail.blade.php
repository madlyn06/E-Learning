@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'course-detail')

@section('content')
    @includeFirst(['elearning::web.course.content'])
@stop
