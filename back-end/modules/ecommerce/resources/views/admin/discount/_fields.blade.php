@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#ecommerce_transaction_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#ecommerce_apply_for">
            {{ __('Điều kiện áp dụng') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#ecommerce_transaction_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="ecommerce_transaction_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('ecommerce::discount.name'), 'required' => true])
                @select(['name' => 'type', 'label' => __('ecommerce::discount.type'), 'allowClear' => false, 'options' => get_discount_type_options()])
                @input(['name' => 'value', 'label' => __('ecommerce::discount.value'), 'required' => true])
                @datetimeinput(['name' => 'valid_from', 'label' => __('ecommerce::discount.valid_from'),])
                @datetimeinput(['name' => 'valid_to', 'label' => __('ecommerce::discount.valid_to'),])
                @textarea(['name' => 'description', 'label' => __('ecommerce::discount.description')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('ecommerce::discount.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade show" id="ecommerce_apply_for">
        <div class="row">
            <div class="col-12 col-md-12">
                @sumoselect(['name' => 'products', 'label' => __('ecommerce::discount.products'), 'multiple' => true, 'options' => get_products_options()])
                @sumoselect(['name' => 'categories', 'label' => __('ecommerce::discount.categories'), 'multiple' => true, 'options' => get_category_products_parent_options()])
                @checkbox(['name' => 'is_apply_all', 'label' => __('ecommerce::discount.is_apply_all'), 'default' => true])
                @input(['name' => 'max_applies', 'label' => __('ecommerce::discount.max_applies'),])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="ecommerce_transaction_Seo">
        @seo
    </div>
</div>
