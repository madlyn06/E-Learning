@extends('master')

@section('meta_title', object_get($item, 'seometa.title') ?: $item->$item)

@section('meta')
    @seometa(['item' => $item])
@stop

@section('body-class', 'lesson-detail')

@section('content')
    @includeFirst(['elearning::web.lesson.content'])
@stop
