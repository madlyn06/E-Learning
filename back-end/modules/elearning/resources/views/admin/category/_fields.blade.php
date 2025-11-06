@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#elearningCategoryInfo">
            {{ __('elearning::category.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#elearningCategorySeo">
            {{ __('elearning::category.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="elearningCategoryInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('elearning::category.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('elearning::category.description')])
                @editor(['name' => 'content', 'label' => __('elearning::category.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('elearning::category.is_active'), 'default' => true])
                @select(['name' => 'parent_id', 'label' => __('elearning::category.parent'), 'options' => elearning_get_category_parent_options()])
                @mediamanager(['name' => 'image', 'label' => __('elearning::category.image')])
                @input(['name' => 'icon', 'label' => __('elearning::category.icon'),])
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="elearningCategorySeo">
        @seo
    </div>
</div>
