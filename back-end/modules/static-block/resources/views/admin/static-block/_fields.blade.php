@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#staticblock_static-block_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#staticblock_static-block_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="staticblock_static-block_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('staticblock::static-block.name')])
                @textarea(['name' => 'description', 'label' => __('staticblock::static-block.description')])
                @editor(['name' => 'content', 'label' => __('staticblock::static-block.content')])
                @input(['name' => 'css', 'label' => __('staticblock::static-block.css')])
                @input(['name' => 'script', 'label' => __('staticblock::static-block.script')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('staticblock::static-block.is_active'), 'default' => true])
                @mediamanager(['name' => 'image', 'label' => __('cms::post.image')])

            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="staticblock_static-block_Seo">
        @seo
    </div>
</div>
