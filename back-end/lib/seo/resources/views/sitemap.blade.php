<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $url)
    <url>
        <loc>{{ url($url->request_path) }}</loc>
        @if($updated_at = object_get($url, 'urlable.updated_at'))
            <lastmod>{{ $updated_at->toIso8601String() }}</lastmod>
        @else
            <lastmod>{{ $url->updated_at->toIso8601String() }}</lastmod>
        @endif
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
@endforeach
</urlset>
