@extends('master')

@section('meta_title', object_get($story, 'seometa.title') ?: $story->name)

@section('meta')
    @seometa(['item' => $story])
@stop

@section('body-class', 'post-detail')

@section('content')
    @includeFirst(['contents.story', "cms::web.story.{$story->id}", 'cms::web.story.content'])
@stop
