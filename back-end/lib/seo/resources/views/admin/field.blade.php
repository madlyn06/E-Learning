<div class="row">
    <div class="col-md-{{ !empty($col) ? $col : 6 }}">

        @input([
            'name' => 'seometa[title]',
            'label' => __('seo::meta.title')
        ])

        <input type="hidden" value="{{ config('app.front_end_url') }}" id="front-end-url">

        @slug([
            'name' => 'seourl[request_path]',
            'label' => __('seo::meta.url'),
            'slugPrefix' => $slugPrefix ?? ''
        ])

        @textarea([
            'name' => 'seometa[description]',
            'label' => __('seo::meta.description')
        ])

        @textarea([
            'name' => 'seometa[keywords]',
            'label' => __('seo::meta.keywords')
        ])
    </div>
    <div class="col-md-{{ !empty($col) ? $col : 6 }}">
        <div id="seoDesktopPreview"></div>
        <div id="seoMobilePreview"></div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-{{ !empty($col) ? $col : 6 }}">
        @input([
            'name' => 'seometa[og_title]',
            'label' => __('seo::meta.og_title')
        ])

        @textarea([
            'name' => 'seometa[og_description]',
            'label' => __('seo::meta.og_description')
        ])

        @mediafile([
            'name' => 'seometa[og_image]',
            'label' => __('seo::meta.og_image'),
        ])
    </div>
    <div class="col-md-6">
        <div id="seoFacebookPreview"></div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-{{ !empty($col) ? $col : 6 }}">
        @input([
            'name' => 'seometa[twitter_title]',
            'label' => __('seo::meta.twitter_title')
        ])

        @textarea([
            'name' => 'seometa[twitter_description]',
            'label' => __('seo::meta.twitter_description')
        ])

        @mediafile([
            'name' => 'seometa[twitter_image]',
            'label' => __('seo::meta.twitter_image'),
        ])
    </div>
    <div class="col-md-{{ !empty($col) ? $col : 6 }}">
        <div id="seoTwitterPreview"></div>
    </div>
</div>

@assetadd('seo.script', 'vendor/seo/admin/js/seo.js', ['jquery'])
