@extends('master')

@section('meta_title', object_get($post, 'seometa.title') ?: $post->name)

@section('meta')
    @seometa(['item' => $post])
@stop

@section('body-class', 'post-detail')

@section('content')
    @includeFirst(['contents.post', "cms::web.post.{$post->id}", 'cms::web.post.content'])
@stop
