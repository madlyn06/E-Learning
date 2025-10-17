@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsPostInfo">
            {{ __('cms::keyword.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsPostSeo">
            {{ __('cms::keyword.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsPostInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::keyword.name')])
                @textarea(['name' => 'description', 'label' => __('cms::keyword.description'), 'autoResize' => true])
            </div>
            <div class="col-12 col-md-3">
                @translatable
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="cmsPostSeo">
        @seo
    </div>
</div>
