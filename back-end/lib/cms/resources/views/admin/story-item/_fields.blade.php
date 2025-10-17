@translatableAlert

<div class="row">
    <div class="col-12 col-md-8">
        <div class="form-horizontal">
            @mediamanager(['name' => 'image', 'label' => __('cms::story-item.image'), 'conversion' => '',])
            @input(['name' => 'name', 'label' => __('cms::story-item.name'), 'required' => true])
            @input(['name' => 'link', 'label' => __('cms::story-item.link')])
            @textarea(['name' => 'description', 'label' => __('cms::story-item.description')])
            @editor(['name' => 'content', 'label' => __('cms::story-item.content')])
            @input(['name' => 'addition_image', 'label' => __('cms::story-item.addition_image')])
        </div>
    </div>
    <div class="col-12 col-md-4">
        @translatable

        @checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('cms::story-item.is_active')])
        @input(['name' => 'sort_order', 'label' => __('cms::story-item.sort_order')])
        @input(['name' => 'auto_play_after', 'default' => 10, 'label' => __('cms::story-item.auto_play_after')])
        @mediamanager(['name' => 'audio', 'label' => __('cms::story-item.audio'), 'conversion' => ''])
    </div>
</div>
