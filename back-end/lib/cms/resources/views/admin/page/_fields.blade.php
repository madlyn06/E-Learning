@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsPageInfo">
            {{ __('cms::page.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsPageSeo">
            {{ __('cms::page.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsPageInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::page.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('cms::page.description'), 'autoResize' => true])
                @editor(['name' => 'content', 'label' => __('cms::page.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => __('cms::page.is_active'), 'default' => true])
                @select(['name' => 'parent_id', 'label' => __('cms::page.parent'), 'options' => get_page_parent_options()])
                @select(['name' => 'page_layout', 'label' => __('cms::page.page_layout'), 'options' => get_page_layout_options(), 'allowClear' => true])
                @if(config('cms.cms.media_manager') && config('cms.cms.media_manager') == true)
                    @mediamanager(['name' => 'image', 'label' => __('cms::page.image')])
                @else
                    @mediafile(['name' => 'image', 'label' => __('cms::page.image')])
                @endif
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="cmsPageSeo">
        @seo
    </div>
</div>
