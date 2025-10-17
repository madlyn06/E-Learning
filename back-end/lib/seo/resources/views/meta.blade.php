<meta name="description" content="{{ object_get($item, 'seometa.description') ?: object_get($item, 'description') ?: setting('seo_meta_description') }}"/>
<meta name="keywords" content="{{ object_get($item, 'seometa.keywords') ?: setting('seo_meta_keywords') }}"/>
<link rel="canonical" href="{{ object_get($item, 'seometa.canonical') ?: object_get($item, 'url') ?: request()->url() }}"/>

<meta property="og:locale" content="{{ app()->getLocale() }}"/>
<meta property="og:type" content="article"/>

<meta property="og:title" content="{{ object_get($item, 'seometa.og_title') ?: object_get($item, 'seometa.title') ?: object_get($item, 'name') ?: setting('seo_meta_og_title') ?: setting('seo_meta_title') }}"/>
<meta property="og:description" content="{{ object_get($item, 'seometa.og_description') ?: object_get($item, 'seometa.description') ?: object_get($item, 'description') ?: setting('seo_meta_og_description') ?: setting('seo_meta_description') }}"/>
<meta property="og:url" content="{{ object_get($item, 'url') ?: request()->url() }}"/>
<meta property="og:site_name" content="{{ setting('site_name') ?: setting('site_title_short') }}"/>

@if($published_time = object_get($item, 'published_at') ?? object_get($item, 'created_at'))
    <meta property="article:published_time" content="{{ $published_time->toAtomString() }}"/>
@endif

@if($modified_time = object_get($item, 'updated_at'))
    <meta property="article:modified_time" content="{{ $modified_time->toAtomString() }}"/>
    <meta property="og:updated_time" content="{{ $modified_time->toAtomString() }}"/>
@endif

@if($ogImage = object_get($item, 'seometa.og_image') ?: object_get($item, 'image') ?: get_setting_media('seo_meta_og_image'))
    <meta property="og:image" content="{{ $ogImage }}"/>
    <meta property="og:image:secure_url" content="{{ $ogImage }}"/>
    {{--<meta property="og:image:width" content=""/>--}}
    {{--<meta property="og:image:height" content=""/>--}}
@endif

<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="{{ object_get($item, 'seometa.twitter_title') ?: object_get($item, 'seometa.title') ?: object_get($item, 'name') ?: setting('seo_meta_twitter_title') ?: setting('seo_meta_title') }}"/>
<meta name="twitter:description" content="{{ object_get($item, 'seometa.twitter_description') ?: object_get($item, 'seometa.description') ?: object_get($item, 'description') ?: setting('seo_meta_twitter_description') ?: setting('seo_meta_description') }}"/>

@if($twitterImage = object_get($item, 'seometa.twitter_image') ?: object_get($item, 'image') ?: get_setting_media('seo_meta_twitter_image'))
    <meta name="twitter:image" content="{{ $twitterImage }}"/>
@endif

{{--<meta name="twitter:creator" content="@newnet"/>--}}
{{--<script type='application/ld+json'></script>--}}
