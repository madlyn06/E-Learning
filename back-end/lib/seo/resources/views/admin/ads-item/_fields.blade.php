@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#seo_ads_items_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="seo_ads_items_Info">
        <div class="row">
            <div class="col-12 col-md-12">
                @input(['name' => 'title', 'label' => __('seo::ads.title'), 'required' => true])
                @mediamanager(['name' => 'image', 'label' => __('seo::ads.image')])
                @mediamanager(['name' => 'image_search_google', 'label' => __('seo::ads.image_search_google')])
                @checkbox(['name' => 'is_active', 'placeholder' => __('seo::ads.is_active'), 'label' => '', 'default' => true])
            </div>
        </div>
    </div>
</div>
