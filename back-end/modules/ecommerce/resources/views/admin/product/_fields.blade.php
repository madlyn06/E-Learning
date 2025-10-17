@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#ecommerce_product_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#ecommerce_product_Rating">
            {{ __('Đánh giá') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#ecommerce_product_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="ecommerce_product_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('ecommerce::product.name'), 'required' => true])
                @input(['name' => 'origin_price', 'label' => __('ecommerce::product.origin_price'), 'mask' => 'money'])
                @input(['name' => 'sale_price', 'label' => __('ecommerce::product.sale_price'), 'mask' => 'money'])
                @editor(['name' => 'description', 'label' => __('ecommerce::product.description')])
                @editor(['name' => 'content', 'label' => __('ecommerce::product.content')])
                @gallerymanager(['name' => 'gallery', 'media_type' => 'gallery', 'label' => __('ecommerce::product.gallery')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('ecommerce::product.is_active'), 'default' => true])
                @mediamanager(['name' => 'image', 'label' => __('ecommerce::product.image')])
                @sumoselect(['name' => 'categories', 'label' => __('ecommerce::product.categories'), 'multiple' => true, 'options' => get_category_products_parent_options()])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="ecommerce_product_Rating">
        @include('ecommerce::admin.product.rating', ['items' => $item ? $item->ratings : [], 'post' => $item])
    </div>

    <div class="tab-pane fade" id="ecommerce_product_Seo">
        @seo
    </div>
</div>
