@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#manage_client_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#manage_client_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="manage_client_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('manage::client.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('manage::client.description')])
                @editor(['name' => 'content', 'label' => __('manage::client.content')])
                @input(['name' => 'stars', 'label' => __('manage::client.stars'), 'required' => true])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('manage::client.is_active'), 'default' => true])
                @mediamanager(['name' => 'image', 'label' => __('manage::client.image')])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="manage_client_Seo">
        @seo
    </div>
</div>
