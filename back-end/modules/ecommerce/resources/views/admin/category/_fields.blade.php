@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#ecommerce_category_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#ecommerce_category_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="ecommerce_category_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::category.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('cms::category.description')])
                @editor(['name' => 'content', 'label' => __('cms::category.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => __('cms::category.is_active'), 'default' => true])
                @select(['name' => 'parent_id', 'label' => __('cms::category.parent'), 'options' => get_category_products_parent_options()])
                @if(config('cms.cms.media_manager') && config('cms.cms.media_manager') == true)
                    @mediamanager(['name' => 'image', 'label' => __('cms::category.image')])
                @else
                    @mediafile(['name' => 'image', 'label' => __('cms::category.image')])
                @endif
                @input(['name' => 'icon', 'label' => __('cms::category.icon'),
                'helper' => "<a href='#' data-toggle='modal' data-target='#modalIcon'><i class='fas fa-question-circle mr-2'></i>Các icon hỗ trợ</a> "])

            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="ecommerce_category_Seo">
        @seo
    </div>
</div>
