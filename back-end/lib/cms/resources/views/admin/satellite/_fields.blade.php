@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsPageInfo">
            {{ __('cms::page.tabs.info') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsPageInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::satellite.name')])
                @input(['name' => 'url', 'label' => __('URL'), 'required' => true])
                @select(['name' => 'category_id', 'label' => __('Danh mục'), 'options' => get_category_parent_options()])
                @editor(['name' => 'description', 'label' => __('cms::page.description')])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => __('cms::page.is_active'), 'default' => true])
                @mediamanager(['name' => 'image', 'label' => __('cms::page.image')])
            </div>
        </div>
    </div>
</div>
