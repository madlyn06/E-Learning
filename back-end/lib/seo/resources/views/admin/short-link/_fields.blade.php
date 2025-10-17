@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#seo_short_link_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#seo_short_link_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="seo_short_link_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'code', 'label' => __('seo::short-link.code'), 'required' => true])
                @textarea(['name' => 'content_urls', 'label' => __('seo::short-link.content_urls'), 'required' => true])
                @input(['name' => 'text', 'label' => __('seo::short-link.text'), 'required' => true])
                @input(['name' => 'css', 'label' => __('seo::short-link.css'),])
                @select(['name' => 'target', 'label' => __('seo::short-link.target'), 'options' => get_target_options(), 'allowClear' => false])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('seo::short-link.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="seo_short_link_Seo">
        @seo
    </div>
</div>
