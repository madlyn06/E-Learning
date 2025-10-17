@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsContentListInfo">
            {{ __('cms::category.tabs.info') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsContentListInfo">
        <div class="row">
            <div class="col-12 col-md-12">
                @input(['name' => 'name', 'label' => __('cms::content-list.name')])
                @input(['name' => 'target', 'label' => __('cms::content-list.target')])
            </div>
        </div>
    </div>
</div>
