@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsCategoryInfo">
            {{ __('cms::category.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsCategorySeo">
            {{ __('cms::category.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsCategoryInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::category.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('cms::category.description')])
                @editor(['name' => 'content', 'label' => __('cms::category.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => __('cms::category.is_active'), 'default' => true])
                @if(config('cms.cms.media_manager') && config('cms.cms.media_manager') == true)
                    @mediamanager(['name' => 'image', 'label' => __('cms::category.image')])
                @else
                    @mediafile(['name' => 'image', 'label' => __('cms::category.image')])
                @endif
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="cmsCategorySeo">
        @seo
    </div>
</div>
