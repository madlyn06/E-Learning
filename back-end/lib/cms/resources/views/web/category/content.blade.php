<div class="container">
    <h1>{{ $category->name }}</h1>

    <div class="media-list">
        @foreach($posts as $post)
            <div class="media my-3">
                @if($post->hasMedia('image'))
                    <img src="{{ $post->getFirstMediaUrl('image') }}" class="mr-3" alt="{{ $post->name }}" width="100">
                @endif
                <div class="media-body">
                    <h2 class="mt-0 mb-1" style="font-size: initial; font-weight: bold; margin: 0;">
                        <a href="{{ $post->url }}" style="color: #333;">
                            {{ $post->name }}
                        </a>
                    </h2>
                    <div class="post-meta">
                        @foreach($post->categories as $metaCategory)
                            <a href="{{ $metaCategory->url }}">
                                {{ $metaCategory->name }}
                            </a>
                        @endforeach
                    </div>
                    <div class="description">
                        {{ $post->description }}
                    </div>
                </div>
            </div>
        @endforeach

        {!! $posts->appends(Request::all())->render() !!}
    </div>
</div>
