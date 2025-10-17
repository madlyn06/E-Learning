@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#ecommerce_transaction_Info">
            {{ __('Tab Info') }}
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
                @input(['name' => 'name', 'label' => __('ecommerce::payment-method.name'), 'required' => true])
                @input(['name' => 'code', 'label' => __('ecommerce::payment-method.code'), 'required' => true])
                @input(['name' => 'owner', 'label' => __('ecommerce::payment-method.owner'), 'required' => true])
                @input(['name' => 'number', 'label' => __('ecommerce::payment-method.number'), 'required' => true])
                @input(['name' => 'branch', 'label' => __('ecommerce::payment-method.branch'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('ecommerce::payment-method.description')])
                @mediafile(['name' => 'image', 'label' => __('cms::post.image')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('ecommerce::payment-method.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="ecommerce_transaction_Seo">
        @seo
    </div>
</div>
