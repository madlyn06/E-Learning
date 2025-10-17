<div class="container">
    <h1>{{ $post->name }}</h1>
    <div class="post-meta">
        <div class="meta-tags">
{{--            Tags: {!! $post->getTagLinks() !!}--}}
        </div>
    </div>
    <div class="content page-content">
        {!! $post->content !!}
    </div>
    <div class="clearfix"></div>
</div>
