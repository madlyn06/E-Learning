@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#seo_ads_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#seo_ads_Item">
            {{ __('Items') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#seo_ads_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="seo_ads_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'code', 'label' => __('seo::ads.code'), 'required' => true])
                @input(['name' => 'title', 'label' => __('seo::ads.title'), 'required' => true])
                @editor(['name' => 'content', 'label' => __('seo::ads.content'), 'required' => true])
                @input(['name' => 'btn_name', 'label' => __('seo::ads.btn_name'), 'default' => 'Download'])
                @input(['name' => 'icon_btn', 'label' => __('seo::ads.icon_btn'), 'default' => 'fa fa-download'])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'placeholder' => __('seo::ads.is_active'), 'default' => true, 'label' => ''])
                @datetimeinput(['name' => 'valid_from', 'label' => __('seo::ads.valid_from'),])
                @datetimeinput(['name' => 'valid_to', 'label' => __('seo::ads.valid_to'),])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="seo_ads_Item">
        @include('seo::admin.ads-item.index', ['items' => $item->adsItems ?? null])
    </div>

    <div class="tab-pane fade" id="seo_ads_Seo">
        @seo
    </div>
</div>
