@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#manage_faq_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#manage_faq_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="manage_faq_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('manage::faq.name'), 'required' => true])
                @textarea(['name' => 'answer', 'label' => __('manage::faq.answer'), 'required' => true])
                @editor(['name' => 'content', 'label' => __('manage::faq.content'),])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('manage::faq.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="manage_faq_Seo">
        @seo
    </div>
</div>
