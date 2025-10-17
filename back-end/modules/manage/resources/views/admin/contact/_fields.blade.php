@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#manage_contact_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#manage_contact_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="manage_contact_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('manage::contact.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('manage::contact.description')])
                @editor(['name' => 'content', 'label' => __('manage::contact.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('manage::contact.is_active'), 'default' => true])
                @mediamanager(['name' => 'image', 'label' => __('cms::category.image')])

            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="manage_contact_Seo">
        @seo
    </div>
</div>
