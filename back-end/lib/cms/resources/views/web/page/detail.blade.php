@extends('master')

@section('meta_title', object_get($page, 'seometa.title') ?: $page->name)

@section('meta')
    @seometa(['item' => $page])
@stop

@section('body-class', "page-detail page-layout-{$page->page_layout}")

@section('content')
    @includeFirst([
        $page->page_layout,
        "pages.{$page->page_layout}",
        "pages.{$page->id}",
        PageLayout::getView($page->page_layout),
        'contents.page',
        'pages.default',
        'cms::web.page.content'
    ])
@stop
