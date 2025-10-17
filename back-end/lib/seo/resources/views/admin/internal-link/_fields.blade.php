@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#seo_internal-link_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#seo_internal-link_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="seo_internal-link_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('seo::internal-link.name'), 'required' => true])
                @input(['name' => 'value', 'label' => __('seo::internal-link.value'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('seo::internal-link.description')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('seo::internal-link.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="seo_internal-link_Seo">
        @seo
    </div>
</div>
